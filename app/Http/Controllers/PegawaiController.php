<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PegawaiController extends Controller
{
    public function index()
    {
        return view('pegawai.home', [
            "data" => Pegawai::all()
        ]);
    }
}
