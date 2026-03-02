<?php

namespace Database\Seeders;

use App\Models\Cuti;
use App\Models\Kgb;
use App\Models\Pegawai;
use App\Models\User;
use Database\Seeders\BidangSeeder;
use Database\Seeders\CutiSeeder;
use Database\Seeders\JabatanSeeder;
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

        // 1. Buat 27 Pegawai Aktif (beserta User dan 2 KGB)
        $pegawai = Pegawai::factory(27)
            ->has(User::factory())
            ->has(Kgb::factory()->count(2), 'kgb')
            ->has(Cuti::factory()->count(5), 'cuti')
            ->create();

        // 2. Buat 2 Pegawai Tidak Aktif
        Pegawai::factory(2)->not_active()->keterangan()->create();

        // 3. Update salah satu User menjadi Admin (Jangan buat baru dengan factory)
        // Ambil user pertama dari pegawai yang baru dibuat untuk dijadikan admin
        $roleAdmin = $pegawai->first()->user;
        $roleUser = $pegawai->last()->user;
        $roleAdmin->update([
            'email' => 'fadilprayadika@gmail.com',
            'role' => 'admin' // Sesuaikan dengan state isAdmin Anda
        ]);
        $roleUser->update([
            'email' => 'fadilprayadika17@gmail.com',
            'role' => 'user' // Sesuaikan dengan state isAdmin Anda
        ]);

        // 4. Jika ingin membuat pegawai dengan jabatan spesifik tanpa menambah total record:
        // Sebaiknya tentukan jabatan_id di dalam loop factory 27 tadi, 
        // atau biarkan factory yang mengaturnya secara acak.

        Pegawai::factory(3)
            ->has(User::factory())
            ->has(Kgb::factory()->count(2), 'kgb')
            ->sequence(
                ['jabatan_id' => 1],
                ['jabatan_id' => 2],
                ['jabatan_id' => 3],
            )
            ->create();
    }
}
