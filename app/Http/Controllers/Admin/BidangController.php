<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BidangController extends Controller
{
    /**
     * Menampilkan daftar master bidang/sektor kerja (Standalone UI)
     */
    public function index()
    {
        $bidang = Bidang::orderBy('nama_bidang', 'asc')->get();

        return Inertia::render('admin/master/bidang/index', [
            'bidang' => $bidang
        ]);
    }

    /**
     * Menyimpan data master bidang baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bidang' => ['required', 'string', 'max:255', 'unique:bidang,nama_bidang'],
            'kode_bidang' => ['nullable', 'string', 'max:50', 'unique:bidang,kode_bidang'],
        ], [
            'nama_bidang.required' => 'Nama bidang atau divisi kerja wajib diisi.',
            'nama_bidang.unique' => 'Nama bidang atau divisi tersebut sudah ada.',
            'kode_bidang.unique' => 'Kode bidang sudah digunakan oleh divisi lain.',
        ]);

        Bidang::create($validated);

        return redirect()->back()->with('flash', [
            'type' => 'success',
            'message' => 'Data master bidang baru berhasil ditambahkan.'
        ]);
    }

    /**
     * Memperbarui data master bidang yang dipilih
     */
    public function update(Request $request, $id)
    {
        $bidang = Bidang::findOrFail($id);

        $validated = $request->validate([
            'nama_bidang' => ['required', 'string', 'max:255', 'unique:bidang,nama_bidang,' . $id],
            'kode_bidang' => ['nullable', 'string', 'max:50', 'unique:bidang,kode_bidang,' . $id],
        ], [
            'nama_bidang.required' => 'Nama bidang atau divisi kerja wajib diisi.',
            'nama_bidang.unique' => 'Nama bidang atau divisi tersebut sudah ada.',
            'kode_bidang.unique' => 'Kode bidang sudah digunakan oleh divisi lain.',
        ]);

        $bidang->update($validated);

        return redirect()->back()->with('flash', [
            'type' => 'success',
            'message' => 'Data master bidang berhasil diperbarui.'
        ]);
    }

    /**
     * Menghapus data master bidang dari sistem
     */
    public function destroy($id)
    {
        $bidang = Bidang::findOrFail($id);
        $bidang->delete();

        return redirect()->back()->with('flash', [
            'type' => 'success',
            'message' => 'Data master bidang berhasil dihapus dari sistem.'
        ]);
    }
}
