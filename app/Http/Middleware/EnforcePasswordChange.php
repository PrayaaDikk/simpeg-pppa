<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Periksa apakah pengguna telah terautentikasi
        if ($user) {
            // Evaluasi role 'pegawai' dan status email_verified_at yang masih NULL
            if ($user->hasRole('pegawai') && is_null($user->email_verified_at)) {

                // Izinkan akses jika mereka memang sedang menuju rute perubahan password atau rute logout
                if ($request->is('settings/security') || $request->is('logout') || $request->routeIs('password.update')) {
                    return $next($request);
                }

                // Intersept navigasi dan arahkan ke halaman ubah password dengan pesan flash peringatan
                return redirect('/settings/security')->with('flash', [
                    'type' => 'warning',
                    'message' => 'Ini adalah login pertama Anda. Demi keamanan, Anda diwajibkan untuk mengubah password default Anda sebelum mengakses dashboard SIMPEG.'
                ]);
            }
        }

        return $next($request);
    }
}
