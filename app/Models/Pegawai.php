<?php

namespace App\Models;

use App\Models\Bidang;
use App\Models\Cuti;
use App\Models\Kgb;
use App\Models\Pangkat;
use App\Models\RiwayatJabatan;
use App\Models\RiwayatKepangkatan;
use App\Models\RiwayatPendidikan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model

{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'bidang_id',
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

    protected $casts = [
        'tgl_lahir' => 'date',
        'tmt_pangkat' => 'date',
        'jumlah_anak' => 'integer',
        'usia' => 'integer',
        'masa_kerja_thn' => 'integer',
        'masa_kerja_bln' => 'integer',
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

    public function pendidikanTerakhir()
    {
        return $this->hasOne(RiwayatPendidikan::class)->ofMany([
            'level_pendidikan' => 'max',
            'tahun_lulus' => 'max'
        ]);
    }

    public static function getTotalGender()
    {
        return [
            'total' => Pegawai::count(),
            'lakiLaki' => Pegawai::where('jns_kelamin', 'L')->count(),
            'perempuan' => Pegawai::where('jns_kelamin', 'P')->count(),
        ];
    }

    // Mutators & Accessors
    public function setTglLahirAttribute($value)
    {
        $this->attributes['tgl_lahir'] = $value;
        $this->attributes['usia'] = $this->calculateAge($value);
    }

    public function setTmtPangkatAttribute($value)
    {
        $this->attributes['tmt_pangkat'] = $value;
        $masaKerja = $this->calculateMasaKerja($value);
        $this->attributes['masa_kerja_thn'] = $masaKerja['tahun'];
        $this->attributes['masa_kerja_bln'] = $masaKerja['bulan'];
    }

    // Helper Methods
    public function calculateAge($tglLahir)
    {
        return Carbon::parse($tglLahir)->age;
    }

    public function calculateMasaKerja($tmtPangkat)
    {
        $tmt = Carbon::parse($tmtPangkat);
        $now = Carbon::now();

        $tahun = $now->diffInYears($tmt);
        $bulan = $now->copy()->subYears($tahun)->diffInMonths($tmt);

        return [
            'tahun' => $tahun,
            'bulan' => $bulan,
        ];
    }

    public function getPangkatFromGolongan($golRuang)
    {
        $pangkat = Pangkat::where('golongan', $golRuang)->first();

        return $pangkat ? $pangkat->nama_pangkat : null;
    }
}
