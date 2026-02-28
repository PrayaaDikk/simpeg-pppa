<?php

namespace Database\Seeders;

use App\Models\Cuti;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'pegawai_id' => 1,
                'jenis_cuti' => 'Tahunan',
                'n' => 12,
                'n_1' => 6,
                'n_2' => 6,
                'tanggal_mulai' => '2026-3-1',
                'tanggal_selesai' => '2026-3-6',
                'lama_cuti' => 6,
                'alamat_cuti' => 'Jl. Raya No. 1',
                'no_telp' => '08123456789',
                'status_cuti' => 'Menunggu',
                'keputusan_atasan' => 'Menunggu',
                'diajukan_oleh' => 1,
            ],
            [
                'pegawai_id' => 2,
                'jenis_cuti' => 'Tahunan',
                'n' => 12,
                'n_1' => 6,
                'n_2' => 6,
                'tanggal_mulai' => '2026-3-7',
                'tanggal_selesai' => '2026-3-10',
                'lama_cuti' => 6,
                'alamat_cuti' => 'Jl. Raya No. 1',
                'no_telp' => '08123456789',
                'status_cuti' => 'Disetujui',
                'keputusan_atasan' => 'Disetujui',
                'diajukan_oleh' => 2,
            ],
        ];

        foreach ($data as $cuti) {
            Cuti::create($cuti);
        }
    }
}
