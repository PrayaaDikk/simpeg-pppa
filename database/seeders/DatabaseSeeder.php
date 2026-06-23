<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PangkatSeeder::class,
            BidangSeeder::class,
            JabatanSeeder::class,
            PegawaiSeeder::class,
            // KgbSeeder::class,
            // CutiSeeder::class,
        ]);
    }
}
