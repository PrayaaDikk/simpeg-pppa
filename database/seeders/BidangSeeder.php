<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_bidang' => 'Sekretariat'],
            ['nama_bidang' => 'Perlindungan Hak Perempuan'],
            ['nama_bidang' => 'Perlindungan Kualitas Anak'],
            ['nama_bidang' => 'Pemberdayaan Perempuan'],
            ['nama_bidang' => 'Kualitas Hidup Perempuan'],
            ['nama_bidang' => 'Unit Pelaksana Teknis Daerah Perlindungan Perempuan dan Anak'],
        ];

        foreach ($data as $item) {
            Bidang::create($item);
        }
    }
}
