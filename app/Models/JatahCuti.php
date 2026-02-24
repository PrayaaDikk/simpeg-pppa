<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JatahCuti extends Model
{
    protected $table = 'jatah_cuti';

    protected $fillable = [
        'pegawai_id',
        'tahun',
        'jatah_tahunan',
        'sisa_tahun_lalu',
        'terpakai',
        'sisa'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
