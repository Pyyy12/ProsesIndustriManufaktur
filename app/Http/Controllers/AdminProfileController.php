<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminProfileController extends Controller
{
    /**
     * Menampilkan halaman profil untuk pengguna PLP (Admin).
     */
    public function index()
    {
        // Verifikasi dasar peran
        if (Session::get('user_type') !== 'plp') {
            // Arahkan ke home/dashboard admin
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Halaman ini hanya untuk Admin/PLP.');
        }

        // Memuat view 'profile.index' yang Anda sediakan.
        // Asumsi view disimpan di 'resources/views/profile/index.blade.php'
        return view('profile.index'); 
    }
}