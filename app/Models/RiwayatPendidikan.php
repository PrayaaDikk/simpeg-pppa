<?php

namespace App\Models;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Model;

class RiwayatPendidikan extends Model
{
    protected $table = 'riwayat_pendidikan';

    protected $fillable = [
        'tingkat',
        'jurusan',
        'institusi',
        'tahun_lulus',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
