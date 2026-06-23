<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Table('pangkat')]
#[Fillable(['nama_pangkat', 'golongan'])]
class Pangkat extends Model
{
    use HasFactory;
    
    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'pangkat_id');
    }

    public function riwayatPangkat(): HasMany
    {
        return $this->hasMany(RiwayatPangkat::class, 'pangkat_id');
    }
}
