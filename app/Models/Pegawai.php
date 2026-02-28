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
        'jabatan_id',
        'atasan_id',
        'nip',
        'nama',
        'karpeg',
        'jns_kelamin',
        'agama',
        'tgl_lahir',
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
        'gol_ruang',
        'pangkat',
        'tmt_pegawai',
        'kuota_cuti',
        'is_active',
        'keterangan',
        'foto',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'tmt_pegawai' => 'date',
        'jumlah_anak' => 'integer',
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function atasan()
    {
        return $this->belongsTo(Pegawai::class);
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

    public function getMasaKerja()
    {
        $masaKerja = $this->calculateMasaKerja($this->tmt_pangkat);
        return $masaKerja['tahun'] . ' Tahun ' . $masaKerja['bulan'] . ' Bulan';
    }

    public static function getTotalGender()
    {
        return [
            'total' => Pegawai::where('is_active', 1)->count(),
            'lakiLaki' => Pegawai::where('is_active', 1)->where('jns_kelamin', 'L')->count(),
            'perempuan' => Pegawai::where('is_active', 1)->where('jns_kelamin', 'P')->count(),
        ];
    }

    // Mutators & Accessors
    public function setTglLahirAttribute($value)
    {
        $this->attributes['tgl_lahir'] = $value;
    }

    public function setTmtPangkatAttribute($value)
    {
        $this->attributes['tmt_pangkat'] = $value;
        $masaKerja = $this->calculateMasaKerja($value);
        $this->attributes['masa_kerja_thn'] = $masaKerja['tahun'];
        $this->attributes['masa_kerja_bln'] = $masaKerja['bulan'];
    }

    // Helper Methods
    public function calculateAge()
    {
        return Carbon::parse($this->attributes['tgl_lahir'])->age;
    }

    public function calculateMasaKerja($tmt_pegawai)
    {
        if (!$tmt_pegawai) {
            return ['tahun' => 0, 'bulan' => 0];
        }

        $tmt = \Carbon\Carbon::parse($tmt_pegawai);
        $now = \Carbon\Carbon::now();

        // Menggunakan diff() untuk mendapatkan objek DateInterval yang akurat
        $diff = $tmt->diff($now);

        return [
            'tahun' => $diff->y, // y adalah property untuk tahun
            'bulan' => $diff->m, // m adalah property untuk bulan
        ];
    }

    public function getPangkatFromGolongan($golRuang)
    {
        $pangkat = Pangkat::where('golongan', $golRuang)->first();

        return $pangkat ? $pangkat->nama_pangkat : null;
    }
}
