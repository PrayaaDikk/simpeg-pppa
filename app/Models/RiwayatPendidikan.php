<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Table('riwayat_pendidikan')]
#[Fillable([
    'pegawai_id',
    'tingkat',
    'jurusan',
    'institusi',
    'tahun_lulus',
    'tingkat_pendidikan',
    'ijazah',
])]
class RiwayatPendidikan extends Model
{
    use HasFactory;
    
    protected function casts(): array
    {
        return [
            'tahun_lulus' => 'integer',
            'tingkat_pendidikan' => 'integer',
        ];
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
