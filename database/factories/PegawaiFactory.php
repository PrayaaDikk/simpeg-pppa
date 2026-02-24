<?php

namespace Database\Factories;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class PegawaiFactory extends Factory
{
    protected $model = Pegawai::class;

    public function definition(): array
    {
        // 1. Tentukan jenis kelamin & tanggal lahir dulu buat dasar NIP
        $gender = $this->faker->randomElement(['L', 'P']);
        $tglLahir = $this->faker->date('Y-m-d', '2005-12-31'); // Maksimal kelahiran 2005
        $tglLahirClean = str_replace('-', '', $tglLahir);

        // 2. Simulasi NIP (TglLahir + ThnAngkat + BlnAngkat + JnsKelamin + NoUrut)
        $nip = $tglLahirClean . $this->faker->year() . '03' . ($gender == 'L' ? '1' : '2') . $this->faker->numerify('###');

        return [
            'bidang_id' => $this->faker->numberBetween(1, 5), // Asumsi ada 5 bidang
            'nip' => $nip,
            'karpeg' => $this->faker->bothify('?? ######'), // Contoh: AB 123456

            'nama' => $this->faker->name($gender == 'L' ? 'male' : 'female'),
            'jns_kelamin' => $gender,
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),

            'tgl_lahir' => $tglLahir,
            // Usia dihitung otomatis pakai Carbon dari tgl_lahir
            'usia' => Carbon::parse($tglLahir)->diffInYears(now()),

            'tpt_lahir' => 'Kota Kendari', // Atau $this->faker->city()
            'telp' => '08123456789',
            'kode_pos' => '93121',
            'alamat' => $this->faker->address(),

            'status_kawin' => $this->faker->randomElement(['Kawin', 'Belum Kawin', 'Cerai']),
            'suami_istri' => $gender == 'L' ? $this->faker->name('female') : $this->faker->name('male'),
            'sta_kerja_suami_istri' => $this->faker->randomElement(['Kerja', 'Tidak Kerja']),
            'jumlah_anak' => $this->faker->numberBetween(0, 4),

            'jns_karyawan' => $this->faker->randomElement(['PNS', 'PPPK', 'CPNS']),

            'jabatan' => $this->faker->jobTitle(),
            'gol_ruang' => $this->faker->randomElement(['III/a', 'III/b', 'IV/a']),
            'pangkat' => $this->faker->randomElement(['Penata Muda', 'Penata', 'Pembina']),
            'tmt_pangkat' => $this->faker->date(),
            'masa_kerja_thn' => $this->faker->numberBetween(1, 30),
            'masa_kerja_bln' => $this->faker->numberBetween(1, 11),
            'foto' => null,
        ];
    }
}
