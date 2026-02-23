<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $gender = Pegawai::getTotalGender();
        $bidang = Pegawai::with('bidang')
            ->get()
            ->groupBy('bidang_id')
            ->sortKeys();

        return view('admin.dashboard', compact('gender', 'bidang'));
    }
}
