<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Pangkat;
use App\Models\RiwayatPendidikan;
use App\Models\RiwayatPangkat;
use App\Models\RiwayatJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RiwayatPegawaiController extends Controller
{
    /**
     * Menampilkan halaman kelola seluruh riwayat pegawai
     */
    public function index($pegawai_id)
    {
        $pegawai = Pegawai::with([
            'pangkat',
            'jabatan',
            'bidang',
            'user'
        ])
            ->whereDoesntHave('user', function ($q) {
                $q->role('superadmin');
            })
            ->findOrFail($pegawai_id);

        // Mengambil riwayat pendidikan dengan pengurutan tingkat tertinggi ke terendah
        $riwayatPendidikan = RiwayatPendidikan::where('pegawai_id', $pegawai_id)
            ->orderBy('tingkat_pendidikan', 'desc')
            ->orderBy('tahun_lulus', 'desc')
            ->get();

        $riwayatPangkat = RiwayatPangkat::with('pangkat')
            ->where('pegawai_id', $pegawai_id)
            ->orderBy('tmt_pangkat', 'desc')
            ->get();

        $riwayatJabatan = RiwayatJabatan::where('pegawai_id', $pegawai_id)
            ->orderBy('tmt_jabatan', 'desc')
            ->get();

        // Mengambil master data pangkat untuk kebutuhan dropdown pilihan form
        $masterPangkat = Pangkat::orderBy('golongan', 'asc')->get();

        return Inertia::render('admin/pegawai/riwayat', [
            'pegawai' => $pegawai,
            'riwayatPendidikan' => $riwayatPendidikan,
            'riwayatPangkat' => $riwayatPangkat,
            'riwayatJabatan' => $riwayatJabatan,
            'masterPangkat' => $masterPangkat
        ]);
    }
}
