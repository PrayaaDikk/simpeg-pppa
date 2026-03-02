<?php

namespace Database\Factories;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $skTanggal = $this->faker->date();
        $tglMulaiBerlaku = $this->faker->dateTimeBetween('now', '+1 year');

        return [
            'pegawai_id' => Pegawai::factory(),
            'nomor_surat' => $this->faker->unique()->bothify('###/KGB/2024'),
            'sk_tanggal' => $skTanggal,
            'sk_nomor' => $this->faker->bothify('SK-####-????'),

            // Gaji & Masa Kerja Lama
            'gaji_pokok_lama' => $this->faker->randomFloat(2, 3000000, 4000000),
            'tgl_mulai_gaji_lama' => $this->faker->date(),
            'masa_kerja_tahun_lama' => $this->faker->numberBetween(1, 10),
            'masa_kerja_bulan_lama' => $this->faker->numberBetween(0, 11),

            // Gaji & Masa Kerja Baru
            'gaji_pokok_baru' => $this->faker->randomFloat(2, 4000001, 5000000),
            'gol_ruang_baru' => $this->faker->randomElement(['III/a', 'III/b', 'III/c', 'III/d']),
            'masa_kerja_tahun_baru' => $this->faker->numberBetween(11, 20),
            'masa_kerja_bulan_baru' => $this->faker->numberBetween(0, 11),
            'tgl_mulai_berlaku' => $tglMulaiBerlaku,

            // Estimasi Mendatang (biasanya +2 tahun dari tgl_mulai_berlaku)
            'tgl_kgb_mendatang' => \Carbon\Carbon::parse($tglMulaiBerlaku)->addYears(2),

            'status_pegawai' => $this->faker->randomElement(['PNS', 'PPPK']),
            'file_sk' => 'sk_kgb_example.pdf',
            'status_kgb' => $this->faker->randomElement(['Menunggu', 'Disetujui', 'Ditolak']),

            'diajukan_oleh' => Pegawai::factory(),
            'disetujui_oleh' => null, // Default null untuk status 'Menunggu'
        ];
    }
}
