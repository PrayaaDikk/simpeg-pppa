<?php

namespace App\Services;

use App\Models\Kgb;
use App\Models\Pegawai;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class KgbService
{
    /**
     * Mengambil seluruh data index KGB yang terbagi berdasarkan tab data.
     */
    public function getIndexData(array $filters): array
    {
        $search = $filters['search'] ?? null;
        $statusKgb = $filters['status_kgb'] ?? null;
        $bulanKgb = $filters['bulan_kgb'] ?? null;
        $activeTab = $filters['active_tab'] ?? 'semua-riwayat';
        $rekapBulan = $filters['rekap_bulan'] ?? Carbon::now()->format('Y-m');

        $thisMonth = Carbon::now()->format('Y-m');
        $relations = ['pegawai.user', 'pegawai.bidang', 'pegawai.jabatan', 'pegawai.pangkat'];

        // --- 1. TAB SELURUH RIWAYAT ---
        $queryAll = Kgb::with($relations);
        if ($search) {
            $queryAll->whereHas('pegawai', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }
        if ($statusKgb) {
            $queryAll->where('status_kgb', $statusKgb);
        }
        if ($bulanKgb) {
            $queryAll->where('kgb_berikutnya', 'like', "{$bulanKgb}%");
        }
        $kgbList = $queryAll->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page')
            ->appends(request()->all());

        // --- 2. TAB ANTRIAN VERIFIKASI (STATUS: MENUNGGU) ---
        $pendingKgbList = Kgb::with($relations)
            ->where('status_kgb', 'menunggu')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('pegawai', function ($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate(10, ['*'], 'pending_page')
            ->appends(['active_tab' => $activeTab, 'search' => $search]);

        // --- 3. TAB JADWAL BULAN INI (HANYA STATUS 'DISETUJUI') ---
        $currentMonthKgbList = Kgb::with($relations)
            ->where('kgb_berikutnya', 'like', "{$thisMonth}%")
            ->where('status_kgb', 'disetujui')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('pegawai', function ($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->orderBy('kgb_berikutnya', 'asc')
            ->paginate(10, ['*'], 'current_month_page')
            ->appends(['active_tab' => $activeTab, 'search' => $search]);

        // --- 4. TAB REKAPITULASI BULANAN ---
        $monthlyRekapKgbList = Kgb::with($relations)
            ->where('kgb_berikutnya', 'like', "{$rekapBulan}%")
            ->where(function ($q) {
                $q->where('status_kgb', 'disetujui')
                    ->orWhere('status_kgb', 'telah diproses');
            })
            ->when($search, function ($q) use ($search) {
                $q->whereHas('pegawai', function ($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->orderBy('kgb_berikutnya', 'asc')
            ->paginate(10, ['*'], 'rekap_page')
            ->appends([
                'active_tab' => $activeTab,
                'rekap_bulan' => $rekapBulan,
                'search' => $search
            ]);

        // --- 5. AGREGASI HITUNG TOTAL UNTUK BADGE COUNT ---
        $totalPending = Kgb::where('status_kgb', 'menunggu')->count();
        $totalCurrentMonth = Kgb::where('kgb_berikutnya', 'like', "{$thisMonth}%")
            ->where('status_kgb', 'disetujui')
            ->count();

        return [
            'kgbList'             => $kgbList,
            'pendingKgbList'      => $pendingKgbList,
            'currentMonthKgbList' => $currentMonthKgbList,
            'monthlyRekapKgbList' => $monthlyRekapKgbList,
            'counts' => [
                'pending'       => $totalPending,
                'current_month' => $totalCurrentMonth,
            ],
            'thisMonth' => $thisMonth
        ];
    }

    /**
     * Memproses penyimpanan usulan berkas KGB baru.
     */
    public function storeKgb(array $data): Kgb
    {
        $data['status_kgb'] = 'disetujui';

        return DB::transaction(function () use ($data) {
            if (!empty($data['parent_id'])) {
                Kgb::where('id', $data['parent_id'])->update([
                    'status_kgb' => 'telah diproses'
                ]);
            }

            return Kgb::create($data);
        });
    }

    /**
     * Memproses pembaruan isi data rekaman KGB.
     */
    public function updateKgb(int $id, array $data): Kgb
    {
        $kgb = Kgb::findOrFail($id);
        $kgb->update($data);
        return $kgb;
    }

    /**
     * Memproses pembaruan status verifikasi KGB beserta auto-touch timestamp Pegawai.
     */
    public function updateKgbStatus(int $id, string $status): Kgb
    {
        $kgb = Kgb::findOrFail($id);

        DB::transaction(function () use ($kgb, $status) {
            $kgb->update(['status_kgb' => $status]);

            if ($status === 'disetujui') {
                $pegawai = Pegawai::find($kgb->pegawai_id);
                if ($pegawai) {
                    $pegawai->touch();
                }
            }
        });

        return $kgb;
    }

    /**
     * Memproses penghapusan data KGB dan melakukan otomatisasi rollback status data sebelumnya.
     */
    public function deleteKgb(int $id): void
    {
        $kgb = Kgb::findOrFail($id);

        DB::transaction(function () use ($kgb) {
            $previousKgb = Kgb::where('pegawai_id', $kgb->pegawai_id)
                ->where('id', '<', $kgb->id)
                ->where('status_kgb', 'telah diproses')
                ->orderBy('id', 'desc')
                ->first();

            if ($previousKgb) {
                $previousKgb->update(['status_kgb' => 'disetujui']);
            }

            $kgb->delete();
        });
    }
}
