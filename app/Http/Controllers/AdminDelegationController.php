<?php

namespace App\Http\Controllers;

use App\Services\AdminDelegationService;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Exception;

class AdminDelegationController extends Controller
{
    protected $delegationService;

    public function __construct(AdminDelegationService $delegationService)
    {
        $this->delegationService = $delegationService;
    }

    public function index()
    {
        if (!auth()->user()->hasRole('superadmin')) {
            abort(403, 'Hanya Akun Superadmin Utama yang dapat mengakses halaman delegasi.');
        }

        // Ambil daftar pegawai yang memegang role admin saat ini
        $adminList = Pegawai::whereNotNull('user_id')
            ->whereHas('user', function ($query) {
                $query->role('admin');
            })
            ->with('user:id,email')
            ->get(['id', 'nama', 'nip', 'user_id']);

        // Ambil daftar pegawai biasa untuk opsi select transfer
        $pegawaiList = Pegawai::whereNotNull('user_id')
            ->whereHas('user', function ($query) {
                $query->role('pegawai');
            })
            ->get(['id', 'nama', 'nip']);

        return Inertia::render('admin/delegasi/index', [
            'adminList'   => $adminList,
            'pegawaiList' => $pegawaiList
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id'          => 'required|exists:pegawai,id',
            'password_konfirmasi' => 'required|string',
        ], [
            'pegawai_id.required'          => 'Silakan pilih pegawai terlebih dahulu.',
            'password_konfirmasi.required' => 'Password konfirmasi superadmin wajib diisi.'
        ]);

        try {
            $this->delegationService->delegateRoleToPegawai($validated, auth()->id());

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Hak akses Admin berhasil dialihkan kepada pegawai terpilih.'
            ]);
        } catch (Exception $e) {
            Log::error("Sistem Error pada AdminDelegationController@store: " . $e->getMessage());

            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => $e->getMessage() ?: 'Terjadi kesalahan sistem saat memproses hak akses admin.'
            ]);
        }
    }

    public function revoke(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
        ]);

        try {
            $this->delegationService->revokeAdminRole($validated['pegawai_id']);

            return redirect()->back()->with('success', [
                'id'   => uniqid('success_', true),
                'text' => 'Hak akses administratif berhasil dicabut dan dikembalikan ke role pegawai biasa.'
            ]);
        } catch (Exception $e) {
            Log::error("Error pada AdminDelegationController@revoke: " . $e->getMessage());
            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => $e->getMessage() ?: 'Gagal mengembalikan role akun pegawai.'
            ]);
        }
    }
}
