<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bidang = Bidang::all();

        return view('settings.form.jabatan', compact('bidang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required',
            'bidang_id' => 'nullable|exists:bidang,id',
        ], [
            'required' => ':attribute harus diisi.',
            'exists' => ':attribute tidak valid.',
        ], [
            'nama_jabatan' => 'Nama Jabatan',
            'bidang_id' => 'Bidang',
        ]);

        $validated['is_singleton'] = $request->boolean('is_singleton');

        Jabatan::create($validated);
        return redirect(route('settings.index'))->with('success', 'Data jabatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jabatan = Jabatan::with('bidang')->findOrFail($id);
        $bidang = Bidang::all();

        return view('settings.form.jabatan', compact('jabatan', 'bidang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required',
            'bidang_id' => 'nullable|exists:bidang,id',
        ], [
            'required' => ':attribute harus diisi.',
            'exists' => ':attribute tidak valid.',
        ], [
            'nama_jabatan' => 'Nama Jabatan',
            'bidang_id' => 'Bidang',
        ]);

        $validated['is_singleton'] = $request->boolean('is_singleton');

        Jabatan::where('id', $id)->update($validated);
        return redirect(route('settings.index'))->with('success', 'Data jabatan berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Jabatan::where('id', $id)->delete();
        return redirect(route('settings.index'))->with('success', 'Data jabatan berhasil dihapus.');
    }
}
