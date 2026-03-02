<?php

namespace App\Models;

use App\Models\Bidang;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';

    protected $fillable = [
        'nama_jabatan',
        'bidang_id',
        'is_singleton',
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}
