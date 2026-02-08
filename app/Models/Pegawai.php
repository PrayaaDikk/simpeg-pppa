<?php

namespace App\Models;

use App\Models\Bidang;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'nip',
        'nama',
        'karpeg',
        'jns_kelamin',
        'agama',
        'tgl_lahir',
        'usia',
        'tpt_lahir',
        'pendidikan',
        'telp',
        'kode_pos',
        'alamat',
        'status_kawin',
        'suami_istri',
        'sta_kerja_suami_istri',
        'jumlah_anak',
        'jns_karyawan',
        'jabatan',
        'gol_ruang',
        'pangkat',
        'tmt_pangkat',
        'masa_kerja_thn',
        'masa_kerja_bln',
        'foto',
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function riwayatPendidikan()
    {
        return $this->hasMany(RiwayatPendidikan::class);
    }

    public function riwayatJabatan()
    {
        return $this->hasMany(RiwayatJabatan::class);
    }

    public function riwayatKepangkatan()
    {
        return $this->hasMany(RiwayatKepangkatan::class);
    }

    public function cuti()
    {
        return $this->hasMany(Cuti::class);
    }

    public function kgb()
    {
        return $this->hasMany(Kgb::class);
    }

    public static function getTotalGender()
    {
        return [
            'total' => Pegawai::count(),
            'lakiLaki' => Pegawai::where('jns_kelamin', 'L')->count(),
            'perempuan' => Pegawai::where('jns_kelamin', 'P')->count(),
        ];
    }
}
