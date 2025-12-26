<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB; 
use App\Models\Peminjaman; 
use App\Models\Tool; 
use Carbon\Carbon; 
// use Illuminate\Support\Facades\Auth; // Digunakan jika memakai Auth::user()

class DashboardPeminjamController extends Controller
{
    public function index()
    {
        // 1. Dapatkan Identitas Peminjam yang Sedang Login
        // Menggunakan Session::get('user_id') sesuai kode Anda, 
        // pastikan ini menyimpan NIM/NIP peminjam
        $peminjamId = Session::get('user_id'); 
        
        // 💡 ASUMSI KRITIS: Kolom Foreign Key di tabel Peminjaman
        $peminjamFKColumn = 'nim'; 

        // ===============================================
        // 2. Data Ringkasan Akun (Cards)
        // ===============================================
        
        $peminjaman_aktif_count = Peminjaman::where($peminjamFKColumn, $peminjamId)
                                            // 'Disetujui' dan 'Dipinjam' adalah status yang umum untuk yang sedang berjalan/menunggu pengambilan
                                            ->whereIn('status', ['Disetujui', 'Dipinjam', 'Menunggu Konfirmasi'])
                                            ->count();

        $peminjaman_selesai_count = Peminjaman::where($peminjamFKColumn, $peminjamId)
                                              ->where('status', 'Dikembalikan')
                                              ->count();

        $total_alat_count = Tool::where('stok', '>', 0)->count();

        // ===============================================
        // 3. LOGIKA UTAMA: Alat Paling Sering Dipinjam oleh User Ini
        // ===============================================
        
        // Mengambil 4 alat teratas yang paling sering dipinjam oleh peminjam ini dari riwayat
        $myMostBorrowedTools = DB::table('detail_peminjaman') 
            // Join ke tabel peminjaman untuk filter berdasarkan NIM/ID
            ->join('peminjaman', 'detail_peminjaman.no_pinjam', '=', 'peminjaman.no_pinjam')
            // Join ke tabel tools untuk detail alat (nama, gambar, stok)
            ->join('tools', 'detail_peminjaman.nomor_alat', '=', 'tools.nomor_alat')
            ->select(
                'tools.nama_alat', 
                'tools.nomor_alat', 
                'tools.gambar', 
                'tools.kategori', 
                'tools.stok',
                // Menghitung total kuantitas alat yang dipinjam oleh user ini
                DB::raw('SUM(detail_peminjaman.qty) AS my_borrow_count')
            )
            // Filter: Hanya transaksi milik peminjam yang sedang login
            ->where('peminjaman.' . $peminjamFKColumn, $peminjamId)
            // Filter: Hanya transaksi yang sudah diproses (Dipinjam atau Dikembalikan)
            ->whereIn('peminjaman.status', ['Dikembalikan', 'Dipinjam']) 
            
            // Grouping: Mengelompokkan berdasarkan alat untuk menghitung total pinjam
            ->groupBy('tools.nomor_alat', 'tools.nama_alat', 'tools.gambar', 'tools.kategori', 'tools.stok')
            
            // Ordering & Limiting
            ->orderBy('my_borrow_count', 'desc')
            ->limit(4) 
            ->get();


        // 4. Mengarahkan ke View dengan Semua Data
        // Mengganti chartData lama dengan data alat favorit baru
        return view('peminjam.dashboard', [
            'peminjaman_aktif_count' => $peminjaman_aktif_count,
            'peminjaman_selesai_count' => $peminjaman_selesai_count,
            'total_alat_count' => $total_alat_count,
            // Variabel baru untuk Alat Favorit Peminjam
            'myMostBorrowedTools' => $myMostBorrowedTools, 
            
            // Variabel Chart Dihapus, tapi jika view Anda membutuhkannya (untuk menghindari error)
            // Anda bisa mengosongkannya:
            'chartLabels' => [],
            'chartData' => [],
        ]);
    }
}