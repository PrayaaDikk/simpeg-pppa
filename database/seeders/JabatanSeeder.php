<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatan = [
            ['nama_jabatan' => 'Kepala Dinas', 'is_singleton' => true],
            ['nama_jabatan' => 'Sekretaris Dinas', 'is_singleton' => true],
            ['nama_jabatan' => 'Kepala Bidang', 'is_singleton' => false], // False karena ada beberapa Kabid
            ['nama_jabatan' => 'Kepala Sub Bagian', 'is_singleton' => false],
            ['nama_jabatan' => 'Kepala UPTD', 'is_singleton' => true],
            ['nama_jabatan' => 'Analisis Kebijakan', 'is_singleton' => false],
            ['nama_jabatan' => 'Fungsional Penggerak Swadaya Masyarakat', 'is_singleton' => false],
            ['nama_jabatan' => 'Psikolog Klinis', 'is_singleton' => false],
            ['nama_jabatan' => 'Konselor', 'is_singleton' => false],
            ['nama_jabatan' => 'Penyusun Rencana Kerja dan Anggaran', 'is_singleton' => false],
            ['nama_jabatan' => 'Pengelola Data', 'is_singleton' => false],
            ['nama_jabatan' => 'Pranata Komputer', 'is_singleton' => false],
            ['nama_jabatan' => 'Administrasi Umum', 'is_singleton' => false],
        ];

        foreach ($jabatan as $j) {
            DB::table('jabatan')->insert([
                'nama_jabatan' => $j['nama_jabatan'],
                'is_singleton' => $j['is_singleton'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
