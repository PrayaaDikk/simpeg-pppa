<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Table('jabatan')]
#[Fillable(['nama_jabatan', 'is_singleton'])]
class Jabatan extends Model
{
    use HasFactory;
    
    protected function casts(): array
    {
        return [
            'is_singleton' => 'boolean',
        ];
    }

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'jabatan_id');
    }
}
