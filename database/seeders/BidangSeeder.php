<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_bidang' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak', 'akronim' => 'DP3A'],
            ['nama_bidang' => 'Sekretariat', 'akronim' => 'Sekretariat'],
            ['nama_bidang' => 'Perlindungan Hak Perempuan', 'akronim' => 'PHPP'],
            ['nama_bidang' => 'Perlindungan Kualitas Anak', 'akronim' => 'PKA'],
            ['nama_bidang' => 'Pemberdayaan Perempuan', 'akronim' => 'PP'],
            ['nama_bidang' => 'Kualitas Hidup Perempuan', 'akronim' => 'KHP'],
            ['nama_bidang' => 'Unit Pelaksana Teknis Daerah Perlindungan Perempuan dan Anak', 'akronim' => 'UPTD PPPA'],
        ];

        foreach ($data as $item) {
            Bidang::create($item);
        }
    }
}
