<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string $role  // Parameter tambahan dari route, misalnya: 'plp' atau 'peminjam'
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek Apakah Pengguna Sudah Login
        // Kita menggunakan Session yang dibuat di AuthController (user_type)
        if (!session()->has('user_type')) {
            // Jika sesi user_type tidak ada, berarti pengguna belum login
            
            // Redirect ke halaman login dengan pesan error
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melanjutkan.');
        }

        // 2. Cek Hak Akses (Role)
        // Bandingkan 'user_type' di sesi dengan '$role' yang didefinisikan di route
        if (session('user_type') != $role) {
            // Jika tipe user di sesi TIDAK cocok dengan role yang diperlukan oleh route
            
            // Redirect ke halaman beranda (atau dashboard mereka) dengan pesan error
            return redirect('/')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        }

        // Jika semua verifikasi berhasil (sudah login dan memiliki role yang benar)
        return $next($request);
    }
}