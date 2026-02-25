<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kgb;
use App\Models\Pangkat;

class KgbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pegawaiId = 1;

        $kgb = Kgb::where('pegawai_id', $pegawaiId)->get();

        return view('users.kgb.index', compact('kgb'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    $golonganOptions = Pangkat::orderBy('golongan')
        ->get()
        ->map(function ($item) {
            return [
                'value' => $item->golongan,
                'label' => $item->golongan
            ];
        });

    return view('users.kgb.create', compact('golonganOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_sk' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',

            'gaji_lama' => 'required|numeric|min:0',
            'tmt_gaji_lama' => 'required|date',

            'gaji_baru' => 'required|numeric|min:0',
            'tmt_kgb' => 'required|date',

            'kgb_berikutnya' => 'required|date',

            'masa_kerja_lama' => 'nullable|string|max:50',
            'masa_kerja_baru' => 'nullable|string|max:50',

            'golongan_lama' => 'nullable|string|max:10',
            'golongan_baru' => 'required|string|max:10',

            'pejabat_penetap' => 'required|string|max:150',
            'nip_pejabat' => 'required|string|max:30',

            'file_sk' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Upload file jika ada
        if ($request->hasFile('file_sk')) {
            $filePath = $request->file('file_sk')->store('kgb', 'public');
            $validated['file_sk'] = $filePath;
        }

        // Set field otomatis
        $validated['pegawai_id'] = 1; // dummy sementara
        $validated['status_kgb'] = 'diajukan';
        $validated['diajukan_oleh'] = 1;

        // Simpan ke database
        Kgb::create($validated);

        return redirect()
            ->route('kgb.index')
            ->with('success', 'Pengajuan KGB berhasil diajukan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kgb = Kgb::where('pegawai_id', 1)->findOrFail($id);

        return view('users.kgb.show', compact('kgb'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ambil data KGB milik pegawai (sementara dummy pegawai_id = 1)
        $kgb = Kgb::where('pegawai_id', 1)->findOrFail($id);

        
        if ($kgb->status_kgb !== 'ditolak') {
            return redirect()
                ->route('kgb.index')
                ->with('error', 'Data KGB tidak dapat diedit.');
        }

        $golonganOptions = Pangkat::orderBy('golongan')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->golongan,
                    'label' => $item->golongan
                ];
            });

        return view('users.kgb.edit', compact('kgb', 'golonganOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
