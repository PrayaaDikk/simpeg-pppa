<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Database\Seeders\BidangSeeder;
use Database\Seeders\PangkatSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BidangSeeder::class,
            PangkatSeeder::class,
        ]);

        Pegawai::factory()->create();
    }
}
