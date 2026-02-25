<?php

namespace Database\Factories;

use App\Models\Pegawai;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cuti>
 */
class CutiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalMulai = $this->faker->dateTimeBetween('-1 year', '+1 month');
        $lama = $this->faker->numberBetween(1, 14);

        return [
            'pegawai_id' => Pegawai::factory(),

            'jenis_cuti' => $this->faker->randomElement([
                'Tahunan',
                'Besar',
                'Sakit',
                'Melahirkan',
                'Alasan Penting',
                'Di Luar Tanggungan Negara'
            ]),

            'alasan_cuti' => $this->faker->sentence(8),

            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => Carbon::parse($tanggalMulai)->addDays($lama),

            'lama_cuti' => $lama,

            'alamat_cuti' => $this->faker->address(),
            'no_telp' => $this->faker->phoneNumber(),
            'catatan_cuti' => $this->faker->sentence(),

            'status_cuti' => $this->faker->randomElement([
                'Menunggu',
                'Disetujui',
                'Perubahan',
                'Ditangguhkan',
                'Ditolak'
            ]),

            'keputusan_atasan' => $this->faker->randomElement([
                'Disetujui',
                'Perubahan',
                'Ditangguhkan',
                'Tidak Disetujui'
            ]),

            'diajukan_oleh' => User::factory(),
            'disetujui_oleh' => User::factory(),
        ];
    }
}
