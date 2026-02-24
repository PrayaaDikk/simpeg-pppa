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
    public function index()
    {
        $cuti = Cuti::with(['pegawai', 'penyetuju.pegawai'])
            ->latest()
            ->paginate(10);

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

        $validated['diajukan_oleh'] = $validated['pegawai_id'];
        $validated['disetujui_oleh'] = Auth::user()->pegawai_id;

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
        $validated['disetujui_oleh'] = Auth::user()->pegawai_id;

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
