<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pegawai;
use App\Models\Pangkat;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RiwayatPangkat>
 */
class RiwayatPangkatFactory extends Factory
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
            'pangkat_id' => Pangkat::inRandomOrder()->first()->id ?? Pangkat::factory(),
            'tmt_pangkat' => $this->faker->date(),
            'nomor_sk' => 'SK-PKT/' . $this->faker->year() . '/' . Str::random(5),
        ];
    }
}
