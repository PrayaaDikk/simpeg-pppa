<?php

namespace App\Services;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class AdminDelegationService
{
    public function delegateRoleToPegawai(array $data, int $currentSuperadminId)
    {
        $pegawaiPenerima = Pegawai::with('user')->findOrFail($data['pegawai_id']);

        if (!$pegawaiPenerima->user_id || !$pegawaiPenerima->user) {
            throw new Exception('Pegawai terpilih belum memiliki akun user yang aktif.');
        }

        // Validasi Password Sudo Superadmin
        $superadmin = User::findOrFail($currentSuperadminId);
        if (!Hash::check($data['password_konfirmasi'], $superadmin->password)) {
            throw new Exception('Konfirmasi gagal. Password Superadmin yang Anda masukkan salah.');
        }

        if ($pegawaiPenerima->user->hasRole('admin') || $pegawaiPenerima->user->hasRole('superadmin')) {
            throw new Exception('Pegawai tersebut sudah memiliki hak akses administratif.');
        }

        // Eksekusi perpindahan role via Spatie
        DB::transaction(function () use ($pegawaiPenerima) {
            $pegawaiPenerima->user->assignRole('admin');
            $pegawaiPenerima->user->removeRole('pegawai');
        });

        return $pegawaiPenerima;
    }

    public function revokeAdminRole(int $pegawaiId)
    {
        $pegawai = Pegawai::with('user')->findOrFail($pegawaiId);

        if (!$pegawai->user_id || !$pegawai->user) {
            throw new Exception('Akun pengguna terkait tidak ditemukan.');
        }

        // Cek apakah user benar-benar memiliki role admin saat ini
        if (!$pegawai->user->hasRole('admin')) {
            throw new Exception('Pegawai ini tidak memiliki hak akses Administrator untuk dicabut.');
        }

        DB::transaction(function () use ($pegawai) {
            // Hapus wewenang admin dan balikkan ke role pegawai
            $pegawai->user->removeRole('admin');
            $pegawai->user->assignRole('pegawai');
        });

        return $pegawai;
    }
}
