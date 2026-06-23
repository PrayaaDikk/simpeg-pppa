<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Table('pegawai')]
#[Fillable([
    'user_id',
    'bidang_id',
    'jabatan_id',
    'nip',
    'nama',
    'karpeg',
    'jenis_kelamin',
    'agama',
    'tempat_lahir',
    'tanggal_lahir',
    'no_telp',
    'kode_pos',
    'alamat',
    'status_kawin',
    'nama_pasangan',
    'status_kerja_pasangan',
    'jumlah_anak',
    'jenis_pegawai',
    'pangkat_id',
    'tmt_pegawai',
    'jatah_cuti_dua_tahun_lalu',
    'jatah_cuti_satu_tahun_lalu',
    'jatah_cuti_tahun_ini',
    'is_active',
])]
class Pegawai extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'datetime',
            'tmt_pegawai' => 'datetime',
            'jumlah_anak' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function pendidikanTerakhir()
    {
        return $this->hasOne(RiwayatPendidikan::class, 'pegawai_id')
            ->where(function ($query) {
                $query->select('id')
                    ->from('riwayat_pendidikan as inner_rp')
                    ->whereColumn('inner_rp.pegawai_id', 'riwayat_pendidikan.pegawai_id')
                    ->orderBy('inner_rp.tingkat_pendidikan', 'desc')
                    ->orderBy('inner_rp.tahun_lulus', 'desc')
                    ->limit(1);
            }, '=', function ($query) {
                // Memastikan pencocokan ID dilakukan di level baris riwayat pendidikan tujuan
                $query->select('id')
                    ->from('riwayat_pendidikan as target_rp')
                    ->whereColumn('target_rp.id', 'riwayat_pendidikan.id');
            });
    }

    public function pendidikan()
    {
        return $this->hasOne(RiwayatPendidikan::class, 'pegawai_id')
            ->ofMany([
                'tingkat_pendidikan' => 'max', // Cari tingkat tertinggi dulu (misal: S1 > D3)
                'tahun_lulus' => 'max',        // Jika tingkatnya sama, ambil tahun lulus paling baru
            ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function pangkat(): BelongsTo
    {
        return $this->belongsTo(Pangkat::class, 'pangkat_id');
    }


    public function riwayatPendidikan(): HasMany
    {
        return $this->hasMany(RiwayatPendidikan::class, 'pegawai_id');
    }

    public function riwayatPangkat(): HasMany
    {
        return $this->hasMany(RiwayatPangkat::class, 'pegawai_id');
    }

    public function riwayatJabatan(): HasMany
    {
        return $this->hasMany(RiwayatJabatan::class, 'pegawai_id');
    }

    public function cuti(): HasMany
    {
        return $this->hasMany(Cuti::class, 'pegawai_id');
    }

    public function kgb(): HasMany
    {
        return $this->hasMany(Kgb::class, 'pegawai_id');
    }
}
