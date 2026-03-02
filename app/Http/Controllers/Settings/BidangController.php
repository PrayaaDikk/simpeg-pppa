<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use Illuminate\Http\Request;

class BidangController extends Controller
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
        return view('settings.form.bidang');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bidang' => 'required',
            'akronim' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
        ], [
            'nama_bidang' => 'Nama Bidang',
            'akronim' => 'Akronim',
        ]);

        Bidang::create($validated);
        return redirect(route('settings.index'))->with('success', 'Data bidang berhasil ditambahkan.');
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
        $bidang = Bidang::findOrFail($id);
        return view('settings.form.bidang', compact('bidang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_bidang' => 'required',
            'akronim' => 'required',
        ], [
            'required' => ':attribute harus diisi.',
        ], [
            'nama_bidang' => 'Nama Bidang',
            'akronim' => 'Akronim',
        ]);

        $bidang = Bidang::findOrFail($id);
        $bidang->update($validated);
        return redirect(route('settings.index'))->with('success', 'Data bidang berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bidang = Bidang::findOrFail($id);
        $bidang->delete();
        return redirect(route('settings.index'))->with('success', 'Data bidang berhasil dihapus.');
    }
}
