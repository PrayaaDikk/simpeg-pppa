<?php

namespace Database\Factories;

use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class PegawaiFactory extends Factory
{
    protected $model = Pegawai::class;

    public function definition(): array
    {
        // 1. Tentukan jenis kelamin & tanggal lahir buat dasar NIP
        $gender = $this->faker->randomElement(['L', 'P']);
        $tglLahir = $this->faker->date('Y-m-d', '2005-12-31');
        $tglLahirClean = str_replace('-', '', $tglLahir);

        // 2. Simulasi NIP (TglLahir + ThnAngkat + BlnAngkat + JnsKelamin + NoUrut)
        $nip = $tglLahirClean . $this->faker->year() . '03' . ($gender == 'L' ? '1' : '2') . $this->faker->numerify('###');

        return [
            // Relasi ke Bidang (Asumsi sudah ada data di tabel bidang)
            'bidang_id' => $this->faker->numberBetween(1, 5),

            // buat faker numberjabatan, namun ketika jabatan nya is_singleton,
            // tidak usah di generate, karena sudah ada data di tabel jabatan
            'jabatan_id' => Jabatan::where('is_singleton', false)->inRandomOrder()->first()->id,

            // Atasan (Kosongkan dulu, nanti diatur di Seeder)
            'atasan_id' => null,

            'nip' => $nip,
            'nama' => $this->faker->name($gender == 'L' ? 'male' : 'female'),
            'karpeg' => $this->faker->bothify('?? ######'),
            'jns_kelamin' => $gender,
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
            'tgl_lahir' => $tglLahir,

            'tpt_lahir' => 'Kota Kendari',
            'telp' => $this->faker->numerify('08##########'),
            'kode_pos' => '93121',
            'alamat' => $this->faker->address(),

            'status_kawin' => $this->faker->randomElement(['Kawin', 'Belum Kawin', 'Cerai Hidup', 'Cerai Mati']),
            'suami_istri' => $gender == 'L' ? $this->faker->name('female') : $this->faker->name('male'),
            'sta_kerja_suami_istri' => $this->faker->randomElement(['Kerja', 'Tidak Kerja']),
            'jumlah_anak' => $this->faker->numberBetween(0, 4),

            'jns_karyawan' => $this->faker->randomElement(['PNS', 'PPPK', 'CPNS']),

            // TIPS: Kolom 'jabatan' (string) dihapus, pakai 'jabatan_id' di atas! 👑
            'gol_ruang' => $this->faker->randomElement(['III/a', 'III/b', 'IV/a']),
            'pangkat' => $this->faker->randomElement(['Penata Muda', 'Penata', 'Pembina']),
            'tmt_pegawai' => Carbon::now()->subYears(5),
            'kuota_cuti' => 12,
            'foto' => null,
        ];
    }

    public function not_active()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    public function keterangan()
    {
        return $this->state(function (array $attributes) {
            return [
                'keterangan' => $this->faker->sentence(10),
            ];
        });
    }
}
