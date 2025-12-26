<?php

namespace App\Http\Controllers;

use App\Models\Peminjam; // Model untuk Mahasiswa
use App\Models\Peminjaman; // Model untuk Transaksi
use App\Models\Tool; // Model untuk Alat Lab
use App\Models\Plp; // Model untuk PLP/Admin (Tetap diimpor, tapi tidak digunakan untuk card)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // 1. Hitung Total Peminjam Terdaftar
        $total_peminjam_count = Peminjam::count();
        
        // 2. Hitung Transaksi Terlambat (MENGGANTIKAN PLP COUNT)
        // 💡 ASUMSI: Status 'Terlambat' ada di tabel peminjaman
        $transaksi_terlambat_count = Peminjaman::where('status', 'Terlambat')->count();
        
        // 3. Hitung Transaksi Aktif
        $transaksi_aktif_count = Peminjaman::whereIn('status', ['Dipinjam', 'Disetujui', 'Menunggu Konfirmasi'])->count();
        
        // 4. Hitung Total Jenis Alat (Tools)
        $total_tools_count = Tool::count();
        
        
        // ===============================================
        // 5. LOGIKA DATA GRAFIK (6 Bulan Terakhir)
        // ===============================================
        
        $chartLabels = [];
        $chartData = [];
        $currentDate = Carbon::now();
        
        // Menggunakan 'updated_at' sebagai kolom tanggal yang aman
        $dateColumn = 'updated_at'; 
        
        for ($i = 5; $i >= 0; $i--) {
            $month = $currentDate->copy()->subMonths($i);
            
            $chartLabels[] = $month->isoFormat('MMM YY'); 
            
            // Hitung jumlah transaksi yang sudah 'Dikembalikan' pada bulan tersebut
            $count = Peminjaman::where('status', 'Dikembalikan')
                               ->whereMonth($dateColumn, $month->month)
                               ->whereYear($dateColumn, $month->year)
                               ->count();
            
            $chartData[] = $count;
        }

        // Mengirim semua variabel hitungan dan data grafik ke View
        return view('admin.dashboard', compact(
            'total_peminjam_count', 
            'transaksi_terlambat_count', // ⬅️ Variabel yang sudah diganti
            'transaksi_aktif_count', 
            'total_tools_count',
            'chartLabels',
            'chartData'
        ));
    }
}