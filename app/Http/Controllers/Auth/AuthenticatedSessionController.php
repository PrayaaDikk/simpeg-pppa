<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login utama.
     */
    public function index()
    {
        return Inertia::render('auth/login', [
            'status' => session('status'),
        ]);
    }

    /**
     * Proses verifikasi kredensial masuk (Login Store).
     */
    public function store(Request $request)
    {
        // 1. Validasi awal format parameter input form
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ], [
            'login_id.required' => 'Kolom Email atau NIP wajib diisi.',
            'password.required' => 'Kolom password wajib diisi.',
        ]);

        $loginId = $request->input('login_id');
        $password = $request->input('password');

        // 2. Brute-Force Protection: Membuat signature unik berdasarkan input identifier dan IP address
        $throttleKey = Str::transliterate(Str::lower($loginId) . '|' . $request->ip());

        // Membatasi maksimum 5 kali percobaan dalam 1 menit (60 detik)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'login_id' => ["Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik."],
            ]);
        }

        $user = null;

        // 3. Dual-Identifier Resolution Logic (Aman dari SQL Injection menggunakan Query Builder / Eloquent)
        if (filter_var($loginId, FILTER_VALIDATE_EMAIL)) {
            // Case A: Input berupa format Email, cari langsung ke tabel 'users'
            $user = User::where('email', $loginId)->first();
        } else {
            // Case B: Input berupa NIP, cari record di tabel singular 'pegawai'
            $pegawai = Pegawai::where('nip', $loginId)->first();

            if ($pegawai) {
                // Resolusi ke parent relation model 'User'
                $user = $pegawai->user;
            }
        }

        // 4. Verifikasi kecocokan data pengguna dan enkripsi hash password
        if (!$user || !Hash::check($password, $user->password)) {
            // Catat kegagalan untuk akumulasi hit rate limiter
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'login_id' => ['Kredensial yang Anda masukkan salah. Silakan periksa kembali Email/NIP dan password Anda.'],
            ]);
        }

        // Jika berhasil, bersihkan rekam jejak percobaan rate limiter
        RateLimiter::clear($throttleKey);

        // 5. Autentikasikan pengguna ke dalam sistem state session guard
        Auth::login($user, $request->boolean('remember'));

        // 6. Keamanan Tambahan: Regenerasi ID Sesi untuk memblokir serangan Session-Fixation
        $request->session()->regenerate();

        // 7. Pengalihan cerdas berdasarkan gerbang keamanan login pertama kali
        if ($user->hasRole('pegawai') && is_null($user->email_verified_at)) {
            return redirect()->intended('/settings/security')->with('flash', [
                'type' => 'warning',
                'message' => 'Ini adalah login pertama Anda. Demi keamanan, Anda diwajibkan untuk mengubah password default Anda sebelum mengakses dashboard SIMPEG.'
            ]);
        }

        // Pengalihan default bagi yang sudah lolos aktivasi / memiliki hak akses Admin
        return redirect()->intended('/dashboard')->with('flash', [
            'type' => 'success',
            'message' => 'Selamat datang kembali di SIMPEG, ' . $user->name . '!'
        ]);
    }

    /**
     * Proses pengakhiran sesi pengguna (Logout Destroy).
     */
    public function destroy(Request $request)
    {
        // Eksekusi pemutusan sesi autentikasi global
        Auth::logout();

        // Hancurkan data state yang melekat pada session driver server-side
        $request->session()->invalidate();

        // Regenerasi token CSRF baru untuk mencegah serangan Cross-Site Request Forgery pasca-logout
        $request->session()->regenerateToken();

        // Kembalikan pengguna ke gerbang awal halaman login
        return redirect('/login')->with('flash', [
            'type' => 'success',
            'message' => 'Anda telah berhasil keluar dari sistem SIMPEG.'
        ]);
    }
}
