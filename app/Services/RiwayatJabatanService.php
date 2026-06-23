<?php

namespace App\Services;

use App\Models\RiwayatJabatan;

class RiwayatJabatanService
{
    /**
     * Menyimpan data riwayat jabatan baru pegawai.
     */
    public function store(array $data, int $pegawaiId): RiwayatJabatan
    {
        $data['pegawai_id'] = $pegawaiId;

        return RiwayatJabatan::create($data);
    }

    /**
     * Memperbarui data riwayat jabatan milik pegawai tertentu.
     */
    public function update(int $id, array $data, int $pegawaiId): RiwayatJabatan
    {
        $riwayat = RiwayatJabatan::where('pegawai_id', $pegawaiId)->findOrFail($id);
        $riwayat->update($data);

        return $riwayat;
    }

    /**
     * Menghapus record riwayat jabatan pegawai.
     */
    public function delete(int $id, int $pegawaiId): void
    {
        $riwayat = RiwayatJabatan::where('pegawai_id', $pegawaiId)->findOrFail($id);
        $riwayat->delete();
    }
}
