<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Pastikan Carbon di-import
use Inertia\Inertia;

class CutiPegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with(['pangkat', 'jabatan', 'bidang'])->where('user_id', Auth::id())->firstOrFail();

        $riwayatCuti = Cuti::with(['pegawai.pangkat', 'pegawai.jabatan', 'atasan'])
            ->where('pegawai_id', $pegawai->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('pegawai/cuti/index', [
            'pegawai' => $pegawai,
            'riwayatCuti' => $riwayatCuti
        ]);
    }

    public function store(Request $request)
    {
        $pegawai = Pegawai::where('user_id', Auth::id())->firstOrFail();

        // Validasi input awal (Kecuali lama_cuti karena dihitung backend)
        $validated = $request->validate([
            'jenis_cuti' => 'required|in:tahunan,besar,sakit,melahirkan,alasan penting,diluar tanggungan negara',
            'alasan_cuti' => 'required|string|min:10|max:500',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat' => 'required|string|min:10|max:255',
            'no_telp' => 'required|string|min:10|max:15',
        ], [
            'jenis_cuti.required' => 'Jenis cuti wajib dipilih salah satu.',
            'jenis_cuti.in' => 'Pilihan jenis cuti yang dimasukkan tidak valid.',
            'alasan_cuti.required' => 'Alasan pengajuan cuti wajib diisi secara jelas.',
            'alasan_cuti.min' => 'Alasan pengajuan cuti minimal harus sepanjang 10 karakter.',
            'tanggal_mulai.required' => 'Tanggal awal pelaksanaan cuti wajib ditentukan.',
            'tanggal_mulai.after_or_equal' => 'Tanggal awal cuti tidak boleh mendahului tanggal hari ini.',
            'tanggal_akhir.required' => 'Tanggal batas akhir pelaksanaan cuti wajib ditentukan.',
            'tanggal_akhir.after_or_equal' => 'Tanggal batas akhir tidak boleh sebelum dari tanggal awal pelaksanaan cuti.',
            'alamat.required' => 'Alamat lengkap korespondensi selama masa cuti berlangsung wajib diisi.',
            'no_telp.required' => 'Nomor telepon aktif yang dapat dihubungi wajib diisi.',
        ]);

        // Kalkulasi Mandiri Otomatis Sisi Server (Menggunakan Selisih Hari + 1)
        $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
        $tanggalAkhir = Carbon::parse($validated['tanggal_akhir']);
        $lamaCuti = $tanggalMulai->diffInDays($tanggalAkhir) + 1;

        // Penyimpanan Entitas Data Berkas Baru
        Cuti::create([
            'pegawai_id' => $pegawai->id,
            'jenis_cuti' => $validated['jenis_cuti'],
            'alasan_cuti' => $validated['alasan_cuti'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_akhir' => $validated['tanggal_akhir'],
            'lama_cuti' => $lamaCuti, // Menggunakan nilai kalkulasi server yang steril
            'alamat' => $validated['alamat'],
            'no_telp' => $validated['no_telp'],
            'keputusan_atasan' => 'menunggu',
            'status_cuti' => 'menunggu',
            'atasan_id' => null,
        ]);

        return redirect()->back()->with('flash', [
            'type' => 'success',
            'message' => 'Pengajuan cuti Anda berhasil dikirim dan otomatis terhitung selama ' . $lamaCuti . ' hari.'
        ]);
    }
}
