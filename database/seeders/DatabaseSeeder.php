<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Database\Seeder;
use Database\Seeders\BidangSeeder;
use Database\Seeders\PangkatSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            BidangSeeder::class,
            PangkatSeeder::class,
        ]);

        Pegawai::factory(120)->create();
    }
}
