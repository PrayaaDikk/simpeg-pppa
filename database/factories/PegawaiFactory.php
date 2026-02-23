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
        $usia = Carbon::parse('2005-08-17')->diff(now())->y;

        return [
            'bidang_id' => 1,

            'nip' => '200508172024031001',
            'karpeg' => null,

            'nama' => 'Fadil Prayadika',
            'jns_kelamin' => 'L',
            'agama' => 'Islam',

            'tgl_lahir' => '2005-08-17',
            'usia' => $usia,

            'tpt_lahir' => 'Kota Kendari',
            'telp' => '082119498353',
            'kode_pos' => '93121', // Contoh kodepos Kendari
            'alamat' => 'Jl. Palapa, Kemaraya, Kendari Barat',

            'status_kawin' => 'Kawin',
            'suami_istri' => 'Afidelya Kanaya Ozara S.',
            'sta_kerja_suami_istri' => 'Kerja',
            'jumlah_anak' => 1,

            'jns_karyawan' => 'PNS',

            'jabatan' => 'Kepala Dinas',
            'gol_ruang' => 'III/a',
            'pangkat' => 'Penata Muda',
            'tmt_pangkat' => '2024-03-10',
            'masa_kerja_thn' => 1,
            'masa_kerja_bln' => 11,
            'foto' => null,
        ];
    }
}
