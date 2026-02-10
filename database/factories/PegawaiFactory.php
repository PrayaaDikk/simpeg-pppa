<?php

namespace Database\Factories;

use App\Models\Bidang;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class PegawaiFactory extends Factory
{
    protected $model = Pegawai::class;

    public function definition(): array
    {
        // PAKSA tanggal lahir pasti di masa lalu
        $tglLahir = Carbon::instance(
            $this->faker->dateTimeBetween('-58 years', '-22 years')
        );

        // HITUNG USIA DENGAN ARAH BENAR
        $usia = $tglLahir->diffInYears(now());

        return [
            'bidang_id' => \App\Models\Bidang::inRandomOrder()->value('id') ?? 1,

            'nip' => $this->faker->unique()->numerify('19##############'),
            'karpeg' => $this->faker->optional()->numerify('##########'),

            'nama' => $this->faker->name(),
            'jns_kelamin' => $this->faker->randomElement(['L', 'P']),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha']),

            'tgl_lahir' => $tglLahir->format('Y-m-d'),
            'usia' => $usia,

            'tpt_lahir' => substr($this->faker->city(), 0, 20),
            'pendidikan' => $this->faker->randomElement(['SMA', 'D3', 'S1', 'S2']),
            'telp' => substr($this->faker->numerify('08############'), 0, 15),
            'kode_pos' => $this->faker->numerify('#####'),
            'alamat' => substr($this->faker->address(), 0, 50),

            'status_kawin' => $this->faker->randomElement(['Belum Kawin', 'Kawin', 'Cerai']),
            'suami_istri' => null,
            'sta_kerja_suami_istri' => null,
            'jumlah_anak' => $this->faker->numberBetween(0, 4),

            'jns_karyawan' => $this->faker->randomElement(['PNS', 'PPPK']),
            'jabatan' => substr($this->faker->jobTitle(), 0, 45),
            'gol_ruang' => $this->faker->randomElement(['II/a', 'III/a', 'III/b', 'IV/a']),
            'pangkat' => $this->faker->randomElement(['Pengatur', 'Penata', 'Pembina']),

            'tmt_pangkat' => $this->faker->date(),
            'masa_kerja_thn' => $this->faker->numberBetween(1, 30),
            'masa_kerja_bln' => $this->faker->numberBetween(0, 11),

            'foto' => null,
        ];
    }
}
