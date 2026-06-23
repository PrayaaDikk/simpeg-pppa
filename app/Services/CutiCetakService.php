<?php

namespace App\Services;

use App\Models\Cuti;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CutiCetakService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function generateDocx($id)
    {
        // 1. Ambil data cuti beserta relasi lengkapnya
        $cuti = Cuti::with([
            'pegawai.user',
            'pegawai.jabatan',
            'atasan',
        ])->findOrFail($id);

        // Validasi pengaman: pastikan statusnya sudah disetujui
        if ($cuti->status_cuti !== 'disetujui') {
            throw new Exception("Dokumen belum disetujui secara sah. Cetak ditolak.");
        }

        try {
            // 2. Definisikan lokasi template dan file output temporer
            $templatePath = public_path('templates/CUTI_template.docx');

            if (!file_exists($templatePath)) {
                throw new Exception("File template 'CUTI_template.docx' tidak ditemukan di direktori /public/templates/");
            }

            $templateProcessor = new TemplateProcessor($templatePath);

            // 3. Mapping Variabel Dokumen & Alamat Surat
            // Tanggal surat diambil dari kapan data cuti dibuat (created_at)
            $tanggalSurat = 'Kendari, ' . Carbon::parse($cuti->created_at)->isoFormat('D MMMM YYYY');
            $templateProcessor->setValue('tanggal_surat', $tanggalSurat);

            // Nama atasan yang dituju (Pejabat Berwenang) diambil dari data atasan/kepala dinas di sistem
            $namaAtasanPenerima = $cuti->atasan ? $cuti->atasan->jabatan?->nama_jabatan : 'Kepala DP3A Kota Kendari';
            $templateProcessor->setValue('nama_atasan_penerima', $namaAtasanPenerima);
            $templateProcessor->setValue('tempat_kedudukan_atasan', 'Kendari');

            // 4. Mapping Blok I: Data Pegawai
            $pegawai = $cuti->pegawai;
            $templateProcessor->setValue('nama_pegawai', $pegawai->nama ?? '-');
            $templateProcessor->setValue('nip_pegawai', $pegawai->nip ?? '-');
            $templateProcessor->setValue('jabatan_pegawai', $pegawai->jabatan?->nama_jabatan ?? '-');

            // Mengambil masa kerja jika tersimpan di DB, atau default statis dari data Kiky jika kosong
            $masaKerja = $pegawai->masa_kerja ?? '10 Tahun 07 Bulan';
            $templateProcessor->setValue('masa_kerja', $masaKerja);
            $templateProcessor->setValue('unit_kerja', 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak Kota Kendari');

            // 5. Mapping Blok II & V (Kanan): Checklist Jenis Cuti (Otomatis Cetak )
            $jenis = Str::lower($cuti->jenis_cuti);
            $templateProcessor->setValue('v_tahunan', $jenis === 'tahunan' ? '' : '');
            $templateProcessor->setValue('v_besar', $jenis === 'besar' ? '' : '');
            $templateProcessor->setValue('v_sakit', $jenis === 'sakit' ? '' : '');
            $templateProcessor->setValue('v_melahirkan', $jenis === 'melahirkan' ? '' : '');
            $templateProcessor->setValue('v_alasan_penting', $jenis === 'alasan penting' ? '' : '');
            $templateProcessor->setValue('v_luar_tanggungan', $jenis === 'diluar tanggungan negara' ? '' : '');

            // Sinkronisasi Checklist untuk Tabel Sebelah Kanan Blok V
            $templateProcessor->setValue('c_besar', $jenis === 'besar' ? '' : '');
            $templateProcessor->setValue('c_sakit', $jenis === 'sakit' ? '' : '');
            $templateProcessor->setValue('c_melahirkan', $jenis === 'melahirkan' ? '' : '');
            $templateProcessor->setValue('c_alasan_penting', $jenis === 'alasan penting' ? '' : '');
            $templateProcessor->setValue('c_luar_tanggungan', $jenis === 'diluar tanggungan negara' ? '' : '');

            // 6. Mapping Blok III & IV: Alasan & Lamanya Cuti
            $templateProcessor->setValue('alasan_cuti', $cuti->alasan_cuti ?? '-');

            // Format durasi dalam bentuk teks angka + terbilang
            $lamaCutiTeks = $cuti->lama_cuti . ' (' . $this->terbilang($cuti->lama_cuti) . ') Hari Kerja';
            $templateProcessor->setValue('lama_cuti_teks', $lamaCutiTeks);

            $templateProcessor->setValue('tanggal_mulai', Carbon::parse($cuti->tanggal_mulai)->isoFormat('D MMMM YYYY'));
            $templateProcessor->setValue('tanggal_akhir', Carbon::parse($cuti->tanggal_akhir)->isoFormat('D MMMM YYYY'));

            // 7. Mapping Blok V (Kiri): Catatan Sisa Jatah Kuota dari DB Pegawai
            $templateProcessor->setValue('jatah_dua_tahun_lalu', $pegawai->jatah_cuti_dua_tahun_lalu ?? '0');
            $templateProcessor->setValue('jatah_satu_tahun_lalu', $pegawai->jatah_cuti_satu_tahun_lalu ?? '0');
            $templateProcessor->setValue('jatah_tahun_ini', $pegawai->jatah_cuti_tahun_ini ?? '0');

            // 8. Mapping Blok VI: Alamat & Telepon Selama Cuti
            $templateProcessor->setValue('alamat_cuti', $cuti->alamat ?? '-');
            $templateProcessor->setValue('no_telp', $cuti->no_telp ?? '-');

            // 9. Mapping Blok VII & VIII: Data Tanda Tangan (Pemohon, Atasan, & Pejabat Sah)
            $templateProcessor->setValue('nama_pegawai_caps', Str::upper($pegawai->nama));

            // Atasan langsung yang meninjau
            $templateProcessor->setValue('nama_atasan', $cuti->atasan?->nama ?? '( WD. ST. SUPINAWATI, S.TP )');
            $templateProcessor->setValue('nip_atasan', $cuti->atasan?->nip ?? '197101061997032005');

            // Pejabat berwenang tertinggi (bisa disesuaikan dengan konfigurasi sistem Anda)
            // Di sini kita defaultkan ke Kepala Dinas seperti contoh dokumen asli Anda
            $templateProcessor->setValue('nama_pejabat_sah', 'FITRIANI SINAPOY, A.Pi., MP');
            $templateProcessor->setValue('nip_pejabat_sah', '197609102000032003');

            // 10. Simpan hasil manipulasi berkas ke folder temporary server
            $fileName = 'Form_Cuti_' . Str::slug($pegawai->nama) . '_' . uniqid() . '.docx';
            $tempPath = storage_path('app/public/' . $fileName);

            $templateProcessor->saveAs($tempPath);

            return $tempPath;
        } catch (Exception $e) {
            Log::error('Gagal memproses ekspor berkas DOCX: ' . $e->getMessage(), [
                'cuti_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('Terjadi kesalahan internal saat merakit template dokumen.');
        }
    }

    /**
     * Helper Sederhana Konversi Angka ke Terbilang Bahasa Indonesia untuk Durasi Hari.
     */
    private function terbilang($angka)
    {
        $bilangan = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        if ($angka < 12) {
            return $bilangan[$angka];
        } else if ($angka < 20) {
            return $bilangan[$angka - 10] . " belas";
        } else if ($angka < 100) {
            return $bilangan[floor($angka / 10)] . " puluh " . $bilangan[$angka % 10];
        }
        return "banyak";
    }
}
