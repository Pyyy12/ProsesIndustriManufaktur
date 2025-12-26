<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjam;
use App\Models\Plp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm()
    {
        // Redirect ke dashboard jika user sudah login
        if (Session::has('user_type')) {
            return Session::get('user_type') === 'plp' 
                ? redirect()->route('admin.dashboard')
                : redirect()->route('peminjam.dashboard');
        }
        return view('auth.login');
    }

    /**
     * Proses otentikasi (login) pengguna.
     */
    public function login(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'username' => 'required|string|max:20', 
            'tgl_lahir' => 'required|date', 
        ], [
            'username.required' => 'NIM/NIP wajib diisi.',
            'tgl_lahir.required' => 'Tanggal Lahir wajib diisi.',
            'tgl_lahir.date' => 'Format Tanggal Lahir tidak valid.'
        ]);

        $username = $request->input('username');
        $tgl_lahir_db_format = Carbon::parse($request->input('tgl_lahir'))->format('Y-m-d'); 
        
        // --- 2. Coba Login sebagai PLP (Admin) ---
        $plp = Plp::where('nip', $username)
                  ->where('tgl_lahir', $tgl_lahir_db_format)
                  ->first();

        if ($plp) {
            // Hapus sesi lama, regenerasi, lalu set sesi baru
            Session::flush(); 
            $request->session()->regenerate();
            
            Session::put([
                'user_type' => 'plp', 
                'user_id' => $plp->nip, 
                'user_name' => $plp->nama,
            ]);
            
            // NOTIFIKASI PERAN saat berhasil login (Ditampilkan di Dashboard)
            Session::flash('success', 'Anda berhasil masuk! Anda login sebagai **Admin/PLP**.');

            return redirect()->route('admin.dashboard');
        }

        // --- 3. Coba Login sebagai Peminjam (Mahasiswa) ---
        $peminjam = Peminjam::where('nim', $username)
                            ->where('tgl_lahir', $tgl_lahir_db_format)
                            ->first();

        if ($peminjam) {
            // Hapus sesi lama, regenerasi, lalu set sesi baru
            Session::flush(); 
            $request->session()->regenerate();
            
            Session::put([
                'user_type' => 'peminjam', 
                'user_id' => $peminjam->nim, 
                'user_name' => $peminjam->nama,
            ]);
            
            // NOTIFIKASI PERAN saat berhasil login (Ditampilkan di Dashboard)
            Session::flash('success', 'Anda berhasil masuk! Anda login sebagai **Peminjam**.');

            return redirect()->route('peminjam.dashboard');
        }

        // 4. Jika tidak ada yang cocok
        return back()->withInput()->withErrors([
            'username' => 'NIM/NIP atau Tanggal Lahir tidak valid. Mohon cek kembali input Anda.'
        ]);
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        // 1. Bersihkan semua variabel sesi custom
        $request->session()->forget(['user_type', 'user_id', 'user_name']);
        
        // 2. Hancurkan semua data sesi dan buat sesi baru
        $request->session()->invalidate();
        
        // 3. Regenerasi session token untuk keamanan
        $request->session()->regenerateToken();
        
        // Redirect ke halaman login dengan flash message NOTIFIKASI LOGOUT BERHASIL
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}