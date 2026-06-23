<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RiwayatPangkatRequest;
use App\Services\RiwayatPangkatService;
use Illuminate\Support\Facades\Log;
use Exception;

class RiwayatPangkatController extends Controller
{
    protected $pangkatService;

    /**
     * Inject RiwayatPangkatService via Constructor.
     */
    public function __construct(RiwayatPangkatService $pangkatService)
    {
        $this->pangkatService = $pangkatService;
    }

    /**
     * Menyimpan riwayat pangkat baru pegawai.
     */
    public function store(RiwayatPangkatRequest $request, $pegawai_id)
    {
        try {
            $this->pangkatService->store($request->validated(), (int) $pegawai_id);

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Data riwayat kepangkatan berhasil ditambahkan ke dalam sistem.'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal menyimpan riwayat pangkat pegawai ID {$pegawai_id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Gagal menyimpan data riwayat pangkat. Terjadi kendala internal server.'
            ]);
        }
    }

    /**
     * Memperbarui data riwayat pangkat pegawai.
     */
    public function update(RiwayatPangkatRequest $request, $pegawai_id, $id)
    {
        try {
            $this->pangkatService->update((int) $id, $request->validated(), (int) $pegawai_id);

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Data riwayat kepangkatan berhasil diperbarui.'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal memperbarui riwayat pangkat ID {$id} pegawai ID {$pegawai_id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Gagal memperbarui data riwayat pangkat. Silakan hubungi tim teknis.'
            ]);
        }
    }

    /**
     * Menghapus record riwayat pangkat pegawai secara permanen.
     */
    public function destroy($pegawai_id, $id)
    {
        try {
            $this->pangkatService->delete((int) $id, (int) $pegawai_id);

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Data riwayat kepangkatan beserta dokumen lampiran telah berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal menghapus riwayat pangkat ID {$id} pegawai ID {$pegawai_id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Gagal menghapus data riwayat kepangkatan dari server.'
            ]);
        }
    }

    /**
     * Menghapus hanya berkas file SK dari riwayat pangkat tertentu.
     */
    public function deleteSkFile($pegawai_id, $id)
    {
        try {
            $this->pangkatService->deleteSkDocument((int) $id, (int) $pegawai_id);

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Berkas lampiran dokumen SK berhasil dihapus dari server.'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal mencopot dokumen SK pangkat ID {$id} pegawai ID {$pegawai_id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Gagal menghapus berkas dokumen SK dari server.'
            ]);
        }
    }
}
