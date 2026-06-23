<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Table('riwayat_pangkat')]
#[Fillable([
    'pegawai_id',
    'pangkat_id',
    'tmt_pangkat',
    'nomor_sk',
    'file_sk',
])]
class RiwayatPangkat extends Model
{
    use HasFactory;
    
    protected function casts(): array
    {
        return [
            'tmt_pangkat' => 'date',
        ];
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function pangkat(): BelongsTo
    {
        return $this->belongsTo(Pangkat::class, 'pangkat_id');
    }
}
