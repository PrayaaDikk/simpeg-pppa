<?php

namespace App\Models;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Model;

class RiwayatJabatan extends Model
{
    protected $table = 'riwayat_jabatan';

    protected $fillable = [
        'pegawai_id',
        'nama_jabatan',
        'tmt_jabatan',
        'nomor_sk',
        'tanggal_sk',
        'file_sk',
    ];

    protected $casts = [
        'tmt_jabatan' => 'date',
        'tanggal_sk' => 'date'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
