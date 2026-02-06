<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function cutiDiajukan()
    {
        return $this->hasMany(Cuti::class, 'diajukan_oleh');
    }

    public function cutiDisetujui()
    {
        return $this->hasMany(Cuti::class, 'disetujui_oleh');
    }

    public function kgbDiajukan()
    {
        return $this->hasMany(Kgb::class, 'diajukan_oleh');
    }

    public function kgbDisetujui()
    {
        return $this->hasMany(Kgb::class, 'disetujui_oleh');
    }
}
