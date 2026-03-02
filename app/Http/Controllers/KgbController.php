<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKgbRequest;
use App\Http\Requests\UpdateKgbRequest;
use App\Models\Kgb;
use App\Models\Pangkat;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;

class KgbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kgb::with(['pegawai']);

        if (auth()->user()->role !== 'admin') {
            $query->where('pegawai_id', auth()->user()->pegawai_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'LIKE', "%{$search}%")
                    ->orWhereHas('pegawai', function ($subQuery) use ($search) {
                        $subQuery->where('nama', 'LIKE', "%{$search}%")
                            ->orWhere('nip', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status_kgb')) {
            $query->whereIn('status_kgb', $request->status_kgb);
        }

        $kgb = $query->latest()->paginate(10)->withQueryString();

        return view('kgb.index', compact('kgb'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($pegawaiId)
    {
        if (auth()->user()->role !== 'admin') {
            $pegawaiId = auth()->user()->pegawai_id;
        }

        $pangkat = Pangkat::all();
        $pegawai = Pegawai::findOrFail($pegawaiId);

        return view('kgb.create', compact('pegawai', 'pangkat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKgbRequest $request)
    {
        $validated = $request->validated();
        $validated['diajukan_oleh'] = $validated['pegawai_id'];
        $validated['disetujui_oleh'] = User::where('role', 'admin')->first()->pegawai_id;

        Kgb::create($validated);
        return redirect()->route(auth()->user()->routePrefix() . 'kgb.index')->with('success', 'Data kgb berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kgb = Kgb::with(['pegawai', 'pegawai.jabatan'])->findOrFail($id);

        return view('kgb.show', compact('kgb'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kgb = Kgb::with(['pegawai', 'pegawai.jabatan'])->findOrFail($id);
        $pangkat = Pangkat::all();

        return view('kgb.edit', compact('kgb', 'pangkat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKgbRequest $request, $id)
    {
        $validated = $request->validated();

        $validated['diajukan_oleh'] = $validated['pegawai_id'];
        $validated['disetujui_oleh'] = auth()->user()->id;

        $kgb = Kgb::findOrFail($id);

        $kgb->update($validated);
        return redirect()->route(auth()->user()->routePrefix() . 'kgb.index')->with('success', 'Data kgb berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kgb = Kgb::findOrFail($id);

        $kgb->delete();

        return redirect()->route(auth()->user()->routePrefix() . 'kgb.index')->with('success', 'Data kgb berhasil dihapus.');
    }
}
