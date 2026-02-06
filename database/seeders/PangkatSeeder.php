<?php

namespace Database\Seeders;

use App\Models\Pangkat;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PangkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_pangkat' => 'Juru Muda', 'golongan' => 'I/a'],
            ['nama_pangkat' => 'Juru Muda Tingkat I', 'golongan' => 'I/b'],
            ['nama_pangkat' => 'Juru', 'golongan' => 'I/c'],
            ['nama_pangkat' => 'Juru Tingkat I', 'golongan' => 'I/d'],

            ['nama_pangkat' => 'Pengatur Muda', 'golongan' => 'II/a'],
            ['nama_pangkat' => 'Pengatur Muda Tingkat I', 'golongan' => 'II/b'],
            ['nama_pangkat' => 'Pengatur', 'golongan' => 'II/c'],
            ['nama_pangkat' => 'Pengatur Tingkat I', 'golongan' => 'II/d'],

            ['nama_pangkat' => 'Penata Muda', 'golongan' => 'III/a'],
            ['nama_pangkat' => 'Penata Muda Tingkat I', 'golongan' => 'III/b'],
            ['nama_pangkat' => 'Penata', 'golongan' => 'III/c'],
            ['nama_pangkat' => 'Penata Tingkat I', 'golongan' => 'III/d'],

            ['nama_pangkat' => 'Pembina', 'golongan' => 'IV/a'],
            ['nama_pangkat' => 'Pembina Tingkat I', 'golongan' => 'IV/b'],
            ['nama_pangkat' => 'Pembina Utama Muda', 'golongan' => 'IV/c'],
            ['nama_pangkat' => 'Pembina Utama Madya', 'golongan' => 'IV/d'],
            ['nama_pangkat' => 'Pembina Utama', 'golongan' => 'IV/e'],

            // ================= PPPK =================
            ['nama_pangkat' => 'PPPK Golongan I', 'golongan' => 'I'],
            ['nama_pangkat' => 'PPPK Golongan II', 'golongan' => 'II'],
            ['nama_pangkat' => 'PPPK Golongan III', 'golongan' => 'III'],
            ['nama_pangkat' => 'PPPK Golongan IV', 'golongan' => 'IV'],
            ['nama_pangkat' => 'PPPK Golongan V', 'golongan' => 'V'],
            ['nama_pangkat' => 'PPPK Golongan VI', 'golongan' => 'VI'],
            ['nama_pangkat' => 'PPPK Golongan VII', 'golongan' => 'VII'],
            ['nama_pangkat' => 'PPPK Golongan VIII', 'golongan' => 'VIII'],
            ['nama_pangkat' => 'PPPK Golongan IX', 'golongan' => 'IX'],
            ['nama_pangkat' => 'PPPK Golongan X', 'golongan' => 'X'],
            ['nama_pangkat' => 'PPPK Golongan XI', 'golongan' => 'XI'],
            ['nama_pangkat' => 'PPPK Golongan XII', 'golongan' => 'XII'],
            ['nama_pangkat' => 'PPPK Golongan XIII', 'golongan' => 'XIII'],
            ['nama_pangkat' => 'PPPK Golongan XIV', 'golongan' => 'XIV'],
            ['nama_pangkat' => 'PPPK Golongan XV', 'golongan' => 'XV'],
            ['nama_pangkat' => 'PPPK Golongan XVI', 'golongan' => 'XVI'],
            ['nama_pangkat' => 'PPPK Golongan XVII', 'golongan' => 'XVII'],
        ];

        foreach ($data as $item) {
            Pangkat::firstOrCreate($item);
        }
    }
}
