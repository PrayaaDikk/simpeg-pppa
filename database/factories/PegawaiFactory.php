<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Pangkat;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pegawai>
 */
class PegawaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jk = $this->faker->randomElement(['l', 'p']);
        $namaDepan = $jk === 'l' ? $this->faker->firstNameMale() : $this->faker->firstNameFemale();
        $namaLengkap = $namaDepan . ' ' . $this->faker->lastName() . ', S.T.';

        return [
            'user_id' => User::factory(),
            'bidang_id' => Bidang::inRandomOrder()->first()->id ?? Bidang::factory(),
            'jabatan_id' => Jabatan::inRandomOrder()->first()->id ?? Jabatan::factory(),
            'nip' => $this->faker->numerify('199#####202012####'),
            'nama' => $namaLengkap,
            'karpeg' => 'K.' . $this->faker->numerify('######'),
            'jenis_kelamin' => $jk,
            'agama' => $this->faker->randomElement(['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu']),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '1998-01-01'),
            'no_telp' => '08' . $this->faker->numerify('##########'),
            'kode_pos' => $this->faker->postcode(),
            'alamat' => $this->faker->address(),
            'status_kawin' => $this->faker->randomElement(['belum kawin', 'kawin', 'cerai hidup', 'cerai mati']),
            'nama_pasangan' => $this->faker->name(),
            'status_kerja_pasangan' => 'Swasta',
            'jumlah_anak' => $this->faker->numberBetween(0, 3),
            'jenis_pegawai' => $this->faker->randomElement(['pns', 'cpns', 'pppk']),
            'pangkat_id' => Pangkat::inRandomOrder()->first()->id ?? Pangkat::factory(),
            'tmt_pegawai' => $this->faker->date('Y-m-d', '2021-01-01'),
            'jatah_cuti_dua_tahun_lalu' => $this->faker->numberBetween(0, 6),
            'jatah_cuti_satu_tahun_lalu' => $this->faker->numberBetween(0, 6),
            'jatah_cuti_tahun_ini' => $this->faker->numberBetween(0, 12),
            'is_active' => true,
        ];
    }
}
