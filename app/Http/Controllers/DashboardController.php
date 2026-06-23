<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Cuti;
use App\Models\Kgb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // JIKA USER ADALAH PEGAWAI, ARAHKAN KE FLOW DATA PEGAWAI
        if ($user->hasRole('pegawai')) {
            return $this->employeeDashboard($user);
        }

        // =====================================================================
        // FLOW ADMIN - FIX COMPATIBILITY FOR MYSQL (NO LIMIT INSIDE IN SUBQUERY)
        // =====================================================================

        // 1. Total Pegawai Aktif
        $totalPegawai = Pegawai::where('is_active', true)
            ->whereDoesntHave('user', function ($q) {
                $q->role('superadmin');
            })->count();

        // 2. Cuti Menunggu Approval
        $cutiMenunggu = Cuti::where('status_cuti', 'menunggu')->count();

        // 3. Update KGB Terjadwal Menunggu Tindakan
        $thisMonth = Carbon::now()->format('Y-m');
        $latestApprovedMonthKgbSubquery = DB::table('kgb')
            ->select(DB::raw('MAX(id)'))
            ->where('kgb_berikutnya', 'like', "{$thisMonth}%")
            ->where('status_kgb', 'disetujui')
            ->groupBy('pegawai_id');

        // Menghitung total pegawai unik yang memiliki KGB disetujui pada bulan ini
        $kgbUpdate = Kgb::whereIn('id', $latestApprovedMonthKgbSubquery)->count();

        // 4. Data Tren Pertumbuhan Pegawai Bulanan (MySQL DATE_FORMAT)
        $monthsData = Pegawai::where('is_active', true)
            ->whereDoesntHave('user', function ($q) {
                $q->role('superadmin');
            })
            ->whereYear('created_at', '2026')
            ->select(DB::raw("DATE_FORMAT(created_at, '%m') as month_code"), DB::raw('count(*) as count'))
            ->groupBy('month_code')
            ->orderBy('month_code', 'asc')
            ->get();

        $monthMapping = [
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'Mei',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Agu',
            '09' => 'Sep',
            '10' => 'Okt',
            '11' => 'Nov',
            '12' => 'Des'
        ];

        $chartData = [];
        foreach ($monthMapping as $code => $label) {
            $found = $monthsData->firstWhere('month_code', $code);
            $chartData[] = [
                'month' => $label,
                'count' => $found ? (int)$found->count : 0
            ];
        }

        // 5. Distribusi Bidang
        $bidangData = Pegawai::where('pegawai.is_active', true)
            ->whereDoesntHave('user', function ($q) {
                $q->role('superadmin');
            })
            ->leftJoin('bidang', 'pegawai.bidang_id', '=', 'bidang.id')
            ->select(
                DB::raw('COALESCE(bidang.nama_bidang, "Belum Ditentukan") as nama_bidang'),
                // Ambil data akronim asli dari tabel bidang, jika null berikan fallback 'BD' (Belum Ditentukan)
                DB::raw('COALESCE(bidang.akronim, "Non Bidang") as akronim'),
                DB::raw('count(*) as jumlah')
            )
            ->groupBy('nama_bidang', 'bidang.akronim') // Wajib masukkan akronim ke groupBy
            ->get();

        $distribusiBidang = $bidangData->map(function ($item) {
            return [
                'nama_bidang' => $item->nama_bidang,
                // Sekarang $item->akronim sudah membawa data asli dari database
                'akronim' => !empty($item->akronim) ? $item->akronim : substr($item->nama_bidang, 0, 3),
                'jumlah' => (int)$item->jumlah,
            ];
        })->toArray();

        // 6. Distribusi Jenis Kelamin
        $genderData = Pegawai::where('is_active', true)
            ->whereDoesntHave('user', function ($q) {
                $q->role('superadmin');
            })
            ->select('jenis_kelamin', DB::raw('count(*) as jumlah'))
            ->groupBy('jenis_kelamin')
            ->get();

        $distribusiGender = $genderData->map(function ($item) {
            $rawGender = strtolower($item->jenis_kelamin);
            $readableName = 'Belum Ditentukan';

            if ($rawGender === 'l' || $rawGender === 'laki-laki') {
                $readableName = 'Laki-laki';
            } elseif ($rawGender === 'p' || $rawGender === 'perempuan') {
                $readableName = 'Perempuan';
            }

            return [
                'name' => $readableName,
                'value' => (int)$item->jumlah
            ];
        })->toArray();

        // 7. Distribusi Pendidikan Tertinggi - SOLUSI FIX ERROR 1235 (MENGGUNAKAN CO-RELATED MAX WEIGHT)
        $paraPegawai = Pegawai::where('is_active', true)
            ->whereDoesntHave('user', function ($q) {
                $q->role('superadmin');
            })
            ->with('pendidikanTerakhir')
            ->get();

        // Buat struktur urutan kaku (SMA -> S3) untuk grafik
        $standarUrutan = ['1', '2', '3', '4', '5', '6', '7', '8'];

        $distribusiPendidikan = collect($standarUrutan)->map(function ($tingkat) use ($paraPegawai) {
            // Hitung berapa pegawai yang pendidikan terakhirnya cocok dengan tingkat saat ini
            $jumlah = $paraPegawai->filter(function ($pegawai) use ($tingkat) {
                return $pegawai->pendidikanTerakhir
                    && trim(strtoupper($pegawai->pendidikanTerakhir->tingkat_pendidikan)) === $tingkat;
            })->count();

            return [
                'pendidikan' => $tingkat, // Menjamin label keluar sebagai TEXT murni (SMA, D1, S1, dst)
                'jumlah'     => $jumlah
            ];
        })->values()->toArray();

        // 8. Berkas Berjalan Menunggu Persetujuan (Recent Validations)
        $recentCuti = Cuti::where('status_cuti', 'menunggu')->with('pegawai')->orderBy('created_at', 'desc')->take(5)->get();
        $recentKgb = Kgb::where('status_kgb', 'menunggu')->with('pegawai')->orderBy('created_at', 'desc')->take(5)->get();

        $recentValidations = collect()
            ->merge($recentCuti)
            ->merge($recentKgb)
            ->sortByDesc('created_at')
            ->take(5)
            ->map(function ($item) {
                $nama = $item->pegawai->nama ?? 'Pegawai';
                $nip = $item->pegawai->nip ?? '-';
                $pegawaiId = $item->pegawai_id;

                $words = explode(' ', $nama);
                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

                // Ambil satu pendidikan tertinggi khusus list baris tabel dengan aman
                $pendidikanUser = DB::table('riwayat_pendidikan')
                    ->where('pegawai_id', $pegawaiId)
                    ->select('tingkat_pendidikan')
                    ->orderByRaw('CASE 
                        WHEN UPPER(tingkat_pendidikan) = "S3" THEN 7
                        WHEN UPPER(tingkat_pendidikan) = "S2" THEN 6
                        WHEN UPPER(tingkat_pendidikan) = "S1" THEN 5
                        WHEN UPPER(tingkat_pendidikan) = "D4" THEN 4
                        WHEN UPPER(tingkat_pendidikan) = "D3" THEN 3
                        WHEN UPPER(tingkat_pendidikan) = "D1" THEN 2
                        ELSE 0 
                    END DESC')
                    ->value('tingkat_pendidikan') ?? '-';

                if ($item instanceof Cuti) {
                    $tanggalMulai = Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y');
                    $tanggalAkhir = Carbon::parse($item->tanggal_selesai)->translatedFormat('d M Y');
                    return [
                        'id' => $item->id,
                        'pegawai' => [
                            'nama' => $nama,
                            'nip' => $nip,
                            'initials' => $initials,
                            'pendidikan_terakhir' => strtoupper($pendidikanUser),
                        ],
                        'jenis' => 'Cuti ' . ucfirst(str_replace('_', ' ', $item->jenis_cuti)),
                        'tanggal' => "{$tanggalMulai} - {$tanggalAkhir}",
                        'status' => 'PENDING',
                        'tipe' => 'CUTI',
                    ];
                } else {
                    $tanggalKgb = Carbon::parse($item->tmt_gaji_baru)->translatedFormat('d M Y');
                    return [
                        'id' => $item->id,
                        'pegawai' => [
                            'nama' => $nama,
                            'nip' => $nip,
                            'initials' => $initials,
                            'pendidikan_terakhir' => strtoupper($pendidikanUser),
                        ],
                        'jenis' => 'KGB Terjadwal',
                        'tanggal' => $tanggalKgb,
                        'status' => 'MENUNGGU SK',
                        'tipe' => 'KGB',
                    ];
                }
            })
            ->values()
            ->toArray();

        return Inertia::render('dashboard', [
            'totalPegawai' => $totalPegawai,
            'cutiMenunggu' => $cutiMenunggu,
            'kgbUpdate' => $kgbUpdate,
            'chartData' => $chartData,
            'distribusiBidang' => $distribusiBidang,
            'distribusiGender' => $distribusiGender,
            'distribusiPendidikan' => $distribusiPendidikan,
            'recentValidations' => $recentValidations,
        ]);
    }

    private function employeeDashboard($user)
    {
        $pegawai = Pegawai::where('user_id', $user->id)->with(['bidang', 'pangkat', 'jabatan'])->firstOrFail();
        $totalHariCuti = Cuti::where('pegawai_id', $pegawai->id)->where('status_cuti', 'disetujui')->sum('lama_cuti');
        $cutiTerakhir = Cuti::where('pegawai_id', $pegawai->id)->orderBy('created_at', 'desc')->first();
        $kgbTerakhir = Kgb::where('pegawai_id', $pegawai->id)->orderBy('tmt_gaji_baru', 'desc')->first();

        $riwayatCuti = Cuti::where('pegawai_id', $pegawai->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'tipe' => 'CUTI',
                    'judul' => 'Pengajuan Cuti ' . ucfirst(str_replace('_', ' ', $item->jenis_cuti)),
                    'status' => $item->status_cuti,
                    'tanggal_raw' => $item->created_at,
                    'tanggal' => Carbon::parse($item->created_at)->translatedFormat('d M Y')
                ];
            });

        $riwayatKgb = Kgb::where('pegawai_id', $pegawai->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'tipe' => 'KGB',
                    'judul' => 'Kenaikan Gaji Berkala (Gol. ' . ($item->golongan_baru ?? '-') . ')',
                    'status' => $item->status_kgb === 'disetujui' ? 'disetujui' : 'menunggu',
                    'tanggal_raw' => $item->created_at,
                    'tanggal' => Carbon::parse($item->created_at)->translatedFormat('d M Y')
                ];
            });

        $timelineAktivitas = collect()
            ->merge($riwayatCuti)
            ->merge($riwayatKgb)
            ->sortByDesc('tanggal_raw')
            ->values()
            ->toArray();

        return Inertia::render('pegawai/dashboard', [
            'role' => 'pegawai',
            'pegawai' => [
                'nama' => $pegawai->nama,
                'nip' => $pegawai->nip,
                'jabatan' => $pegawai->jabatan->nama_jabatan ?? '-',
                'pangkat' => $pegawai->pangkat->nama_pangkat ?? '-',
                'golongan' => $pegawai->pangkat->golongan ?? '-',
                'bidang' => $pegawai->bidang->nama_bidang ?? '-',
            ],
            'metrik' => [
                'total_hari_cuti' => (int) $totalHariCuti,
                'cuti_terakhir' => $cutiTerakhir ? [
                    'jenis' => ucfirst(str_replace('_', ' ', $cutiTerakhir->jenis_cuti)),
                    'status' => $cutiTerakhir->status_cuti,
                    'tanggal' => Carbon::parse($cutiTerakhir->created_at)->translatedFormat('d M Y')
                ] : null,
                'kgb_berikutnya' => $kgbTerakhir && $kgbTerakhir->kgb_berikutnya
                    ? Carbon::parse($kgbTerakhir->kgb_berikutnya)->translatedFormat('d F Y')
                    : 'Belum Terjadwal',
            ],
            'timelineAktivitas' => $timelineAktivitas,
        ]);
    }
}
