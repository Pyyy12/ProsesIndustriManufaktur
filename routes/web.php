<?php
// routes/web.php

use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\PlpController;
use App\Http\Controllers\ToolController; 
use App\Http\Controllers\PeminjamanController; 
use App\Http\Controllers\DetailPeminjamanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardPeminjamController; 
use App\Http\Controllers\DashboardAdminController; 
use App\Http\Controllers\KeranjangController; 
use App\Http\Controllers\PeminjamToolController; 
use App\Http\Controllers\PeminjamProfileController;
use App\Http\Controllers\AdminProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| A. Rute NON-AUTHENTICATED (Akses Umum)
|--------------------------------------------------------------------------
*/

Route::get('/welcome-landing', function () {
    return view('welcome');
})->name('homepage'); 

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    if (!session()->has('user_type')) {
        return redirect()->route('homepage');
    }
    return redirect()->to(session('user_type') == 'plp' ? '/admin/dashboard' : '/peminjam/dashboard');
})->name('root');

/*
|--------------------------------------------------------------------------
| B. Rute ADMIN (PLP) - Akses Penuh
|--------------------------------------------------------------------------
*/
Route::middleware(['user_auth:plp'])->prefix('admin')->group(function () { 
    
    // Dashboard Admin
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard'); 
    
    // Data Master (CRUD Penuh)
    Route::resource('peminjam', PeminjamController::class); 
    Route::resource('plp', PlpController::class); 
    Route::resource('tools', ToolController::class); 
    
    // --- Transaksi Peminjaman ---
    
    // 1. Export Excel (Diletakkan di atas agar tidak bentrok dengan {peminjaman})
    Route::get('peminjaman/export', [PeminjamanController::class, 'export'])->name('peminjaman.export');
    
    // 2. Resource Utama Peminjaman
    Route::resource('peminjaman', PeminjamanController::class); 
    
    // 3. Update Keterangan/Kondisi Alat (Untuk Modal Pop-up)
    // ✅ Tambahkan rute ini untuk menangani update keterangan per item alat
    Route::put('/peminjaman/{no_pinjam}/detail/{nomor_alat}', [DetailPeminjamanController::class, 'update'])
        ->name('detailpeminjaman.update');
    
    // 4. Hapus Detail Item Spesifik
    Route::delete('/peminjaman/{no_pinjam}/detail/{nomor_alat}', [DetailPeminjamanController::class, 'destroy'])
        ->name('detailpeminjaman.destroy');
    
    // Halaman Profil Admin/PLP
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
});

/*
|--------------------------------------------------------------------------
| C. Rute PEMINJAM (Mahasiswa) - Akses Terbatas
|--------------------------------------------------------------------------
*/
Route::middleware(['user_auth:peminjam'])->prefix('peminjam')->group(function () {

    // Dashboard Peminjam
    Route::get('/dashboard', [DashboardPeminjamController::class, 'index'])->name('peminjam.dashboard');
    
    // Peminjam: Katalog Alat (READ ONLY)
    Route::get('/tools', [PeminjamToolController::class, 'index'])->name('peminjam.tools.index');
    Route::get('/tools/{tool}', [PeminjamToolController::class, 'show'])->name('peminjam.tools.show');
    
    // Peminjam: Keranjang Peminjaman
    Route::resource('keranjang', KeranjangController::class)
        ->only(['index', 'store', 'destroy', 'update']) 
        ->names('peminjam.keranjang'); 

    // Peminjam: Peminjaman Saya
    Route::resource('peminjaman', PeminjamanController::class) 
        ->names([
            'index' => 'peminjam.peminjaman.index',
            'create' => 'peminjam.peminjaman.create',
            'store' => 'peminjam.peminjaman.store',
            'show' => 'peminjam.peminjaman.show',
        ])
        ->only(['index', 'create', 'store', 'show']);

    // Halaman Profil Peminjam
    Route::get('/profile', [PeminjamProfileController::class, 'index'])->name('peminjam.profile.index');
}); 