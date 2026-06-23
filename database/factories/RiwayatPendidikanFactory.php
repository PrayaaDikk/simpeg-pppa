<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pegawai;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RiwayatPendidikan>
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
        $tingkatOptions = ['SMA', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'];
        $tingkatTerpilih = $this->faker->randomElement($tingkatOptions);

        $tingkatPendidikan = array_search($tingkatTerpilih, $tingkatOptions) + 1;

        return [
            'pegawai_id' => Pegawai::factory(),
            'tingkat' => $tingkatTerpilih,
            'jurusan' => $this->faker->word(),
            'institusi' => $this->faker->company(),
            'tahun_lulus' => $this->faker->year(),
            'tingkat_pendidikan' => $tingkatPendidikan,
        ];
    }
}
