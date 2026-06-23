<?php

namespace App\Services;

use App\Models\Cuti;
use App\Models\Pegawai;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CutiService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function storeCuti(array $data): Cuti
    {
        DB::beginTransaction();
        try {
            $data['status_cuti'] = 'disetujui';

            $cuti = Cuti::create($data);

            DB::commit();
            return $cuti;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CutiService@storeCuti Error: ' . $e->getMessage(), ['context' => $data]);
            throw new Exception('Gagal menyimpan berkas pengajuan cuti ke sistem.');
        }
    }

    /**
     * Memperbarui isi informasi berkas cuti yang masih berstatus 'disetujui'.
     *
     * @param int $id
     * @param array $data
     * @return Cuti
     * @throws Exception
     */
    public function updateCuti(int $id, array $data): Cuti
    {
        DB::beginTransaction();
        try {
            $cuti = Cuti::findOrFail($id);

            $cuti->update([
                'atasan_id'     => $data['atasan_id'],
                'jenis_cuti'    => $data['jenis_cuti'],
                'tanggal_mulai' => $data['tanggal_mulai'],
                'tanggal_akhir' => $data['tanggal_akhir'],
                'lama_cuti'     => $data['lama_cuti'],
                'alamat'        => $data['alamat'],
                'no_telp'       => $data['no_telp'],
                'alasan_cuti'   => $data['alasan_cuti'] ?? $cuti->alasan_cuti,
            ]);

            DB::commit();
            return $cuti;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("CutiService@updateCuti Error [ID: {$id}]: " . $e->getMessage(), ['context' => $data]);
            throw new Exception('Gagal memperbarui data berkas cuti.');
        }
    }

    /**
     * Mengubah status persetujuan cuti dan mengalkulasi jatah kuota tahunan pegawai.
     *
     * @param int $id
     * @param string $newStatus
     * @return Cuti
     * @throws ValidationException|Exception
     */
    public function updateStatusCuti(int $id, string $newStatus): Cuti
    {
        DB::beginTransaction();
        try {
            $cuti = Cuti::findOrFail($id);
            $oldStatus = $cuti->status_cuti;

            if ($oldStatus === $newStatus) {
                return $cuti;
            }

            if ($cuti->jenis_cuti === 'tahunan') {
                if ($newStatus === 'disetujui') {
                    // 1. Validasi kecukupan akumulasi jatah gabungan N-2, N-1, N
                    $this->validasiKecukupanJatahCuti($cuti->pegawai_id, $cuti->lama_cuti);

                    // 2. Kurangi jatah dengan metode FIFO dan ambil log strukturnya
                    $riwayatLog = $this->potongJatahCuti($cuti->pegawai_id, $cuti->lama_cuti);

                    // 3. Rekam log array potongan ke dalam field JSON berkas cuti
                    $cuti->riwayat_potongan = $riwayatLog;
                } elseif ($oldStatus === 'disetujui' && $newStatus === 'ditangguhkan') {
                    // Kembalikan kuota jatah secara presisi berdasarkan riwayat pemotongan aslinya
                    $this->kembalikanJatahCuti($cuti);
                    $cuti->riwayat_potongan = null;
                }
            }

            $cuti->status_cuti = $newStatus;
            $cuti->save();

            DB::commit();
            return $cuti;
        } catch (ValidationException $ve) {
            DB::rollBack();
            throw $ve; // Lempar kembali ke controller jika berupa error validasi jatah
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("CutiService@updateStatusCuti Error [ID: {$id}]: " . $e->getMessage());
            throw new Exception('Gagal memproses sirkulasi status dan jatah kuota cuti.');
        }
    }

    /**
     * Menghapus record berkas pengajuan cuti dari sistem.
     *
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function deleteCuti(int $id): void
    {
        DB::beginTransaction();
        try {
            $cuti = Cuti::findOrFail($id);

            // Jika berkas yang dihapus sudah berstatus disetujui, kembalikan kuota jatahnya terlebih dahulu
            if ($cuti->jenis_cuti === 'tahunan' && $cuti->status_cuti === 'disetujui') {
                $this->kembalikanJatahCuti($cuti);
            }

            $cuti->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("CutiService@deleteCuti Error [ID: {$id}]: " . $e->getMessage());
            throw new Exception('Gagal menghapus berkas pengajuan cuti dari server.');
        }
    }

    /* ──────────────────────────────────────────────────────────────────────────
       INTERNAL MUTATION CORES (FIFO CALCULATOR)
       ────────────────────────────────────────────────────────────────────────── */

    /**
     * Memeriksa apakah sisa jatah gabungan pegawai mencukupi untuk durasi cuti yang diminta.
     */
    private function validasiKecukupanJatahCuti(int $pegawaiId, int $durasiCuti): void
    {
        $pegawai = Pegawai::findOrFail($pegawaiId);

        $totalJatahTersedia = $pegawai->jatah_cuti_dua_tahun_lalu +
            $pegawai->jatah_cuti_satu_tahun_lalu +
            $pegawai->jatah_cuti_tahun_ini;

        if ($totalJatahTersedia < $durasiCuti) {
            throw ValidationException::withMessages([
                'lama_cuti' => ["Jatah cuti tahunan pegawai tidak mencukupi. Sisa jatah gabungan saat ini: {$totalJatahTersedia} hari."],
            ]);
        }
    }

    /**
     * Memotong jatah cuti tahunan pegawai dengan metode FIFO (Dua tahun lalu -> Satu tahun lalu -> Tahun ini).
     */
    private function potongJatahCuti(int $pegawaiId, int $durasiCuti): array
    {
        $pegawai = Pegawai::lockForUpdate()->findOrFail($pegawaiId);
        $sisaPotong = $durasiCuti;

        $logPotongan = [
            'dua_tahun_lalu'  => 0,
            'satu_tahun_lalu' => 0,
            'tahun_ini'       => 0
        ];

        // 1. Potong jatah kuota 2 tahun lalu
        if ($sisaPotong > 0 && $pegawai->jatah_cuti_dua_tahun_lalu > 0) {
            if ($pegawai->jatah_cuti_dua_tahun_lalu >= $sisaPotong) {
                $logPotongan['dua_tahun_lalu'] = $sisaPotong;
                $pegawai->jatah_cuti_dua_tahun_lalu -= $sisaPotong;
                $sisaPotong = 0;
            } else {
                $logPotongan['dua_tahun_lalu'] = $pegawai->jatah_cuti_dua_tahun_lalu;
                $sisaPotong -= $pegawai->jatah_cuti_dua_tahun_lalu;
                $pegawai->jatah_cuti_dua_tahun_lalu = 0;
            }
        }

        // 2. Potong jatah kuota 1 tahun lalu
        if ($sisaPotong > 0 && $pegawai->jatah_cuti_satu_tahun_lalu > 0) {
            if ($pegawai->jatah_cuti_satu_tahun_lalu >= $sisaPotong) {
                $logPotongan['satu_tahun_lalu'] = $sisaPotong;
                $pegawai->jatah_cuti_satu_tahun_lalu -= $sisaPotong;
                $sisaPotong = 0;
            } else {
                $logPotongan['satu_tahun_lalu'] = $pegawai->jatah_cuti_satu_tahun_lalu;
                $sisaPotong -= $pegawai->jatah_cuti_satu_tahun_lalu;
                $pegawai->jatah_cuti_satu_tahun_lalu = 0;
            }
        }

        // 3. Potong jatah kuota tahun berjalan berjalan ini
        if ($sisaPotong > 0) {
            $logPotongan['tahun_ini'] = $sisaPotong;
            $pegawai->jatah_cuti_tahun_ini -= $sisaPotong;
            $sisaPotong = 0;
        }

        $pegawai->save();
        return $logPotongan;
    }

    /**
     * Mengembalikan kuota jatah cuti ke kolom tahun asal secara presisi menggunakan log riwayat potongan JSON.
     */
    private function kembalikanJatahCuti(Cuti $cuti): void
    {
        if (empty($cuti->riwayat_potongan)) {
            return;
        }

        $pegawai = Pegawai::lockForUpdate()->findOrFail($cuti->pegawai_id);
        $riwayat = $cuti->riwayat_potongan;

        if (!empty($riwayat['dua_tahun_lalu'])) {
            $pegawai->jatah_cuti_dua_tahun_lalu += $riwayat['dua_tahun_lalu'];
        }

        if (!empty($riwayat['satu_tahun_lalu'])) {
            $pegawai->jatah_cuti_satu_tahun_lalu += $riwayat['satu_tahun_lalu'];
        }

        if (!empty($riwayat['tahun_ini'])) {
            $pegawai->jatah_cuti_tahun_ini += $riwayat['tahun_ini'];
        }

        $pegawai->save();
    }
}
