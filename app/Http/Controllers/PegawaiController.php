<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePegawaiRequest;
use App\Http\Requests\UpdatePegawaiRequest;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Pangkat;
use App\Models\Pegawai;
use App\Models\RiwayatJabatan;
use App\Models\RiwayatPangkat;
use App\Models\RiwayatPendidikan;
use App\Services\PegawaiService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PegawaiController extends Controller
{
    /**
     * @var PegawaiService
     */
    protected $pegawaiService;

    /**
     * Dependency Injection PegawaiService ke dalam Controller.
     */
    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    /**
     * Menampilkan daftar pegawai beserta master data filter.
     */
    public function index(Request $request)
    {
        try {
            // 1. Ambil kriteria filter array dari request GET query string
            $filters = $request->only([
                'search',
                'bidang_ids',
                'jabatan_ids',
                'pangkat_ids',
                'pendidikans',
                'statuses'
            ]);

            // 2. Format ulang filter agar dipetakan secara presisi ke dalam prop stateFilters di React frontend
            $stateFilters = [
                'search'      => $request->input('search', ''),
                'bidang_id'   => $request->input('bidang_ids', []),
                'jabatan_id'  => $request->input('jabatan_ids', []),
                'pangkat_id'  => $request->input('pangkat_ids', []),
                'pendidikans' => $request->input('pendidikans', []),
                'statuses'    => $request->input('statuses', []),
            ];

            // 3. Ambil data paginasi dari service
            $paginatedPegawai = $this->pegawaiService->getFilteredPegawais($filters, 10);

            return Inertia::render('admin/pegawai/index', [
                // Menggunakan ->items() untuk mengambil data array murni di dalam paginasi
                // agar cocok dengan pemanggilan `pegawaiList.map()` di React frontend
                'pegawaiList'  => $paginatedPegawai,
                'bidangList'   => Bidang::all(),
                'jabatanList'  => Jabatan::all(),
                'pangkatList'  => Pangkat::all(),
                'stateFilters' => $stateFilters,
            ]);
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman index PegawaiController: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => 'Gagal memuat halaman manajemen pegawai karena masalah internal server.'
            ]);
        }
    }

    /**
     * Memproses penyimpanan master data pegawai baru beserta akun sistem.
     */
    public function store(StorePegawaiRequest $request)
    {
        try {
            // Data dijamin sudah melewati proses sanitasi dan validasi di StorePegawaiRequest
            $this->pegawaiService->storePegawai($request->validated());

            return redirect()->back()->with('success', [
                'id' => uniqid('success_', true),
                'text' => 'Data Master Pegawai baru beserta konfigurasi akun sistem berhasil diterbitkan.'
            ]);
        } catch (Exception $e) {
            // Mencatat detail error transaksi database ke storage/logs/laravel.log
            Log::error('Gagal memproses storePegawai pada PegawaiController: ' . $e->getMessage(), [
                'input' => $request->validated(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id' => uniqid('error_', true),
                'text' => 'Gagal menerbitkan data pegawai baru. Terjadi kesalahan internal pada server database.'
            ]);
        }
    }

    /**
     * Memperbarui profil pegawai, kredensial user, dan kuota jatah cuti.
     */
    public function update(UpdatePegawaiRequest $request, $id)
    {
        try {
            // Eksekusi mutasi data via Service Layer
            $this->pegawaiService->updatePegawai((int) $id, $request->validated());

            return redirect()->back()->with('success', [
                'id' => uniqid('success_', true),
                'text' => 'Data profil pegawai, akun login, dan penyesuaian kuota cuti berhasil diperbarui.'
            ]);
        } catch (Exception $e) {
            // Mencatat detail kegagalan update ke log
            Log::error("Gagal memperbarui data pegawai ID {$id} pada PegawaiController: " . $e->getMessage(), [
                'id'    => $id,
                'input' => $request->validated(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id' => uniqid('error_', true),
                'text' => 'Gagal memperbarui data pegawai. Terjadi kesalahan sistem server internal.'
            ]);
        }
    }

    /**
     * Menghapus data pegawai beserta akun user terkait secara cascade.
     */
    public function destroy($id)
    {
        try {
            $this->pegawaiService->deletePegawai((int) $id);

            return redirect()->back()->with('success', [
                'id' => uniqid('success_', true),
                'text' => 'Data master pegawai dan seluruh hak akses akun terkait berhasil dihapus permanen.'
            ]);
        } catch (Exception $e) {
            Log::error("Gagal menghapus data pegawai ID {$id} pada PegawaiController: " . $e->getMessage(), [
                'id'    => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id' => uniqid('error_', true),
                'text' => 'Gagal menghapus data pegawai. Terjadi kesalahan sistem penyimpanan server.'
            ]);
        }
    }

    /**
     * Menampilkan riwayat kedudukan instansi (Pendidikan, Pangkat, Jabatan).
     */
    public function riwayat($id)
    {
        try {
            $pegawai = Pegawai::with(['pangkat', 'jabatan', 'bidang', 'user'])->findOrFail($id);

            return Inertia::render('admin/pegawai/riwayat', [
                'pegawai' => $pegawai,
                'riwayatPendidikan' => RiwayatPendidikan::where('pegawai_id', $id)->orderBy('tahun_lulus', 'desc')->get(),
                'riwayatPangkat'    => RiwayatPangkat::with('pangkat')->where('pegawai_id', $id)->orderBy('tmt_pangkat', 'desc')->get(),
                'riwayatJabatan'    => RiwayatJabatan::where('pegawai_id', $id)->orderBy('tmt_jabatan', 'desc')->get(),
                'masterPangkat'     => Pangkat::all(),
            ]);
        } catch (Exception $e) {
            Log::error("Gagal memuat halaman riwayat pegawai ID {$id}: " . $e->getMessage(), [
                'id'    => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id' => uniqid('error_', true),
                'text' => 'Gagal memuat arsip riwayat pegawai. Data tidak ditemukan atau rusak.'
            ]);
        }
    }
}
