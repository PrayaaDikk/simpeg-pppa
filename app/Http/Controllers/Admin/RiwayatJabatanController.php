<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RiwayatJabatanRequest;
use App\Services\RiwayatJabatanService;
use Illuminate\Support\Facades\Log;
use Exception;

class RiwayatJabatanController extends Controller
{
    protected $jabatanService;

    /**
     * Dependency Injection via Constructor.
     */
    public function __construct(RiwayatJabatanService $jabatanService)
    {
        $this->jabatanService = $jabatanService;
    }

    /**
     * Menyimpan data riwayat jabatan baru pegawai.
     */
    public function store(RiwayatJabatanRequest $request, $pegawai_id)
    {
        try {
            $this->jabatanService->store($request->validated(), (int) $pegawai_id);

            return redirect()->back()->with('flash', [
                'type'    => 'success',
                'message' => 'Data riwayat kedudukan jabatan berhasil ditambahkan ke dalam sistem.'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal menyimpan riwayat jabatan pegawai ID {$pegawai_id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('flash', [
                'type'    => 'error',
                'message' => 'Gagal menyimpan data riwayat jabatan. Terjadi kendala internal server.'
            ]);
        }
    }

    /**
     * Memperbarui riwayat jabatan pegawai.
     */
    public function update(RiwayatJabatanRequest $request, $pegawai_id, $id)
    {
        try {
            $this->jabatanService->update((int) $id, $request->validated(), (int) $pegawai_id);

            return redirect()->back()->with('flash', [
                'type'    => 'success',
                'message' => 'Data riwayat kedudukan jabatan berhasil diperbarui.'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal memperbarui riwayat jabatan ID {$id} pegawai ID {$pegawai_id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('flash', [
                'type'    => 'error',
                'message' => 'Gagal memperbarui data riwayat jabatan. Silakan hubungi tim teknis.'
            ]);
        }
    }

    /**
     * Menghapus riwayat jabatan pegawai.
     */
    public function destroy($pegawai_id, $id)
    {
        try {
            $this->jabatanService->delete((int) $id, (int) $pegawai_id);

            return redirect()->back()->with('flash', [
                'type'    => 'success',
                'message' => 'Data riwayat kedudukan jabatan berhasil dihapus dari sistem.'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal menghapus riwayat jabatan ID {$id} pegawai ID {$pegawai_id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('flash', [
                'type'    => 'error',
                'message' => 'Gagal menghapus data riwayat jabatan dari server.'
            ]);
        }
    }
}
