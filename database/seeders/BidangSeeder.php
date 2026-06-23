<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        $bidang = [
            ['nama_bidang' => 'Sekretariat', 'akronim' => 'SEKRETARIAT'],
            ['nama_bidang' => 'Bidang Pemberdayaan Perempuan', 'akronim' => 'PP'],
            ['nama_bidang' => 'Bidang Perlindungan Anak', 'akronim' => 'PA'],
            ['nama_bidang' => 'Bidang Kependudukan dan Keluarga Berencana', 'akronim' => 'KKB'],
            ['nama_bidang' => 'Bidang Pengarusutamaan Gender', 'akronim' => 'PUG'],
            ['nama_bidang' => 'Unit Pelaksana Teknis Daerah Perlindungan Perempuan dan Anak', 'akronim' => 'UPTD PPA'],
        ];

        foreach ($bidang as $b) {
            DB::table('bidang')->insert([
                'nama_bidang' => $b['nama_bidang'],
                'akronim' => $b['akronim'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
