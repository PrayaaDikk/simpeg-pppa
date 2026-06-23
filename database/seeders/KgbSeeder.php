<?php

namespace Database\Seeders;

use App\Models\Kgb;
use App\Models\Pegawai;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class KgbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pegawais = Pegawai::where('is_active', true)->get();

        if ($pegawais->isEmpty()) {
            return;
        }

        // Ambil contoh 30 pegawai untuk diisi riwayat KGB-nya secara acak
        $targetPegawai = $pegawais->random(min(30, $pegawais->count()));

        foreach ($targetPegawai as $index => $pegawai) {
            // Simulasi 1: Riwayat KGB tahun lalu yang sudah Disetujui
            Kgb::create([
                'pegawai_id' => $pegawai->id,
                'golongan_lama' => 'III/a',
                'gaji_lama' => 2579400,
                'masa_kerja_lama' => '02 Tahun 00 Bulan',
                'tmt_gaji_lama' => Carbon::now()->subYears(3)->format('Y-m-d'),
                'golongan_baru' => 'III/a',
                'gaji_baru' => 2659800,
                'masa_kerja_baru' => '04 Tahun 00 Bulan',
                'tmt_gaji_baru' => Carbon::now()->subYears(1)->format('Y-m-d'),
                'kgb_berikutnya' => Carbon::now()->addYear()->format('Y-m-d'),
                'status_kgb' => 'disetujui',
                'created_at' => Carbon::now()->subYears(1),
            ]);

            // Simulasi 2: Kondisi kondisional berdasarkan indeks looping
            if ($index % 3 === 0) {
                // Pegawai yang masuk jadwal KGB BULAN INI (Untuk menguji fitur Cetak Rekap & Tab Bulan Ini)
                Kgb::create([
                    'pegawai_id' => $pegawai->id,
                    'golongan_lama' => 'III/b',
                    'gaji_lama' => 2688500,
                    'masa_kerja_lama' => '04 Tahun 00 Bulan',
                    'tmt_gaji_lama' => Carbon::now()->subYears(2)->format('Y-m-d'),
                    'golongan_baru' => 'III/b',
                    'gaji_baru' => 2773200,
                    'masa_kerja_baru' => '06 Tahun 00 Bulan',
                    'tmt_gaji_baru' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                    // Sengaja diset pas bulan ini agar lolos query where('kgb_berikutnya', 'like', 'YYYY-MM%')
                    'kgb_berikutnya' => Carbon::now()->format('Y-m-d'),
                    'status_kgb' => 'menunggu',
                    'created_at' => Carbon::now()->subDays(5),
                ]);
            } elseif ($index % 3 === 1) {
                // Pegawai dengan berkas baru masuk yang statusnya masih Menunggu Verifikasi
                Kgb::create([
                    'pegawai_id' => $pegawai->id,
                    'golongan_lama' => 'IV/a',
                    'gaji_lama' => 3044300,
                    'masa_kerja_lama' => '08 Tahun 00 Bulan',
                    'tmt_gaji_lama' => Carbon::now()->subYears(2)->subMonths(2)->format('Y-m-d'),
                    'golongan_baru' => 'IV/a',
                    'gaji_baru' => 3140100,
                    'masa_kerja_baru' => '10 Tahun 00 Bulan',
                    'tmt_gaji_baru' => Carbon::now()->addMonths(2)->format('Y-m-d'),
                    'kgb_berikutnya' => Carbon::now()->addYears(2)->addMonths(2)->format('Y-m-d'),
                    'status_kgb' => 'menunggu',
                    'created_at' => Carbon::now()->subDays(2),
                ]);
            } else {
                // Berkas yang Ditolak / Tidak Disetujui karena berkas kurang lengkap
                Kgb::create([
                    'pegawai_id' => $pegawai->id,
                    'golongan_lama' => 'II/c',
                    'gaji_lama' => 2301800,
                    'masa_kerja_lama' => '02 Tahun 00 Bulan',
                    'tmt_gaji_lama' => Carbon::now()->subYears(2)->format('Y-m-d'),
                    'golongan_baru' => 'II/c',
                    'gaji_baru' => 2373500,
                    'masa_kerja_baru' => '04 Tahun 00 Bulan',
                    'tmt_gaji_baru' => Carbon::now()->format('Y-m-d'),
                    'kgb_berikutnya' => Carbon::now()->addYears(2)->format('Y-m-d'),
                    'status_kgb' => 'tidak disetujui',
                    'created_at' => Carbon::now()->subDays(10),
                ]);
            }
        }
    }
}
