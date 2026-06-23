<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RiwayatPendidikanRequest;
use App\Services\RiwayatPendidikanService;
use Illuminate\Support\Facades\Log;
use Exception;

class RiwayatPendidikanController extends Controller
{
    protected $pendidikanService;

    /**
     * Inject RiwayatPendidikanService ke Constructor.
     */
    public function __construct(RiwayatPendidikanService $pendidikanService)
    {
        $this->pendidikanService = $pendidikanService;
    }

    /**
     * Simpan riwayat pendidikan baru.
     */
    public function store(RiwayatPendidikanRequest $request, $pegawai_id)
    {
        try {
            $this->pendidikanService->store($request->validated(), (int) $pegawai_id);

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Data riwayat pendidikan berhasil ditambahkan ke dalam sistem.'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal menyimpan riwayat pendidikan pegawai ID {$pegawai_id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Gagal menyimpan data riwayat pendidikan. Terjadi gangguan pada server.'
            ]);
        }
    }

    /**
     * Perbarui data riwayat pendidikan.
     */
    public function update(RiwayatPendidikanRequest $request, $pegawai_id, $id)
    {
        try {
            $this->pendidikanService->update($request->validated(), (int) $id, (int) $pegawai_id);

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Data riwayat pendidikan berhasil diperbarui.'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal merubah riwayat pendidikan ID {$id} milik pegawai ID {$pegawai_id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Gagal memperbarui data riwayat pendidikan. Hubungi tim teknis.'
            ]);
        }
    }

    /**
     * Hapus record riwayat pendidikan.
     */
    public function destroy($pegawai_id, $id)
    {
        try {
            $this->pendidikanService->delete((int) $id, (int) $pegawai_id);

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Data riwayat pendidikan berhasil dihapus secara permanen.'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal menghapus riwayat pendidikan ID {$id} pegawai ID {$pegawai_id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Gagal menghapus data riwayat pendidikan dari server.'
            ]);
        }
    }

    public function deleteIjazah($pegawai_id, $id)
    {
        try {
            $this->pendidikanService->deleteIjazahFile((int) $id, (int) $pegawai_id);

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Berkas fisik ijazah berhasil dihapus dari server.'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal menghapus file ijazah ID {$id} pegawai ID {$pegawai_id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Gagal menghapus berkas ijazah. Silakan coba lagi.'
            ]);
        }
    }
}
