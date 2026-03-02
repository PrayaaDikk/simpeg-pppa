<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRiwayatKepangkatanRequest;
use App\Http\Requests\UpdateRiwayatKepangkatanRequest;
use App\Models\Pangkat;
use App\Models\RiwayatKepangkatan;
use Illuminate\Support\Facades\Storage;

class RiwayatKepangkatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($pegawaiId)
    {
        if (auth()->user()->role !== 'admin') {
            $pegawaiId = auth()->user()->id;
        }

        $riwayatPangkat = RiwayatKepangkatan::where('pegawai_id', $pegawaiId)->get();

        return view('riwayat-pangkat.index', compact('riwayatPangkat', 'pegawaiId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($pegawaiId)
    {
        if (auth()->user()->role !== 'admin') {
            $pegawaiId = auth()->user()->id;
        }

        $pangkat = Pangkat::all();

        return view('riwayat-pangkat.create', compact('pegawaiId', 'pangkat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRiwayatKepangkatanRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('file_sk')) {
            $file = $request->file('file_sk');
            $filename = $validated['pegawai_id'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('sk-pangkat', $file, $filename);
            $validated['file_sk'] = $filename;
        }

        RiwayatKepangkatan::create($validated);
        return redirect()->route(auth()->user()->routePrefix() . 'riwayat-pangkat.index', $validated['pegawai_id'])->with('success', 'Data riwayat pangkat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $riwayatPangkat = RiwayatKepangkatan::with('pegawai')->findOrFail($id);

        return view('riwayat-pangkat.show', compact('riwayatPangkat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $riwayatPangkat = RiwayatKepangkatan::findOrFail($id);
        $pangkat = Pangkat::all();

        return view('riwayat-pangkat.edit', compact('riwayatPangkat', 'pangkat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRiwayatKepangkatanRequest $request, $id)
    {
        $riwayatPangkat = RiwayatKepangkatan::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('file_sk')) {

            if ($riwayatPangkat->file_sk && Storage::disk('public')->exists('sk-pangkat/' . $riwayatPangkat->file_sk)) {
                Storage::disk('public')->delete('sk-pangkat/' . $riwayatPangkat->file_sk);
            }

            $file = $request->file('file_sk');
            $filename = $riwayatPangkat->pegawai_id . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('sk-pangkat', $filename, 'public');

            $validated['file_sk'] = $filename;
        }

        $riwayatPangkat->update($validated);

        return redirect()
            ->route(auth()->user()->routePrefix() . 'riwayat-pangkat.index', $riwayatPangkat->pegawai_id)
            ->with('success', 'Data riwayat pangkat berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $riwayatPangkat = RiwayatKepangkatan::findOrFail($id);

        if ($riwayatPangkat->file_sk && Storage::disk('public')->exists('sk-pangkat/' . $riwayatPangkat->file_sk)) {
            Storage::disk('public')->delete('sk-pangkat/' . $riwayatPangkat->file_sk);
        }

        $riwayatPangkat->delete();

        return redirect()->route(auth()->user()->routePrefix() . 'riwayat-pangkat.index', $riwayatPangkat->pegawai_id)->with('success', 'Data riwayat pangkat berhasil dihapus.');
    }

    public function showFile($filename)
    {
        $path = "public/sk-pangkat/{$filename}";
        if (!Storage::exists($path)) abort(404);

        return Storage::response($path);
    }
}
