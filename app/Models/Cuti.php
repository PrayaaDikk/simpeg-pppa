<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $table = 'cuti';

    protected $fillable = [
        'pegawai_id',
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

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->lama_cuti = $model->tanggal_mulai->diffInDays($model->tanggal_selesai);
        });
    }

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
