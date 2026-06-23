<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCutiRequest;
use App\Http\Requests\UpdateCutiRequest;
use App\Models\Cuti;
use App\Models\Pegawai;
use App\Services\CutiCetakService;
use App\Services\CutiService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // <-- Ditambahkan untuk mencatat error ke log
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

/**
 * Class CutiController
 * Mengontrol lalu lintas HTTP data pengajuan cuti pegawai lingkup Admin.
 */
class CutiController extends Controller
{
    /**
     * @var CutiService
     */
    protected $cutiService;

    /**
     * Dependency Injection CutiService ke dalam Controller.
     */
    public function __construct(CutiService $cutiService, CutiCetakService $cutiCetakService)
    {
        $this->cutiService = $cutiService;
        $this->cutiCetakService = $cutiCetakService;
    }

    /**
     * Menampilkan daftar master berkas pengajuan cuti dengan pagination, search, dan tab status.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'jenis_cuti', 'status_cuti']);

        // Default tab status jika views pertama kali dirender
        if (empty($filters['status_cuti'])) {
            $filters['status_cuti'] = 'disetujui';
        }

        // Query relasi data profil kepegawaian secara komprehensif
        $query = Cuti::with([
            'pegawai.user',
            'pegawai.bidang',
            'pegawai.jabatan',
            'pegawai.pangkat',
            'atasan',
        ])->orderBy('created_at', 'desc');

        // Pencarian dinamis berdasarkan nama atau NIP pegawai
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('pegawai', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['jenis_cuti'])) {
            $query->where('jenis_cuti', $filters['jenis_cuti']);
        }

        if (empty($filters['status_cuti']) || $filters['status_cuti'] === 'rekapitulasi') {
            $filters['status_cuti'] = 'disetujui';
        }

        if (!empty($filters['status_cuti'])) {
            $query->where('status_cuti', $filters['status_cuti']);
        }

        $allApprovedCutis = Cuti::with(['pegawai.jabatan', 'atasan.jabatan'])
            ->where('status_cuti', 'disetujui')
            ->get();

        // List opsi pegawai aktif untuk kebutuhan modal form
        $pegawaiList = Pegawai::with(['jabatan'])->where('is_active', true)->orderBy('nama', 'asc')
            ->whereDoesntHave('user', function ($q) {
                $q->role('superadmin');
            })
            ->get();

        return Inertia::render('admin/cuti/index', [
            'cutis'       => $query->paginate(10)->withQueryString(),
            'pegawaiList' => $pegawaiList,
            'allApprovedCutis' => $allApprovedCutis,
            'filters'     => $filters,
        ]);
    }

    /**
     * Memproses penyimpanan berkas pengajuan cuti baru.
     *
     * @param StoreCutiRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCutiRequest $request)
    {
        try {
            $this->cutiService->storeCuti($request->validated());

            return redirect()->route('cuti.index')->with('success', [
                'id' => uniqid('success_', true),
                'text' => 'Berkas pengajuan cuti baru berhasil dikirim dan terdaftar.'
            ]);
        } catch (Exception $e) {
            // Mencatat detail error ke storage/logs/laravel.log
            Log::error('Gagal memproses storeCuti pada CutiController: ' . $e->getMessage(), [
                'input' => $request->validated(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id' => uniqid('error_', true),
                'text' => 'Gagal memproses pengajuan cuti. Terjadi kesalahan internal pada server database.'
            ]);
        }
    }

    /**
     * Memperbarui informasi berkas cuti (Hanya berlaku untuk dokumen berstatus 'menunggu').
     *
     * @param UpdateCutiRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCutiRequest $request, $id)
    {
        try {
            $this->cutiService->updateCuti((int) $id, $request->validated());

            return redirect()->back()->with('success', [
                'id' => uniqid('success_', true),
                'text' => 'Data isi berkas permohonan cuti berhasil diperbarui.'
            ]);
        } catch (Exception $e) {
            // Mencatat detail error ke storage/logs/laravel.log
            Log::error("Gagal memperbarui data cuti ID {$id} pada CutiController: " . $e->getMessage(), [
                'id'    => $id,
                'input' => $request->validated(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id' => uniqid('error_', true),
                'text' => 'Gagal memperbarui data cuti. Terjadi kesalahan sistem server internal.'
            ]);
        }
    }

    /**
     * Memperbarui sirkulasi status persetujuan berkas cuti sekaligus mengalkulasi jatah kuota tahunan.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_cuti' => 'required|in:disetujui,ditangguhkan'
        ]);

        try {
            $newStatus = $request->status_cuti;
            $this->cutiService->updateStatusCuti((int) $id, $newStatus);

            $label = $newStatus === 'disetujui' ? 'Disetujui' : 'Ditangguhkan';
            return redirect()->back()->with('success', [
                'id' => uniqid('success_', true),
                'text' => "Status pengajuan cuti pegawai berhasil diubah menjadi {$label}."
            ]);
        } catch (ValidationException $ve) {
            // Catatan: ValidationException tidak perlu di-log karena merupakan bad input yang wajar dari user
            return redirect()->back()->withErrors($ve->errors());
        } catch (Exception $e) {
            // Mencatat detail error ke storage/logs/laravel.log jika terjadi kegagalan kalkulasi database/sistem
            Log::error("Gagal mengubah status dokumen cuti ID {$id} pada CutiController: " . $e->getMessage(), [
                'id'         => $id,
                'target_status' => $request->status_cuti,
                'trace'      => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id' => uniqid('error_', true),
                'text' => 'Gagal mengubah status dokumen. Terjadi kesalahan sistem pemrosesan kuota.'
            ]);
        }
    }

    /**
     * Menghapus record berkas pengajuan cuti pegawai dari sistem.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $this->cutiService->deleteCuti((int) $id);

            return redirect()->back()->with('success', [
                'id' => uniqid('success_', true),
                'text' => 'Data dokumen permohonan cuti berhasil dihapus permanen dari sistem.'
            ]);
        } catch (Exception $e) {
            // Mencatat detail error ke storage/logs/laravel.log
            Log::error("Gagal menghapus berkas data cuti ID {$id} pada CutiController: " . $e->getMessage(), [
                'id'    => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', [
                'id' => uniqid('error_', true),
                'text' => 'Gagal menghapus berkas data cuti. Terjadi kesalahan sistem penyimpanan server.'
            ]);
        }
    }

    public function cetakDokumen($id)
    {
        try {
            // Generate file melalui service
            $filePath = $this->cutiCetakService->generateDocx((int) $id);

            // Return file sebagai unduhan otomatis langsung ke browser, lalu hapus file setelah dikirim
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', [
                'id' => uniqid('error_', true),
                'text' => 'Gagal mengunduh cetakan berkas. ' . $e->getMessage()
            ]);
        }
    }
}
