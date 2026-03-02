<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Pangkat;
use Illuminate\Http\Request;

class PangkatController extends Controller
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
        return view('settings.form.pangkat');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pangkat' => 'required',
            'golongan' => 'required',
        ], [
            'reqeuired' => ':attribute harus diisi.',
        ], [
            'nama_pangkat' => 'Nama Pangkat',
            'golongan' => 'Golongan',
        ]);

        Pangkat::create($validated);
        return redirect(route('settings.index'))->with('success', 'Data pangkat berhasil ditambahkan.');
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
        $pangkat = Pangkat::findOrFail($id);

        return view('settings.form.pangkat', compact('pangkat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_pangkat' => 'required',
            'golongan' => 'required',
        ], [
            'reqeuired' => ':attribute harus diisi.',
        ], [
            'nama_pangkat' => 'Nama Pangkat',
            'golongan' => 'Golongan',
        ]);

        $pangkat = Pangkat::findOrFail($id);
        $pangkat->update($validated);
        return redirect(route('settings.index'))->with('success', 'Data pangkat berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pangkat = Pangkat::findOrFail($id);

        $pangkat->delete();
        return redirect(route('settings.index'))->with('success', 'Data pangkat berhasil dihapus.');
    }
}
