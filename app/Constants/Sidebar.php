<?php

namespace App\Constants;

class Sidebar
{
    /**
     * Create a new class instance.
     */

    public const SIDEBAR_URL = [
        'admin' => [
            'home' => 'admin',
            'pegawai' => 'admin/pegawai',
            'cuti' => 'admin/cuti',
            'kgb' => 'admin/kgb',
        ],
        'user' => [
            'home' => '/',
            'pegawai' => 'pegawai',
            'cuti' => 'cuti',
            'kgb' => 'kgb',
        ]
    ];



    public function __construct()
    {
        //
    }
}
