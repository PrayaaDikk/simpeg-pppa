<?php

namespace Database\Factories;

use App\Models\Pegawai;
use App\Models\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RiwayatPendidikanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tingkat = $this->faker->randomElement(['SMA', 'D3', 'D4', 'S1', 'S2', 'S3']);

        return [
            'pegawai_id' => Pegawai::factory(),
            'tingkat' => $tingkat,
            'level_pendidikan' => RiwayatPendidikan::$mapLevel[$tingkat],
            'jurusan' => fake()->randomElement(['Informatika', 'Akuntansi', 'Hukum']),
            'institusi' => fake()->company() . ' University',
            'tahun_lulus' => fake()->year(),
        ];
    }
}
