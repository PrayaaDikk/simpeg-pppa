<?php

namespace App\Models;

use App\Models\RiwayatKepangkatan;
use Illuminate\Database\Eloquent\Model;

class Pangkat extends Model
{
    protected $table = 'pangkat';

    protected $fillable = [
        'nama_pangkat',
        'golongan',
    ];

    public function riwayatKepangkatan()
    {
        return $this->hasMany(RiwayatKepangkatan::class);
    }
}
