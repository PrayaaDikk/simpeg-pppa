<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Table('riwayat_jabatan')]
#[Fillable([
    'pegawai_id',
    'nama_jabatan',
    'tmt_jabatan',
    'nomor_sk',
    'tanggal_sk',
])]
class RiwayatJabatan extends Model
{
    use HasFactory;
    
    protected function casts(): array
    {
        return [
            'tmt_jabatan' => 'date',
            'tanggal_sk' => 'date',
        ];
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
