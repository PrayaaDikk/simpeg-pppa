<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_jabatan' => 'Kepala Dinas', 'is_singleton' => true],
            ['nama_jabatan' => 'Sekretaris Dinas', 'is_singleton' => true],

            // Jabatan di Bidang Sekretariat
            ['nama_jabatan' => 'Kasubag Umum dan Kepegawaian', 'bidang_id' => 1, 'is_singleton' => true],
            ['nama_jabatan' => 'Kasubag Perencanaan', 'bidang_id' => 1, 'is_singleton' => true],
            ['nama_jabatan' => 'Kasubag Keuangan', 'bidang_id' => 1, 'is_singleton' => true],

            // Jabatan Kepala Bidang (Struktural)
            ['nama_jabatan' => 'Kepala Bidang Perlindungan Hak Perempuan', 'bidang_id' => 2, 'is_singleton' => true],
            ['nama_jabatan' => 'Kepala Bidang Perlindungan Kualitas Anak', 'bidang_id' => 3, 'is_singleton' => true],
            ['nama_jabatan' => 'Kepala Bidang Pemberdayaan Perempuan', 'bidang_id' => 4, 'is_singleton' => true],
            ['nama_jabatan' => 'Kepala Bidang Kualitas Hidup Perempuan', 'bidang_id' => 5, 'is_singleton' => true],
            ['nama_jabatan' => 'Kepala UPTD PPA', 'bidang_id' => 6, 'is_singleton' => true],

            // Jabatan Fungsional & Pelaksana (Sesuai Penempatan Bidang)
            ['nama_jabatan' => 'Analis Kebijakan Ahli Muda', 'bidang_id' => 4, 'is_singleton' => false], // Di PP
            ['nama_jabatan' => 'Penyuluh Pemberdayaan Masyarakat', 'bidang_id' => 4, 'is_singleton' => false], // Di PP
            ['nama_jabatan' => 'Psikolog Klinis', 'bidang_id' => 6, 'is_singleton' => false], // Di UPTD
            ['nama_jabatan' => 'Pekerja Sosial', 'bidang_id' => 6, 'is_singleton' => false], // Di UPTD
            ['nama_jabatan' => 'Pengolah Data Gender dan Anak', 'bidang_id' => 5, 'is_singleton' => false], // Di KHP
            ['nama_jabatan' => 'Pengadministrasi Umum', 'bidang_id' => 1, 'is_singleton' => false], // Di Sekretariat
        ];

        foreach ($data as $item) {
            Jabatan::create($item);
        }
    }
}
