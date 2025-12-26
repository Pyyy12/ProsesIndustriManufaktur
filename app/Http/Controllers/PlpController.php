<?php

namespace App\Http\Controllers;

use App\Models\Plp; 
use Illuminate\Http\Request;

class PlpController extends Controller
{
    public function index() {
        $plps = Plp::latest()->get();
        // View: resources/views/admin/plp/index.blade.php
        return view('admin.plp.index', compact('plps')); 
    }
    
    public function create() { return view('admin.plp.create'); }
    public function show(Plp $plp) { return view('admin.plp.show', compact('plp')); }
    public function edit(Plp $plp) { return view('admin.plp.edit', compact('plp')); }

    public function store(Request $request) {
        $request->validate([
            'nip' => 'required|string|max:20|unique:plp,nip', 
            'nama' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
        ]);
        Plp::create($request->all());
        return redirect()->route('plp.index')->with('success', 'Data PLP berhasil ditambahkan.');
    }
    
    public function update(Request $request, Plp $plp) {
        $request->validate([
            'nip' => 'required|string|max:20|unique:plp,nip,'.$plp->id, 
            'nama' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
        ]);
        $plp->update($request->all());
        return redirect()->route('plp.index')->with('success', 'Data PLP berhasil diperbarui.');
    }

    public function destroy(Plp $plp) {
        $plp->delete();
        return redirect()->route('plp.index')->with('success', 'Data PLP berhasil dihapus.');
    }
}