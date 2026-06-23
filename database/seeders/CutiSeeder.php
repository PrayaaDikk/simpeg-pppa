<?php

namespace Database\Seeders;

use App\Models\Cuti;
use App\Models\Pegawai;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengambil semua pegawai yang aktif dari database
        $pegawais = Pegawai::where('is_active', true)->get();

        // Pastikan ada pegawai yang terdaftar sebelum seeding data cuti
        if ($pegawais->count() < 2) {
            $this->command->warn('Gagal running CutiSeeder: Minimal harus ada 2 pegawai terdaftar di database untuk menentukan relasi atasan.');
            return;
        }

        // Memilih maksimal 25 pegawai secara acak untuk mensimulasikan pengajuan cuti
        $targetPegawai = $pegawais->random(min(25, $pegawais->count()));

        // Opsi enum dan data dummy sesuai ketentuan file migration cuti
        $jenisCutiOptions = ['tahunan', 'besar', 'sakit', 'melahirkan', 'alasan penting', 'diluar tanggungan negara'];
        $statusOptions = ['disetujui', 'ditangguhkan'];

        $daftarAlasan = [
            'Keperluan keluarga penting di luar kota mendesak.',
            'Kondisi medis memerlukan istirahat total berdasarkan surat rekomendasi dokter.',
            'Melaksanakan ibadah keagamaan / umroh ke tanah suci.',
            'Persiapan menjelang persalinan anak kedua.',
            'Mengurus urusan warisan dan properti keluarga yang tidak bisa diwakilkan.',
        ];

        $daftarAlamat = [
            'Jl. Kaliurang KM 5.5, No. 23, Sleman, DI Yogyakarta',
            'Perumahan Dosen UHO, Blok A No. 12, Kendari',
            'Jl. Jendral Sudirman No. 45, Kendari',
            'Perumahan Kota Hijau, Blok C, Poasia, Kendari',
        ];

        foreach ($targetPegawai as $pegawai) {
            // Menentukan jumlah pengajuan cuti acak untuk setiap pegawai pilihan (1-2 records)
            $jumlahPengajuan = rand(1, 2);

            for ($i = 0; $i < $jumlahPengajuan; $i++) {
                $lamaCuti = rand(2, 5); // Lama cuti antara 2 sampai 5 hari

                // Membuat tanggal mulai acak dalam rentang tahun ini
                $tanggalMulai = Carbon::now()->startOfYear()->addDays(rand(1, 150));
                $tanggalAkhir = (clone $tanggalMulai)->addDays($lamaCuti - 1);

                // Mencari atasan acak (memastikan atasan_id tidak sama dengan pegawai_id yang mengajukan)
                $atasan = $pegawais->where('id', '!=', $pegawai->id)->random();

                // Menentukan status acak sesuai dengan isi opsi enum migration
                $statusAcak = $statusOptions[array_rand($statusOptions)];

                Cuti::create([
                    'pegawai_id'    => $pegawai->id,
                    'atasan_id'     => $atasan->id,
                    'jenis_cuti'    => $jenisCutiOptions[array_rand($jenisCutiOptions)],
                    'alasan_cuti'   => $daftarAlasan[array_rand($daftarAlasan)],
                    'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
                    'lama_cuti'     => $lamaCuti,
                    'alamat'        => $daftarAlamat[array_rand($daftarAlamat)],
                    'no_telp'       => '08' . rand(111111111, 999999999),
                    'catatan_cuti'  => 'Telah ditinjau dan disesuaikan dengan kuota operasional unit kerja.',
                    'status_cuti'   => $statusAcak, // Menggunakan field 'status_cuti' sesuai migration
                ]);
            }
        }
    }
}
