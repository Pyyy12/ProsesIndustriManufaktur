<?php

namespace App\Http\Controllers;

use App\Models\Tool; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk debugging (opsional)

class ToolController extends Controller
{
    /**
     * Helper untuk menentukan basis path view ('admin' atau 'peminjam').
     */
    private function getBaseViewPathForIndexShow()
    {
        if (request()->routeIs('peminjam.tools.*')) {
            return 'peminjam'; 
        }
        return 'admin';
    }

    // ====================================================================================
    // 1. INDEX (Tampilkan Daftar Alat dengan Filter dan Search)
    // ====================================================================================
    public function index(Request $request)
    {
        // Ambil nilai dari request
        $search = $request->query('search');
        $currentCategory = $request->query('category');
        
        $viewPath = $this->getBaseViewPathForIndexShow();
        
        // 1. Mulai Query
        $toolsQuery = Tool::latest();

        // 2. Terapkan Search (Berdasarkan nama_alat atau nomor_alat)
        if ($search) {
            $toolsQuery->where(function($query) use ($search) {
                $query->where('nama_alat', 'LIKE', "%{$search}%")
                      ->orWhere('nomor_alat', 'LIKE', "%{$search}%");
            });
        }

        // 3. Terapkan Filter Kategori
        if ($currentCategory) {
            $toolsQuery->where('kategori', $currentCategory);
        }

        // 4. Eksekusi Query
        $tools = $toolsQuery->get();
        
        // 5. Ambil Daftar Kategori Unik untuk Dropdown Filter
        $categories = Tool::select('kategori')
                           ->distinct()
                           ->whereNotNull('kategori')
                           ->pluck('kategori')
                           ->sort()
                           ->all();

        // Kirim data ke view
        return view($viewPath . '.tools.index', compact('tools', 'categories', 'search', 'currentCategory')); 
    }

    // ====================================================================================
    // 2. CREATE (Hanya untuk Admin/PLP)
    // ====================================================================================
    public function create() 
    { 
        if (Session::get('user_type') !== 'plp') { abort(403, 'Akses Ditolak.'); }
        
        // 💡 Tambahkan logika pengiriman kategori ke view create juga
        $categories = Tool::select('kategori')
                           ->distinct()
                           ->whereNotNull('kategori')
                           ->pluck('kategori')
                           ->sort()
                           ->all();
        
        return view('admin.tools.create', compact('categories')); 
    }

    // ====================================================================================
    // 3. STORE (Simpan Alat Baru - Hanya untuk Admin/PLP)
    // ====================================================================================
    public function store(Request $request) 
    {
        if (Session::get('user_type') !== 'plp') { abort(403, 'Akses Ditolak.'); }
        
        $request->validate([
            'nomor_alat' => 'required|string|max:20|unique:tools,nomor_alat',
            'nama_alat' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'kategori' => 'nullable|string|max:50',
        ]);

        $gambarUrl = null;
        
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $gambarPath = $file->storeAs('public/images/tools', $namaFile); 
            $gambarUrl = Storage::url($gambarPath);
        }
        
        Tool::create([
            'nomor_alat' => $request->nomor_alat,
            'nama_alat' => $request->nama_alat,
            'stok' => $request->stok,
            'kategori' => $request->kategori,
            'gambar' => $gambarUrl,
        ]);
        
        return redirect()->route('tools.index')->with('success', 'Data Alat berhasil ditambahkan.');
    }

    // ====================================================================================
    // 4. SHOW (Tampilkan Detail Alat)
    // ====================================================================================
    public function show(Tool $tool)
    {
        $viewPath = $this->getBaseViewPathForIndexShow();
        return view($viewPath . '.tools.show', compact('tool'));
    }

    // ====================================================================================
    // 5. EDIT (Hanya untuk Admin/PLP)
    // ====================================================================================
    public function edit(Tool $tool) 
    { 
        if (Session::get('user_type') !== 'plp') { abort(403, 'Akses Ditolak.'); }
        
        // 💡 FIX: Ambil daftar kategori unik untuk dropdown di view edit
        $categories = Tool::select('kategori')
                           ->distinct()
                           ->whereNotNull('kategori')
                           ->pluck('kategori')
                           ->sort()
                           ->all();
                           
        return view('admin.tools.edit', compact('tool', 'categories')); 
    }

    // ====================================================================================
    // 6. UPDATE (Perbarui Alat - Hanya untuk Admin/PLP)
    // ====================================================================================
    public function update(Request $request, Tool $tool) 
    {
        if (Session::get('user_type') !== 'plp') { abort(403, 'Akses Ditolak.'); }
        
        $request->validate([
            'nomor_alat' => 'required|string|max:20|unique:tools,nomor_alat,'.$tool->nomor_alat.',nomor_alat', 
            'nama_alat' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'gambar_baru' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'kategori' => 'nullable|string|max:50',
        ]);
        
        $data = $request->except(['_token', '_method', 'gambar_baru']);
        $gambarUrl = $tool->gambar; 
        
        if ($request->hasFile('gambar_baru')) {
            DB::beginTransaction();
            try {
                // Hapus gambar lama
                if ($tool->gambar) {
                    $pathToDelete = str_replace('/storage/', 'public/', $tool->gambar);
                    Storage::delete($pathToDelete);
                }
                
                // Simpan file baru
                $file = $request->file('gambar_baru');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $gambarPath = $file->storeAs('public/images/tools', $namaFile);
                $gambarUrl = Storage::url($gambarPath);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Gagal memproses gambar baru. Coba ulangi.');
            }
        }
        
        $data['gambar'] = $gambarUrl;
        
        $tool->update($data);
        return redirect()->route('tools.index')->with('success', 'Data Alat berhasil diperbarui.');
    }

    // ====================================================================================
    // 7. DESTROY (Hapus Alat - Hanya untuk Admin/PLP)
    // ====================================================================================
    public function destroy(Tool $tool) 
    {
        if (Session::get('user_type') !== 'plp') { abort(403, 'Akses Ditolak.'); }
        
        DB::beginTransaction();
        try {
            // Hapus file gambar dari storage sebelum menghapus record
            if ($tool->gambar) {
                $pathToDelete = str_replace('/storage/', 'public/', $tool->gambar);
                Storage::delete($pathToDelete);
            }
            
            $tool->delete();
            DB::commit();
            return redirect()->route('tools.index')->with('success', 'Data Alat berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Notifikasi jika ada Foreign Key Constraint error
            return back()->with('error', 'Gagal menghapus alat. Pastikan alat tidak terkait dengan transaksi peminjaman yang aktif.');
        }
    }
}