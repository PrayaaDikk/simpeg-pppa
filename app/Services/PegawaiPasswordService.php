<?php

namespace App\Services;

use App\Models\Pegawai;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class PegawaiPasswordService
{
    /**
     * Mengubah sandi pegawai secara acak berdasarkan aturan hierarki role Spatie.
     */
    public function resetPasswordRandom(int $pegawaiId, $currentUser)
    {
        $pegawaiTarget = Pegawai::with('user')->findOrFail($pegawaiId);

        if (!$pegawaiTarget->user_id || !$pegawaiTarget->user) {
            throw new Exception('Pegawai ini belum memiliki akun user aktif di sistem.');
        }

        $targetUser = $pegawaiTarget->user;

        // Aturan Proteksi Spatie RBAC:
        // 1. Akun ber-role Admin TIDAK BOLEH mereset akun sesama Admin atau Superadmin
        if ($currentUser->hasRole('admin')) {
            if ($targetUser->hasRole('superadmin') || $targetUser->hasRole('admin')) {
                throw new Exception('Otoritas ditolak. Anda tidak diizinkan mereset sandi milik akun administratif.');
            }
        }

        // 2. Siapapun dilarang mereset akun miliknya sendiri melalui panel bypass ini
        if ($currentUser->id === $targetUser->id) {
            throw new Exception('Tindakan tidak valid. Anda tidak dapat mereset sandi Anda sendiri melalui panel ini.');
        }

        // Generasi sandi acak baru sepanjang 8 karakter
        $plainPassword = Str::random(8);

        DB::transaction(function () use ($targetUser, $plainPassword) {
            $targetUser->update([
                'password' => Hash::make($plainPassword)
            ]);
        });

        return $plainPassword;
    }
}
