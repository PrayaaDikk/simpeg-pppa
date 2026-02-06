<?php

namespace App\Models;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    protected $table = 'bidang';

    protected $fillable = [
        'nama_bidang',
    ];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
