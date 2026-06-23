<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKgbRequest;
use App\Http\Requests\UpdateKgbRequest;
use App\Http\Requests\UpdateKgbStatusRequest;
use App\Models\Pegawai;
use App\Services\KgbService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class KgbController extends Controller
{
    protected $kgbService;

    // Menyuntikkan KgbService ke dalam Controller
    public function __construct(KgbService $kgbService)
    {
        $this->kgbService = $kgbService;
    }

    public function index(Request $request)
    {
        try {
            $filters = [
                'search'      => $request->input('search'),
                'status_kgb'  => $request->input('status_kgb'),
                'bulan_kgb'   => $request->input('bulan_kgb'),
                'active_tab'  => $request->input('active_tab', 'semua-riwayat'),
                'rekap_bulan' => $request->input('rekap_bulan', Carbon::now()->format('Y-m')),
            ];

            // Memanggil Service untuk mengambil tumpukan data terpaginasi
            $data = $this->kgbService->getIndexData($filters);
            $pegawaiList = Pegawai::with(['pangkat', 'jabatan', 'bidang'])->orderBy('nama', 'asc')
                ->whereDoesntHave('user', function ($q) {
                    $q->role('superadmin');
                })->get();

            return Inertia::render('admin/kgb/index', [
                'kgbList'             => $data['kgbList'],
                'pegawaiList'         => $pegawaiList,
                'pendingKgbList'      => $data['pendingKgbList'],
                'currentMonthKgbList' => $data['currentMonthKgbList'],
                'monthlyRekapKgbList' => $data['monthlyRekapKgbList'],
                'filters'             => $filters,
                'counts'              => $data['counts']
            ]);
        } catch (Exception $e) {
            Log::error("Sistem Error pada KgbController@index: " . $e->getMessage());
            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Terjadi kesalahan sistem saat memuat data KGB.'
            ]);
        }
    }

    public function store(StoreKgbRequest $request)
    {
        try {
            // Data otomatis tersanitasi & tervalidasi dari StoreKgbRequest
            $this->kgbService->storeKgb($request->validated());

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Data Kenaikan Gaji Berkala berikutnya berhasil diterbitkan.'
            ]);
        } catch (Exception $e) {
            Log::error("Sistem Error pada KgbController@store: " . $e->getMessage());
            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Terjadi kesalahan sistem saat menyimpan usulan KGB baru.'
            ]);
        }
    }

    public function update(UpdateKgbRequest $request, $id)
    {
        try {
            $this->kgbService->updateKgb($id, $request->validated());

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Data Kenaikan Gaji Berkala berhasil diperbarui.'
            ]);
        } catch (Exception $e) {
            Log::error("Sistem Error pada KgbController@update: " . $e->getMessage());
            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Terjadi kesalahan sistem saat memperbarui berkas KGB.'
            ]);
        }
    }

    public function updateStatus(UpdateKgbStatusRequest $request, $id)
    {
        try {
            $this->kgbService->updateKgbStatus($id, $request->validated()['status_kgb']);

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Status verifikasi keputusan berkas KGB berhasil diperbarui.'
            ]);
        } catch (Exception $e) {
            Log::error("Sistem Error pada KgbController@updateStatus: " . $e->getMessage());
            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Terjadi kesalahan sistem saat memperbarui status verifikasi.'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->kgbService->deleteKgb($id);

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Data Kenaikan Gaji Berkala berhasil dihapus dan status riwayat rujukan dikembalikan.'
            ]);
        } catch (Exception $e) {
            Log::error("Sistem Error pada KgbController@destroy: " . $e->getMessage());
            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Terjadi kesalahan sistem saat menghapus berkas KGB.'
            ]);
        }
    }
}
