<?php

namespace App\Http\Controllers;

use App\Services\PegawaiPasswordService;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Exception;

class PegawaiPasswordController extends Controller
{
    protected $passwordService;

    public function __construct(PegawaiPasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }

    /**
     * Menampilkan halaman khusus daftar pegawai untuk manajemen kata sandi.
     */
    public function index()
    {
        $currentUser = auth()->user();

        if (!$currentUser->hasAnyRole(['admin', 'superadmin'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola kata sandi.');
        }

        $query = Pegawai::whereNotNull('user_id');

        if ($currentUser->hasRole('superadmin')) {
            $query->whereHas('user', function ($q) {
                $q->whereHas('roles', function ($roleQuery) {
                    $roleQuery->whereIn('name', ['pegawai', 'admin']);
                });
            });
        } elseif ($currentUser->hasRole('admin')) {
            $query->whereHas('user', function ($q) {
                $q->whereHas('roles', function ($roleQuery) {
                    $roleQuery->where('name', 'pegawai');
                });
            });
        }

        $pegawaiList = $query->with(['user:id,email', 'user.roles:id,name'])
            ->orderBy('nama', 'asc')
            ->get(['id', 'nama', 'nip', 'user_id']);

        return Inertia::render('admin/reset-password/index', [
            'pegawaiList' => $pegawaiList
        ]);
    }

    /**
     * Memproses pengacakan kata sandi pegawai terpilih.
     */
    public function reset(Request $request, $id)
    {
        try {
            // Eksekusi business logic dan dapatkan sandi baru berbentuk plain-text
            $plainPassword = $this->passwordService->resetPasswordRandom($id, auth()->user());

            // Kirim balik plain password via session flash Inertia ke modal
            return redirect()->back()->with('password_reset_success', [
                'id'       => uniqid('pwd_', true),
                'password' => $plainPassword,
            ]);
        } catch (Exception $e) {
            Log::error("Gagal melakukan reset sandi pada Pegawai ID {$id}: " . $e->getMessage());

            return redirect()->back()->with('error', [
                'id'   => uniqid('error_', true),
                'text' => $e->getMessage() ?: 'Terjadi kesalahan sistem saat mereset kata sandi.'
            ]);
        }
    }
}
