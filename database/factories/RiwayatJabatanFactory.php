<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pegawai;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RiwayatJabatan>
 */
class RiwayatJabatanFactory extends Factory
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
            'nama_jabatan' => $this->faker->jobTitle(),
            'tmt_jabatan' => $this->faker->date(),
            'nomor_sk' => 'SK-JBT/' . $this->faker->year() . '/' . Str::random(5),
            'tanggal_sk' => $this->faker->date(),
        ];
    }
}
