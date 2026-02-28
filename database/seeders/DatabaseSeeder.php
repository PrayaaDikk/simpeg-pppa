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
            JabatanSeeder::class,
        ]);


        Pegawai::factory(27)->has(User::factory()->count(1))->create();
        Pegawai::factory(2)->has(User::factory()->count(1))->not_active()->keterangan()->create();
        User::factory(['email' => 'fadilprayadika@gmail.com'])->isAdmin()->create();

        $this->call(CutiSeeder::class);
    }
}
