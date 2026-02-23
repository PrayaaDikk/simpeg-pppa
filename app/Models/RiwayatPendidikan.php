<?php

namespace App\Models;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPendidikan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pendidikan';

    protected $fillable = [
        'pegawai_id',
        'tingkat',
        'jurusan',
        'institusi',
        'nomor_ijazah',
        'tahun_lulus',
        'ijazah'
    ];

    public static $mapLevel = [
        'SMA' => 1,
        'D3'  => 2,
        'D4'  => 3,
        'S1'  => 4,
        'S2'  => 5,
        'S3'  => 6,
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->level_pendidikan = self::$mapLevel[$model->tingkat] ?? 0;
        });
    }
}
