<?php

namespace App\Services;

use App\Models\Pegawai;
use App\Models\User;
use App\Models\RiwayatJabatan;
use App\Models\RiwayatPangkat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PegawaiService
{
    /**
     * Mengambil daftar pegawai yang telah difilter secara dinamis.
     */
    public function getFilteredPegawais(array $filters, int $perPage = 10)
    {
        $query = Pegawai::with(['bidang', 'jabatan', 'pangkat', 'user', 'pendidikan'])
            ->whereDoesntHave('user', function ($q) {
                $q->role('admin');
            });

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nip', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['bidang_ids'])) {
            $query->whereIn('bidang_id', $filters['bidang_ids']);
        }

        if (!empty($filters['jabatan_ids'])) {
            $query->whereIn('jabatan_id', $filters['jabatan_ids']);
        }

        if (!empty($filters['pangkat_ids'])) {
            $query->whereIn('pangkat_id', $filters['pangkat_ids']);
        }

        if (!empty($filters['pendidikans'])) {
            $query->whereHas('pendidikan', function ($q) use ($filters) {
                $q->whereIn('tingkat', $filters['pendidikans']);
            });
        }

        if (!empty($filters['statuses'])) {
            $query->whereIn('is_active', $filters['statuses']);
        }

        return $query->orderBy('nama', 'asc')->paginate($perPage)->withQueryString();
    }

    /**
     * Proses pembuatan akun user dan profil pegawai baru dalam satu database transaction.
     */
    public function storePegawai(array $data): Pegawai
    {
        return DB::transaction(function () use ($data) {
            // 1. Create User Account
            $user = User::create([
                'name' => $data['nama'],
                'email' => $data['email'] ? strtolower($data['email']) : $data['nip'] . '@simpeg.com',
                'password' => $data['password'] ? Hash::make($data['password']) : Hash::make('p3a' . $data['nip']),
                'role' => 'pegawai', // Default role sistem
            ]);

            // 2. Create Profil Pegawai & Default Kuota Cuti
            $pegawai = Pegawai::create([
                'user_id' => $user->id,
                'nip' => $data['nip'],
                'karpeg' => $data['karpeg'] ?? null,
                'nama' => $data['nama'],

                // Penyesuaian nama field dari validasi 'no_telp' ke kolom database 'telepon'
                'no_telp' => $data['no_telp'] ?? null,

                // Menyimpan data personal yang sebelumnya tertinggal
                'jenis_kelamin' => $data['jenis_kelamin'],
                'agama' => $data['agama'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'kode_pos' => $data['kode_pos'],
                'alamat' => $data['alamat'],
                'status_kawin' => $data['status_kawin'],
                'nama_pasangan' => $data['nama_pasangan'] ?? null,
                'status_kerja_pasangan' => $data['status_kerja_pasangan'] ?? null,
                'jumlah_anak' => $data['jumlah_anak'] ?? 0,
                'jenis_pegawai' => $data['jenis_pegawai'],
                'is_active' => $data['is_active'] ?? true,

                // Menyimpan data penempatan & jatah cuti
                'tmt_pegawai' => $data['tmt_pegawai'],
                'bidang_id' => $data['bidang_id'] ?? null,
                'jabatan_id' => $data['jabatan_id'] ?? null,
                'pangkat_id' => $data['pangkat_id'],
                'jatah_cuti_dua_tahun_lalu' => $data['jatah_cuti_dua_tahun_lalu'],
                'jatah_cuti_satu_tahun_lalu' => $data['jatah_cuti_satu_tahun_lalu'],
                'jatah_cuti_tahun_ini' => $data['jatah_cuti_tahun_ini'],
            ]);

            return $pegawai;
        });
    }

    /**
     * Proses pembaruan data komprehensif pegawai.
     */
    public function updatePegawai(int $id, array $data): Pegawai
    {
        $pegawai = Pegawai::findOrFail($id);

        DB::transaction(function () use ($pegawai, $data) {
            // Update Data User Login terkait
            $userData = [
                'name' => $data['nama'],
                'email' => $data['email']
            ];
            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }
            $pegawai->user->update($userData);

            // Update Profil Master Pegawai
            $pegawai->update($data);
        });

        return $pegawai;
    }

    /**
     * Proses hapus pegawai beserta akun usernya secara cascade aman.
     */
    public function deletePegawai(int $id): void
    {
        $pegawai = Pegawai::findOrFail($id);

        DB::transaction(function () use ($pegawai) {
            if ($pegawai->user) {
                $pegawai->user->delete();
            }
            $pegawai->delete();
        });
    }
}
