<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\User;
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

        Pegawai::factory(10)->has(User::factory()->count(1))->create();
        User::factory(['email' => 'fadilprayadika@gmail.com', 'name' => 'Fadil Prayadika'])->isAdmin()->create();
    }
}
