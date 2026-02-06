<?php

namespace App\Models;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Model;

class RiwayatKepangkatan extends Model
{
    protected $table = 'riwayat_kepangkatan';

    protected $fillable = [
        'tmt_pangkat',
        'nomor_sk',
        'file_sk',
        'tanggal_input',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
