<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePegawaiRequest;
use App\Models\Bidang;
use App\Models\Pangkat;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with('bidang');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nip', 'LIKE', "%{$search}%")
                    ->orWhere('jabatan', 'LIKE', "%{$search}%");
            });
        }

        // Filter Jenis Pegawai
        if ($request->filled('jenis')) {
            $query->whereIn('jns_karyawan', $request->jenis);
        }

        // Filter Golongan
        if ($request->filled('gol_ruang')) {
            $golonganPatterns = [];
            foreach ($request->gol_ruang as $gol) {
                $golonganPatterns[] = $gol;
            }
            $query->where(function ($q) use ($golonganPatterns) {
                foreach ($golonganPatterns as $pattern) {
                    $q->orWhere('gol_ruang', 'LIKE', $pattern . '/' . '%');
                }
            });
        }

        // Filter Bidang
        if ($request->filled('bidang')) {
            $query->whereIn('bidang_id', $request->bidang);
        }

        $pegawai = $query->paginate(10)->withQueryString();

        // Get bidang options for filter
        $bidangOptions = Bidang::pluck('nama_bidang', 'id')->toArray();

        return view('admin.pegawai.index', compact('pegawai', 'bidangOptions'));
    }

    public function show($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pendidikanTerakhir = $pegawai->riwayatPendidikan()->latest('tahun_lulus')->first();

        return view('admin.pegawai.show', compact('pegawai', 'pendidikanTerakhir'));
    }

    public function create()
    {
        $pangkat = Pangkat::all();
        $bidang = Bidang::all();

        return view('admin.pegawai.create', compact('pangkat', 'bidang'));
    }

    public function store(StorePegawaiRequest $request)
    {
        $validated = $request->validated();

        $validated['usia'] = Carbon::parse($validated['tgl_lahir'])->age;
        $validated['pangkat'] = Pangkat::where('golongan', $validated['gol_ruang'])->value('nama_pangkat');
        $validated['masa_kerja_thn'] = Carbon::parse($validated['tmt_pangkat'])->diffInYears(Carbon::now());
        $validated['masa_kerja_bln'] = Carbon::parse($validated['tmt_pangkat'])->diffInMonths(Carbon::now()) % 12;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = $validated['nip'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/pegawai', $filename);
            $validated['foto'] = $filename;
        }

        Pegawai::create($validated);
        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Pegawai::findOrFail($id)->delete();
        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}
