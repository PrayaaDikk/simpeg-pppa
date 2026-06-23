<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pegawai;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kgb>
 */
class KgbFactory extends Factory
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
            'tmt_kgb' => $this->faker->date(),
            'nomor_sk' => 'SK-KGB/' . $this->faker->year() . '/' . $this->faker->numerify('#####'),
            'tanggal_sk' => $this->faker->date(),
        ];
    }
}
