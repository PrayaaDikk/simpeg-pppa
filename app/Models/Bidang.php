<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Table('bidang')]
#[Fillable(['nama_bidang', 'akronim'])]
class Bidang extends Model
{
    use HasFactory;
    
    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'bidang_id');
    }
}
