<?php

namespace App\Http\Controllers;

use App\Models\Tool; 
use Illuminate\Http\Request;

class PeminjamToolController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter dari request
        $currentCategory = $request->query('category');
        $search = $request->query('search');
        
        $query = Tool::query();

        // 1. Terapkan Filter Search (Nama Alat atau Nomor Alat)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_alat', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_alat', 'LIKE', "%{$search}%");
            });
        }

        // 2. Terapkan Filter Kategori
        if ($currentCategory) {
            // Penting: Memfilter berdasarkan nilai kategori yang dikirim dari URL
            $query->where('kategori', $currentCategory); 
        }

        // 3. Filter wajib: hanya tampilkan alat yang tersedia (stok > 0)
        // Hal ini penting agar peminjam tidak bisa melihat alat yang habis.
        $query->where('stok', '>', 0);
        
        // 4. Eksekusi query
        $tools = $query->latest()->get();

        // 5. Ambil Daftar Kategori Unik untuk Dropdown Filter
        // Kita ambil semua kategori unik yang memiliki stok > 0
        $categories = Tool::select('kategori')
                           ->distinct()
                           ->whereNotNull('kategori')
                           ->where('stok', '>', 0) // Hanya tampilkan kategori yang masih ada stoknya
                           ->pluck('kategori')
                           ->sort()
                           ->all();
        
        // Kirim data ke view, termasuk kategori, nilai search, dan kategori aktif
        return view('peminjam.tools.index', compact('tools', 'categories', 'currentCategory', 'search'));
    }
}