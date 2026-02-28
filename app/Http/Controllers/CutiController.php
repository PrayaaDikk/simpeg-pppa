<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCutiRequest;
use App\Http\Requests\UpdateCutiRequest;
use App\Models\Cuti;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cuti::with(['pegawai']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jenis_cuti', 'LIKE', "%{$search}%")
                    ->orWhereHas('pegawai', function ($subQuery) use ($search) {
                        $subQuery->where('nama', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->filled('jenis_cuti')) {
            $query->whereIn('jenis_cuti', $request->jenis_cuti);
        }

        if ($request->filled('status_cuti')) {
            $query->whereIn('status_cuti', $request->status_cuti);
        }

        $cuti = $query->paginate(10)->withQueryString();

        return view('admin.cuti.index', compact('cuti'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($pegawaiId)
    {
        $pegawai = Pegawai::find($pegawaiId);

        return view('admin.cuti.create', compact('pegawai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCutiRequest $request)
    {
        $validated = $request->validated();
        $pegawai = Pegawai::findOrFail($validated['pegawai_id']);

        // Hitung durasi (Sama dengan logika di Model: selisih hari + 1)
        $tglMulai = Carbon::parse($validated['tanggal_mulai']);
        $tglSelesai = Carbon::parse($validated['tanggal_selesai']);
        $durasiDiajukan = $tglMulai->diffInDays($tglSelesai) + 1;

        // Validasi kuota
        if ($durasiDiajukan > $pegawai->kuota_cuti) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => "Gagal! Durasi cuti ($durasiDiajukan hari) melebihi sisa kuota ({$pegawai->kuota_cuti} hari)."]);
        }

        $validated['diajukan_oleh'] = $validated['pegawai_id'];
        Cuti::create($validated);

        return redirect()->route('admin.cuti.index')->with('success', 'Data cuti berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cuti = Cuti::with('pegawai')->findOrFail($id);

        return view('admin.cuti.show', compact('cuti'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cuti = Cuti::with('pegawai')->findOrFail($id);

        return view('admin.cuti.edit', compact('cuti'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCutiRequest $request, $id)
    {
        $validated = $request->validated();

        $validated['diajukan_oleh'] = $validated['pegawai_id'];
        $cuti = Cuti::findOrFail($id);

        $cuti->update($validated);

        return redirect()->route('admin.cuti.index')->with('success', 'Data cuti berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cuti = Cuti::findOrFail($id);

        $cuti->delete();

        return redirect()->route('admin.cuti.index')->with('success', 'Data cuti berhasil dihapus.');
    }
}
