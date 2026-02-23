<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRiwayatPendidikanRequest;
use App\Http\Requests\UpdateRiwayatPendidikanRequest;
use App\Models\RiwayatPendidikan;
use Illuminate\Support\Facades\Storage;

class RiwayatPendidikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($pegawaiId)
    {
        $riwayatPendidikan = RiwayatPendidikan::where('pegawai_id', $pegawaiId)->orderBy('level_pendidikan', 'asc')->get();

        return view('admin.riwayat-pendidikan.index', compact('riwayatPendidikan', 'pegawaiId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($pegawaiId)
    {
        return view('admin.riwayat-pendidikan.create', compact('pegawaiId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRiwayatPendidikanRequest $request, $pegawaiId)
    {
        $validated = $request->validated();
        $validated['pegawai_id'] = $pegawaiId;

        if ($request->hasFile('ijazah')) {
            $file = $request->file('ijazah');
            $filename = $validated['pegawai_id'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('ijazah', $file, $filename);
            $validated['ijazah'] = $filename;
        }

        RiwayatPendidikan::create($validated);
        return redirect()->route('admin.riwayat-pendidikan.index', $validated['pegawai_id'])->with('success', 'Data riwayat pendidikan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $riwayatPendidikan = RiwayatPendidikan::with('pegawai')->findOrFail($id);

        return view('admin.riwayat-pendidikan.show', compact('riwayatPendidikan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $riwayatPendidikan = RiwayatPendidikan::findOrFail($id);

        return view('admin.riwayat-pendidikan.edit', compact('riwayatPendidikan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRiwayatPendidikanRequest $request,  $id)
    {
        $validated = $request->validated();
        $riwayatPendidikan = RiwayatPendidikan::findOrFail($id);

        if ($request->hasFile('ijazah')) {

            if ($riwayatPendidikan->ijazah && Storage::disk('public')->exists('ijazah/' . $riwayatPendidikan->ijazah)) {
                Storage::disk('public')->delete('ijazah/' . $riwayatPendidikan->ijazah);
            }

            $file = $request->file('ijazah');
            $filename = $riwayatPendidikan->pegawai_id . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('ijazah', $filename, 'public');

            $validated['ijazah'] = $filename;
        }

        $riwayatPendidikan->update($validated);

        return redirect()->route('admin.riwayat-pendidikan.index', $riwayatPendidikan->pegawai_id)->with('success', 'Data riwayat pendidikan berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $riwayatPendidikan = RiwayatPendidikan::findOrFail($id);

        if ($riwayatPendidikan->ijazah && Storage::disk('public')->exists('ijazah/' . $riwayatPendidikan->ijazah)) {
            Storage::disk('public')->delete('ijazah/' . $riwayatPendidikan->ijazah);
        }

        $riwayatPendidikan->delete();

        return redirect()->route('admin.riwayat-pendidikan.index', $riwayatPendidikan->pegawai_id)->with('success', 'Data riwayat pendidikan berhasil dihapus.');
    }

    public function showFile($filename)
    {
        $path = "public/ijazah/{$filename}";
        if (!Storage::exists($path)) abort(404);

        return Storage::response($path);
    }
}
