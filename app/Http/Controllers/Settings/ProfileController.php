<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman pengaturan profil terpadu (Akun & Metadata Pegawai).
     */
    public function edit(Request $request): Response
    {
        // Eager load relasi pegawai yang terhubung dengan akun user aktif
        $user = $request->user()->load([
            'pegawai.pangkat',
            'pegawai.jabatan',
            'pegawai.bidang'
        ]);

        return Inertia::render('settings/profile', [
            'mustVerifyEmail' => false, // Disesuaikan dengan kebutuhan arsitektur internal
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Memperbarui informasi akun user dan data profil pegawai secara transaksional.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Pastikan model Pegawai terikat, jika tidak ada buat instansiasi baru untuk mencegah null pointer execution
        $pegawai = $user->pegawai ?: $user->pegawai()->create(['nip' => '000000000000000000']);

        $validated = $request->validate([
            // Validasi Kredensial Akun (users)
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],

            // Refactor Pipeline Validasi NIP (Dapat Diubah Secara Dinamis)
            'nip' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pegawai', 'nip')->ignore($pegawai->id),
            ],

            // Validasi Data Profil Pegawai
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', Rule::in(['l', 'p'])],
            'agama' => ['required', 'string', 'max:50'],
            'kode_pos' => ['required', 'string', 'max:10'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'status_kawin' => ['required', Rule::in(['belum kawin', 'kawin', 'janda', 'duda'])],
            'karpeg' => ['nullable', 'string', 'max:50'],
            'nama_pasangan' => ['nullable', 'required_if:status_kawin,kawin', 'string', 'max:255'],
            'status_kerja_pasangan' => ['nullable', 'required_if:status_kawin,kawin', 'string', 'max:255'],
            'jumlah_anak' => ['nullable', 'integer', 'min:0'],
        ], [
            // Pesan Kustom Bahasa Indonesia Selaras Aturan PRD Internal
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email akun wajib diisi.',
            'email.email' => 'Format alamat email yang Anda masukkan tidak valid.',
            'email.unique' => 'Alamat email tersebut sudah digunakan oleh pengguna lain.',

            'nip.required' => 'Nomor Induk Pegawai (NIP) wajib diisi.',
            'nip.unique' => 'NIP yang Anda masukkan sudah terdaftar pada pegawai lain.',

            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir sesuai dokumen resmi wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',
            'agama.required' => 'Agama wajib diisi.',
            'kode_pos.required' => 'Kode pos domisili wajib diisi.',
            'no_telp.required' => 'Nomor telepon aktif wajib diisi.',
            'alamat.required' => 'Alamat lengkap tempat tinggal wajib diisi.',
            'status_kawin.required' => 'Status perkawinan wajib dipilih.',
            'status_kawin.in' => 'Pilihan status perkawinan tidak valid.',
            'nama_pasangan.required_if' => 'Nama suami/istri wajib diisi jika status Anda kawin.',
            'status_kerja_pasangan.required_if' => 'Status pekerjaan pasangan wajib diisi jika status Anda kawin.',
            'jumlah_anak.integer' => 'Jumlah anak harus berupa angka murni.',
            'jumlah_anak.min' => 'Jumlah anak tidak boleh bernilai negatif.',
        ]);

        // Eksekusi pembaruan database secara transaksional (ACID Compliance)
        DB::transaction(function () use ($user, $pegawai, $validated) {
            $oldNip = $pegawai->nip;
            $newNip = $validated['nip'];

            // 1. Perbarui Data Akun User (Kredensial Dasar)
            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // 2. OTOMATISASI SINKRONISASI AKUN JIKA NIP BERUBAH (Kritikal Arsitektur SCHEMA.md)
            if ($oldNip !== $newNip) {
                // Email Re-generation Blueprint
                $user->email = $newNip . '@simpeg.local';

                // Default Password Update Re-hash
                $user->password = bcrypt('p3a' . $newNip);

                // First-Time Login Reset Flag (Force Password Change Enforcer)
                $user->email_verified_at = null;
            }

            $user->save();

            // 3. Perbarui Atribut Profil Pegawai Terkait
            $pegawai->update([
                'nip' => $newNip,
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'agama' => $validated['agama'],
                'kode_pos' => $validated['kode_pos'],
                'no_telp' => $validated['no_telp'],
                'alamat' => $validated['alamat'],
                'status_kawin' => $validated['status_kawin'],
                'karpeg' => $validated['karpeg'] ?? null,
                'nama_pasangan' => $validated['status_kawin'] === 'kawin' ? ($validated['nama_pasangan'] ?? null) : null,
                'status_kerja_pasangan' => $validated['status_kawin'] === 'kawin' ? ($validated['status_kerja_pasangan'] ?? null) : null,
                'jumlah_anak' => $validated['jumlah_anak'] ?? 0,
            ]);
        });

        // Kembalikan respons disertai payload toast notifikasi sesuai standar PRD internal
        return to_route('profile.edit')->with('flash', [
            'type' => 'success',
            'message' => 'Profil dan data pegawai Anda berhasil diperbarui.'
        ]);
    }

    /**
     * Menghapus akun pengguna secara permanen dari sistem.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Kata sandi konfirmasi wajib diisi untuk menghapus akun.',
            'password.current_password' => 'Kata sandi yang Anda masukkan salah.',
        ]);

        $user = $request->user();

        Auth::logout();

        // Cascade rule pada level SQLite akan otomatis menghapus log data riwayat_* dan record pegawai
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
