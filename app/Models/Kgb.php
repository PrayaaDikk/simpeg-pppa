<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Table('kgb')]
#[Fillable([
    'pegawai_id',
    'golongan_lama',
    'gaji_lama',
    'masa_kerja_lama',
    'tmt_gaji_lama',
    'golongan_baru',
    'gaji_baru',
    'masa_kerja_baru',
    'tmt_gaji_baru',
    'kgb_berikutnya',
    'status_kgb',
    'parent_id'
])]
class Kgb extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'gaji_lama' => 'integer',
            'gaji_baru' => 'integer',
            'tmt_gaji_lama' => 'date',
            'tmt_gaji_baru' => 'date',
            'kgb_berikutnya' => 'date',
        ];
    }

    protected static function booted()
    {
        // Event yang otomatis terpicu Sesaat SEBELUM data KGB dihapus
        static::deleting(function ($kgb) {
            // Cari data KGB sebelumnya milik pegawai yang sama yang statusnya 'telah diproses'
            // Kita ambil yang paling terakhir berdasarkan id sebelum data yang mau dihapus ini
            $previousKgb = static::where('pegawai_id', $kgb->pegawai_id)
                ->where('id', '<', $kgb->id)
                ->where('status_kgb', 'telah diproses')
                ->orderBy('id', 'desc')
                ->first();

            // Jika ditemukan, kembalikan statusnya menjadi 'disetujui'
            if ($previousKgb) {
                $previousKgb->update([
                    'status_kgb' => 'disetujui'
                ]);
            }
        });
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
