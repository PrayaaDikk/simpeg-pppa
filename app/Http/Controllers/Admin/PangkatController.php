<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pangkat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PangkatController extends Controller
{
    /**
     * Menampilkan daftar master pangkat dan golongan (Standalone UI)
     */
    public function index()
    {
        $pangkat = Pangkat::orderBy('golongan', 'asc')->get();

        return Inertia::render('admin/master/pangkat/index', [
            'pangkat' => $pangkat
        ]);
    }

    /**
     * Menyimpan data master pangkat baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pangkat' => ['required', 'string', 'max:255'],
            'golongan' => ['required', 'string', 'max:50', 'unique:pangkat,golongan'],
        ], [
            'nama_pangkat.required' => 'Nama pangkat wajib diisi.',
            'golongan.required' => 'Golongan / ruang wajib diisi.',
            'golongan.unique' => 'Kode golongan / ruang ini sudah terdaftar dalam sistem.',
        ]);

        Pangkat::create($validated);

        return redirect()->back()->with('flash', [
            'type' => 'success',
            'message' => 'Data master pangkat baru berhasil ditambahkan.'
        ]);
    }

    /**
     * Memperbarui data master pangkat yang dipilih
     */
    public function update(Request $request, $id)
    {
        $pangkat = Pangkat::findOrFail($id);

        $validated = $request->validate([
            'nama_pangkat' => ['required', 'string', 'max:255'],
            'golongan' => ['required', 'string', 'max:50', 'unique:pangkat,golongan,' . $id],
        ], [
            'nama_pangkat.required' => 'Nama pangkat wajib diisi.',
            'golongan.required' => 'Golongan / ruang wajib diisi.',
            'golongan.unique' => 'Kode golongan / ruang ini sudah terdaftar dalam sistem.',
        ]);

        $pangkat->update($validated);

        return redirect()->back()->with('flash', [
            'type' => 'success',
            'message' => 'Data master pangkat berhasil diperbarui.'
        ]);
    }

    /**
     * Menghapus data master pangkat dari sistem
     */
    public function destroy($id)
    {
        $pangkat = Pangkat::findOrFail($id);

        // Aturan Cascading: Memastikan integritas data pegawai tetap terjaga
        $pangkat->delete();

        return redirect()->back()->with('flash', [
            'type' => 'success',
            'message' => 'Data master pangkat berhasil dihapus dari sistem.'
        ]);
    }
}
