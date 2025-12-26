<?php

namespace App\Http\Controllers;

use App\Models\Peminjam; 
use Illuminate\Http\Request;

class PeminjamController extends Controller
{
    public function index() {
        $peminjams = Peminjam::latest()->get();
        return view('admin.peminjam.index', compact('peminjams')); 
    }
    
    public function create() { return view('admin.peminjam.create'); } 
    public function show(Peminjam $peminjam) { return view('admin.peminjam.show', compact('peminjam')); } 
    public function edit(Peminjam $peminjam) { return view('admin.peminjam.edit', compact('peminjam')); } 

    public function store(Request $request) {
        $request->validate([
            'nim' => 'required|string|max:20|unique:peminjam,nim', 
            'nama' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
        ]);
        Peminjam::create($request->all());
        return redirect()->route('peminjam.index')->with('success', 'Data peminjam berhasil ditambahkan.');
    }
    
    public function update(Request $request, Peminjam $peminjam) {
        $request->validate([
            // ✅ PERBAIKAN: Gunakan primary key kustom 'nim' untuk pengecualian uniqueness
            // Format: table, kolom, nilai_id, nama_kolom_id
            'nim' => 'required|string|max:20|unique:peminjam,nim,'.$peminjam->nim.',nim', 
            'nama' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
        ]);
        $peminjam->update($request->all());
        return redirect()->route('peminjam.index')->with('success', 'Data peminjam berhasil diperbarui.');
    }

    public function destroy(Peminjam $peminjam) {
        $peminjam->delete();
        return redirect()->route('peminjam.index')->with('success', 'Data peminjam berhasil dihapus.');
    }
}