<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRiwayatJabatanRequest;
use App\Http\Requests\UpdateRiwayatJabatanRequest;
use App\Models\RiwayatJabatan;
use Illuminate\Support\Facades\Storage;

class RiwayatJabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($pegawaiId)
    {
        if (auth()->user()->role !== 'admin') {
            $pegawaiId = auth()->user()->id;
        }

        $riwayatJabatan = RiwayatJabatan::where('pegawai_id', $pegawaiId)->get();

        return view('riwayat-jabatan.index', compact('riwayatJabatan', 'pegawaiId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($pegawaiId)
    {
        if (auth()->user()->role !== 'admin') {
            $pegawaiId = auth()->user()->id;
        }

        return view('riwayat-jabatan.create', compact('pegawaiId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRiwayatJabatanRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('file_sk')) {
            $file = $request->file('file_sk');
            $filename = $validated['pegawai_id'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('sk-jabatan', $file, $filename);
            $validated['file_sk'] = $filename;
        }

        RiwayatJabatan::create($validated);
        return redirect()->route(auth()->user()->routePrefix() . 'riwayat-jabatan.index', $validated['pegawai_id'])->with('success', 'Data riwayat jabatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $riwayatJabatan = RiwayatJabatan::with('pegawai')->findOrFail($id);

        return view('riwayat-jabatan.show', compact('riwayatJabatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $riwayatJabatan = RiwayatJabatan::findOrFail($id);

        return view('riwayat-jabatan.edit', compact('riwayatJabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRiwayatJabatanRequest $request, $id)
    {
        $riwayatJabatan = RiwayatJabatan::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('file_sk')) {

            if ($riwayatJabatan->file_sk && Storage::disk('public')->exists('sk-jabatan/' . $riwayatJabatan->file_sk)) {
                Storage::disk('public')->delete('sk-jabatan/' . $riwayatJabatan->file_sk);
            }

            $file = $request->file('file_sk');
            $filename = $riwayatJabatan->pegawai_id . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('sk-jabatan', $filename, 'public');

            $validated['file_sk'] = $filename;
        }

        $riwayatJabatan->update($validated);

        return redirect()
            ->route(auth()->user()->routePrefix() . 'riwayat-jabatan.index', $riwayatJabatan->pegawai_id)
            ->with('success', 'Data riwayat jabatan berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $riwayatJabatan = RiwayatJabatan::findOrFail($id);

        if ($riwayatJabatan->file_sk && Storage::disk('public')->exists('sk-jabatan/' . $riwayatJabatan->file_sk)) {
            Storage::disk('public')->delete('sk-jabatan/' . $riwayatJabatan->file_sk);
        }

        $riwayatJabatan->delete();

        return redirect()->route(auth()->user()->routePrefix() . 'riwayat-jabatan.index', $riwayatJabatan->pegawai_id)->with('success', 'Data riwayat jabatan berhasil dihapus.');
    }

    public function showFile($filename)
    {
        $path = "public/sk-jabatan/{$filename}";
        if (!Storage::exists($path)) abort(404);

        return Storage::response($path);
    }
}
