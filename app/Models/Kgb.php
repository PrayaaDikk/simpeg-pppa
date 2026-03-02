<?php

namespace App\Models;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kgb extends Model
{
    use HasFactory;

    protected $table = "kgb";

    protected $fillable = [
        'pegawai_id',
        'nomor_surat',
        'sk_pejabat_penetap',
        'sk_tanggal',
        'sk_nomor',
        'gaji_pokok_lama',
        'tgl_mulai_gaji_lama',
        'masa_kerja_tahun_lama',
        'masa_kerja_bulan_lama',
        'gaji_pokok_baru',
        'gol_ruang_baru',
        'masa_kerja_tahun_baru',
        'masa_kerja_bulan_baru',
        'tgl_mulai_berlaku',
        'tgl_kgb_mendatang',
        'status_pegawai',
        'file_sk',
        'diajukan_oleh',
        'disetujui_oleh',
        'status_kgb'
    ];

    protected $casts = [
        'sk_tanggal' => 'datetime',
        'tgl_mulai_gaji_lama' => 'datetime',
        'tgl_mulai_berlaku' => 'datetime',
        'tgl_kgb_mendatang' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
