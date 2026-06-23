<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Table('cuti')]
#[Fillable([
    'pegawai_id',
    'atasan_id',
    'jenis_cuti',
    'alasan_cuti',
    'tanggal_mulai',
    'tanggal_akhir',
    'lama_cuti',
    'alamat',
    'no_telp',
    'riwayat_potongan',
    'catatan_cuti',
    'status_cuti',
])]
class Cuti extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_akhir' => 'date',
            'lama_cuti' => 'integer',
            'riwayat_potongan' => 'array', // Automatis diubah dari JSON ke Array PHP
        ];
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function atasan(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'atasan_id');
    }
}
