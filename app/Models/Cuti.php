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
        'n',
        'n_1',
        'n_2',
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
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->lama_cuti = $model->tanggal_mulai->diffInDays($model->tanggal_selesai) + 1;

            // Opsional: Batalkan jika kuota tidak cukup (safety net)
            if ($model->pegawai && $model->lama_cuti > $model->pegawai->kuota_cuti) {
                return false; // Menghentikan proses create/save
            }
        });

        static::created(function ($model) {
            $pegawai = $model->pegawai;
            if ($pegawai) {
                $pegawai->kuota_cuti -= $model->lama_cuti;
                $pegawai->save();
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('lama_cuti')) {
                $oldLamaCuti = $model->getOriginal('lama_cuti');
                $newLamaCuti = $model->lama_cuti;

                $pegawai = $model->pegawai;
                if ($pegawai) {
                    $pegawai->kuota_cuti = ($pegawai->kuota_cuti + $oldLamaCuti) - $newLamaCuti;
                    $pegawai->save();
                }
            }
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
