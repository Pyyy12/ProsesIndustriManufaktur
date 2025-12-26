<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
// use App\Models\Peminjam; // Jika Anda perlu memuat data yang lebih detail dari DB

class PeminjamProfileController extends Controller
{
    /**
     * Menampilkan halaman profil untuk pengguna Peminjam.
     */
    public function index()
    {
        // Pastikan pengguna adalah peminjam sebelum menampilkan
        if (Session::get('user_type') !== 'peminjam') {
            return redirect()->route('peminjam.dashboard')->with('error', 'Akses ditolak.');
        }

        // Karena semua data yang dibutuhkan ada di Session, kita langsung muat view-nya.
        // Asumsi View disimpan di 'resources/views/profile/index.blade.php'
        return view('profile.index'); 
    }
}