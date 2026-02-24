<?php

namespace App\Models;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Model;

class RiwayatKepangkatan extends Model
{
    protected $table = 'riwayat_kepangkatan';

    protected $fillable = [
        'pegawai_id',
        'pangkat_id',
        'tmt_pangkat',
        'nomor_sk',
        'file_sk',
        'tanggal_input',
    ];

    protected $casts = [
        'tmt_pangkat' => 'date',
        'tanggal_input' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
