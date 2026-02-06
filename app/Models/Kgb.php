<?php

namespace App\Models;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Model;

class Kgb extends Model
{
    protected $table = "kgb";

    protected $fillable = [
        'gaji_lama',
        'gaji_baru',
        'tmt_gaji_lama',
        'tmt_kgb',
        'kgb_berikutnya',
        'masa_kerja_lama',
        'masa_kerja_baru',
        'golongan_lama',
        'golongan_baru',
        'status_kgb',
        'diajukan_oleh',
        'disetujui_oleh',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
