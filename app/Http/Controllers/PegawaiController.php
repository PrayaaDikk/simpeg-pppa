<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePegawaiRequest;
use App\Http\Requests\UpdatePegawaiRequest;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Pangkat;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with(['bidang']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nip', 'LIKE', "%{$search}%")
                    ->orWhereHas('jabatan', function ($subQuery) use ($search) {
                        $subQuery->where('nama_jabatan', 'LIKE', "%{$search}%");
                    });
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

        $pegawai = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();

        // Get bidang options for filter
        $bidangOptions = Bidang::pluck('nama_bidang', 'id')->toArray();

        return view('admin.pegawai.index', compact('pegawai', 'bidangOptions'));
    }

    public function show($id)
    {
        $pegawai = Pegawai::with('jabatan')->findOrFail($id);
        $pendidikanTerakhir = $pegawai->pendidikanTerakhir;

        return view('admin.pegawai.show', compact('pegawai', 'pendidikanTerakhir'));
    }

    public function create()
    {
        $pangkat = Pangkat::all();
        $bidang = Bidang::all();
        $jabatan = Jabatan::all();

        return view('admin.pegawai.create', compact('pangkat', 'bidang', 'jabatan'));
    }

    public function store(StorePegawaiRequest $request)
    {
        $validated = $request->validated();

        $validated['pangkat'] = Pangkat::where('golongan', $validated['gol_ruang'])->value('nama_pangkat');

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = $validated['nip'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('foto_pegawai', $file, $filename);
            $validated['foto'] = $filename;
        }

        Pegawai::create($validated);
        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pangkat = Pangkat::all();
        $bidang = Bidang::all();
        $jabatan = Jabatan::all();

        return view('admin.pegawai.edit', compact('pegawai', 'pangkat', 'bidang', 'jabatan'));
    }

    public function update(UpdatePegawaiRequest $request, $id)
    {
        $validated = $request->validated();
        $pegawai = Pegawai::findOrFail($id);

        $validated['pangkat'] = Pangkat::where('golongan', $validated['gol_ruang'])->value('nama_pangkat');

        if ($request->hasFile('foto')) {

            if ($pegawai->foto && Storage::disk('public')->exists('foto-pegawai/' . $pegawai->foto)) {
                Storage::disk('public')->delete('foto-pegawai/' . $pegawai->foto);
            }

            $file = $request->file('foto');
            $filename = $validated['nip'] . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('foto-pegawai', $filename, 'public');

            $validated['foto'] = $filename;
        }

        $pegawai->update($validated);

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diubah.');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        if ($pegawai->foto && Storage::disk('public')->exists('foto-pegawai/' . $pegawai->foto)) {
            Storage::disk('public')->delete('foto-pegawai/' . $pegawai->foto);
        }

        $pegawai->delete();

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }

    public function showFile($filename)
    {
        $path = "public/foto-pegawai/{$filename}";
        if (!Storage::exists($path)) abort(404);

        return Storage::response($path);
    }
}
