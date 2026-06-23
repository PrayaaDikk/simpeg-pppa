<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\RiwayatPendidikan;
use App\Models\RiwayatPangkat;
use App\Models\RiwayatJabatan;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $pegawaiRole = Role::firstOrCreate(['name' => 'pegawai', 'guard_name' => 'web']);

        // 1. CREATE SUPERADMIN
        $superAdminUser = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
        ]);
        $superAdminUser->assignRole($superadminRole);

        $superadminPegawai = Pegawai::factory()->create([
            'nama' => 'Super Admin, S.Kom., M.Cs.',
            'user_id' => $superAdminUser->id,
        ]);

        // 2. CREATE ADMIN
        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);
        $adminUser->assignRole($adminRole);

        $adminPegawai = Pegawai::factory()->create([
            'nama' => 'Fadil Prayadika, S.T., M.T.',
            'user_id' => $adminUser->id,
        ]);
        $this->createRiwayat($adminPegawai);

        // 2. CREATE 49 PEGAWAI
        $pegawais = Pegawai::factory(49)->create();

        foreach ($pegawais as $pegawai) {
            $pegawai->user->assignRole($pegawaiRole);
            $this->createRiwayat($pegawai);
        }
    }

    private function createRiwayat(Pegawai $pegawai): void
    {
        RiwayatPendidikan::factory(3)->create(['pegawai_id' => $pegawai->id]);
        RiwayatPangkat::factory(3)->create(['pegawai_id' => $pegawai->id]);
        RiwayatJabatan::factory(3)->create(['pegawai_id' => $pegawai->id]);
    }
}
