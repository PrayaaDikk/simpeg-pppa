<?php

namespace App\Services;

use App\Models\RiwayatPendidikan;
use Illuminate\Support\Facades\Storage;
use Exception;

class RiwayatPendidikanService
{
    /**
     * Menyimpan data riwayat pendidikan baru beserta dokumen ijazah.
     */
    public function store(array $data, int $pegawaiId): RiwayatPendidikan
    {
        if (isset($data['ijazah']) && $data['ijazah']->isValid()) {
            $data['ijazah'] = $data['ijazah']->store('riwayat_ijazah', 'public');
        }

        $data['pegawai_id'] = $pegawaiId;
        $data['tingkat_pendidikan'] = $this->mapTingkatKeBobot($data['tingkat']);

        return RiwayatPendidikan::create($data);
    }

    /**
     * Memperbarui data riwayat pendidikan dan mengelola pembaruan berkas ijazah.
     */
    public function update(array $data, int $id, int $pegawaiId): RiwayatPendidikan
    {
        $riwayat = RiwayatPendidikan::where('pegawai_id', $pegawaiId)->findOrFail($id);

        if (isset($data['ijazah']) && $data['ijazah'] instanceof \Illuminate\Http\UploadedFile && $data['ijazah']->isValid()) {
            if ($riwayat->ijazah) {
                Storage::disk('public')->delete($riwayat->ijazah);
            }
            $data['ijazah'] = $data['ijazah']->store('riwayat_ijazah', 'public');
        } else {
            unset($data['ijazah']);
        }

        $data['tingkat_pendidikan'] = $this->mapTingkatKeBobot($data['tingkat']);
        $riwayat->update($data);

        return $riwayat;
    }

    /**
     * Menghapus baris data riwayat pendidikan beserta berkas fisik dari storage disk.
     */
    public function delete(int $id, int $pegawaiId): void
    {
        $riwayat = RiwayatPendidikan::where('pegawai_id', $pegawaiId)->findOrFail($id);

        if ($riwayat->ijazah) {
            Storage::disk('public')->delete($riwayat->ijazah);
        }

        $riwayat->delete();
    }

    public function deleteIjazahFile(int $id, int $pegawaiId): void
    {
        $riwayat = RiwayatPendidikan::where('pegawai_id', $pegawaiId)->findOrFail($id);

        // Jika terdapat path berkas fisik, hapus dari disk public
        if ($riwayat->ijazah) {
            Storage::disk('public')->delete($riwayat->ijazah);

            // Ubah field ijazah menjadi null di database
            $riwayat->update([
                'ijazah' => null
            ]);
        }
    }

    /**
     * Memetakan string jenjang kelulusan ke bobot integer untuk standarisasi sorting data.
     */
    private function mapTingkatKeBobot(string $tingkat): int
    {
        return match ($tingkat) {
            'SMA'   => 1,
            'D1'    => 2,
            'D2'    => 3,
            'D3'    => 4,
            'D4'    => 5,
            'S1'    => 6,
            'S2'    => 7,
            'S3'    => 8,
            default => 0,
        };
    }
}
