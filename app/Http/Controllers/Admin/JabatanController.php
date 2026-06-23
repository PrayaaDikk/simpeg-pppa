<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JabatanController extends Controller
{
    /**
     * Menampilkan daftar master jabatan struktur/fungsional (Standalone UI)
     */
    public function index()
    {
        $jabatan = Jabatan::orderBy('nama_jabatan', 'asc')->get();

        return Inertia::render('admin/master/jabatan/index', [
            'jabatan' => $jabatan
        ]);
    }

    /**
     * Menyimpan data master jabatan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255', 'unique:jabatan,nama_jabatan'],
            'is_singleton' => ['required', 'boolean'],
        ], [
            'nama_jabatan.required' => 'Nama jabatan resmi instansi wajib diisi.',
            'nama_jabatan.unique' => 'Nama jabatan tersebut sudah terdaftar dalam sistem.',
            'is_singleton.required' => 'Status kuota jabatan (singleton) wajib ditentukan.',
        ]);

        Jabatan::create($validated);

        return redirect()->back()->with('flash', [
            'type' => 'success',
            'message' => 'Data master jabatan baru berhasil ditambahkan.'
        ]);
    }

    /**
     * Memperbarui data master jabatan yang dipilih
     */
    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255', 'unique:jabatan,nama_jabatan,' . $id],
            'is_singleton' => ['required', 'boolean'],
        ], [
            'nama_jabatan.required' => 'Nama jabatan resmi instansi wajib diisi.',
            'nama_jabatan.unique' => 'Nama jabatan tersebut sudah terdaftar dalam sistem.',
            'is_singleton.required' => 'Status kuota jabatan (singleton) wajib ditentukan.',
        ]);

        $jabatan->update($validated);

        return redirect()->back()->with('flash', [
            'type' => 'success',
            'message' => 'Data master jabatan berhasil diperbarui.'
        ]);
    }

    /**
     * Menghapus data master jabatan dari sistem
     */
    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);

        // Catatan: Jika di kemudian hari ada relasi ke tabel pegawai, 
        // pastikan melakukan pengecekan apakah jabatan sedang digunakan sebelum dihapus.
        $jabatan->delete();

        return redirect()->back()->with('flash', [
            'type' => 'success',
            'message' => 'Data master jabatan berhasil dihapus dari sistem.'
        ]);
    }
}
