<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pegawai;

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
        return [
            'pegawai_id' => Pegawai::factory(),
            'jenis_cuti' => $this->faker->randomElement(['tahunan', 'besar', 'sakit', 'melahirkan', 'alasan_penting', 'di_luar_tanggungan_negara']),
            'tanggal_mulai' => $this->faker->date(),
            'tanggal_selesai' => $this->faker->date(),
            'alasan' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'disetujui', 'ditolak']),
        ];
    }
}
