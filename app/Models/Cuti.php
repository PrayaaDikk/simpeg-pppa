<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $table = 'cuti';

    protected $fillable = [
        'jenis_cuti',
        'alasan_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'lama_cuti',
        'alamat_cuti',
        'no_telp',
        'catatan_cuti',
        'status_cuti',
        'keputusan_atasan',
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
