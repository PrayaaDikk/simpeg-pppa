<?php

namespace App\Services;

use App\Models\RiwayatPangkat;
use Illuminate\Support\Facades\Storage;
use Exception;

class RiwayatPangkatService
{
    /**
     * Menyimpan data riwayat pangkat baru beserta dokumen SK lampiran.
     */
    public function store(array $data, int $pegawaiId): RiwayatPangkat
    {
        if (isset($data['file_sk']) && $data['file_sk']->isValid()) {
            $data['file_sk'] = $data['file_sk']->store('riwayat_pangkat', 'public');
        }

        $data['pegawai_id'] = $pegawaiId;

        return RiwayatPangkat::create($data);
    }

    /**
     * Memperbarui data riwayat pangkat dan mengganti berkas SK lama jika ada berkas baru.
     */
    public function update(int $id, array $data, int $pegawaiId): RiwayatPangkat
    {
        $riwayat = RiwayatPangkat::where('pegawai_id', $pegawaiId)->findOrFail($id);

        if (isset($data['file_sk']) && $data['file_sk']->isValid()) {
            // Hapus file SK lama jika admin mengunggah dokumen baru pengganti
            if ($riwayat->file_sk) {
                Storage::disk('public')->delete($riwayat->file_sk);
            }
            $data['file_sk'] = $data['file_sk']->store('riwayat_pangkat', 'public');
        } else {
            // Jaga keamanan file lama jika tidak ada dokumen baru yang diunggah
            unset($data['file_sk']);
        }

        $riwayat->update($data);

        return $riwayat;
    }

    /**
     * Menghapus baris data riwayat pangkat beserta berkas SK fisik dari storage server.
     */
    public function delete(int $id, int $pegawaiId): void
    {
        $riwayat = RiwayatPangkat::where('pegawai_id', $pegawaiId)->findOrFail($id);

        if ($riwayat->file_sk) {
            Storage::disk('public')->delete($riwayat->file_sk);
        }

        $riwayat->delete();
    }

    public function deleteSkDocument(int $id, int $pegawaiId): void
    {
        // Cari baris kepangkatan bersangkutan
        $riwayat = RiwayatPangkat::where('pegawai_id', $pegawaiId)->findOrFail($id);

        // Jika rujukan berkas fisik terdeteksi di DB, hapus dari public storage
        if ($riwayat->file_sk) {
            Storage::disk('public')->delete($riwayat->file_sk);

            // Set field file_sk menjadi null murni di database
            $riwayat->update([
                'file_sk' => null
            ]);
        }
    }
}
