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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pegawai_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

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
