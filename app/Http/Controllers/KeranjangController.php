<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Tool; // Perlu untuk validasi stok

class KeranjangController extends Controller
{
    /**
     * Menampilkan isi keranjang peminjaman.
     * Route: peminjam.keranjang.index
     * Ditingkatkan: Memastikan stok tersedia selalu yang terbaru dari database.
     */
    public function index()
    {
        $keranjangItems = Session::get('keranjang', []);
        $updatedItems = [];

        foreach ($keranjangItems as $nomorAlat => $item) {
            // Ambil stok terbaru dari database
            $tool = Tool::where('nomor_alat', $nomorAlat)->first();
            
            if ($tool) {
                // 1. Update stok_tersedia dengan nilai terbaru dari database
                $item['stok_tersedia'] = $tool->stok;

                // 2. Jika QTY di keranjang melebihi stok yang baru (karena stok master berkurang),
                //    turunkan QTY ke batas maksimum yang aman.
                if ($item['qty'] > $tool->stok) {
                    $item['qty'] = $tool->stok;
                    Session::flash('error', 'QTY item **' . $item['nama_alat'] . '** dikurangi karena stok master tidak mencukupi.');
                }

                $updatedItems[$nomorAlat] = $item;
            } else {
                // Jika alat tidak ditemukan di master data, hapus dari sesi
                Session::forget('keranjang.' . $nomorAlat);
                Session::flash('error', 'Alat **' . $item['nama_alat'] . '** tidak ditemukan lagi di master data dan telah dihapus dari keranjang.');
            }
        }
        
        // Simpan kembali sesi yang mungkin telah diperbarui
        Session::put('keranjang', $updatedItems);
        
        $keranjangItems = $updatedItems; // Menggunakan data yang sudah di-refresh
        
        return view('peminjam.keranjang.index', compact('keranjangItems'));
    }

    /**
     * Menambah alat ke keranjang (Dipanggil dari peminjam.tools.index).
     * Route: peminjam.keranjang.store
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_alat' => 'required|exists:tools,nomor_alat',
            'qty' => 'required|integer|min:1',
        ]);
        
        $nomorAlat = $request->nomor_alat;
        $qtyBaru = (int)$request->qty; 
        
        $tool = Tool::where('nomor_alat', $nomorAlat)->first();
        if (!$tool) {
            return back()->with('error', 'Alat tidak ditemukan.');
        }

        $keranjang = Session::get('keranjang', []);
        $qtySaatIni = isset($keranjang[$nomorAlat]) ? (int)$keranjang[$nomorAlat]['qty'] : 0;
        
        $qtyTotal = $qtySaatIni + $qtyBaru;
        
        if ($tool->stok < $qtyTotal) {
            return back()->with('error', 'Stok alat ' . $tool->nama_alat . ' tidak mencukupi (Max: ' . ($tool->stok - $qtySaatIni) . ' tambahan).');
        }

        if (isset($keranjang[$nomorAlat])) {
            $keranjang[$nomorAlat]['qty'] = $qtyTotal;
        } else {
            $keranjang[$nomorAlat] = [
                "nomor_alat" => $nomorAlat,
                "nama_alat" => $tool->nama_alat,
                "qty" => $qtyBaru,
                "stok_tersedia" => $tool->stok, 
            ];
        }

        Session::put('keranjang', $keranjang);
        return redirect()->route('peminjam.tools.index')->with('keranjang_success', $tool->nama_alat . ' berhasil ditambahkan ke keranjang!');
    }
    
    /**
     * Memperbarui QTY item tertentu di keranjang (session).
     * Route: PATCH /peminjam/keranjang/{nomor_alat}
     */
    public function update(Request $request, $nomorAlat)
    {
        $keranjangItems = Session::get('keranjang', []);

        // 1. Cek apakah item yang akan diupdate ada di keranjang
        if (!isset($keranjangItems[$nomorAlat])) {
            return back()->with('error', 'Item tidak ditemukan di keranjang.');
        }

        $tool = Tool::where('nomor_alat', $nomorAlat)->first();

        // Jika alat tidak ditemukan di master data (meski ada di sesi)
        if (!$tool) {
            unset($keranjangItems[$nomorAlat]);
            Session::put('keranjang', $keranjangItems);
            return back()->with('error', 'Alat master tidak ditemukan atau sudah dihapus. Item dihapus dari keranjang.');
        }

        // 2. Validasi input QTY berdasarkan stok aktual
        $validated = $request->validate([
            // Validasi Max menggunakan stok AKTUAL dari database
            'qty' => 'required|integer|min:1|max:' . $tool->stok, 
        ], [
            'qty.required' => 'Jumlah alat wajib diisi.',
            'qty.integer' => 'Jumlah harus berupa angka bulat.',
            'qty.min' => 'Jumlah minimal peminjaman adalah 1.',
            'qty.max' => 'Jumlah tidak boleh melebihi stok yang tersedia (' . $tool->stok . ').',
        ]);

        // 3. Update QTY di keranjang
        $keranjangItems[$nomorAlat]['qty'] = $validated['qty'];
        
        // 4. Update stok tersedia di sesi (untuk konsistensi saat update berikutnya)
        $keranjangItems[$nomorAlat]['stok_tersedia'] = $tool->stok; 

        // 5. Simpan kembali keranjang ke sesi
        Session::put('keranjang', $keranjangItems);

        return back()->with('success', 'Jumlah **' . $tool->nama_alat . '** berhasil diperbarui.');
    }


    /**
     * Menghapus satu item dari keranjang.
     * Route: peminjam.keranjang.destroy
     */
    public function destroy($nomorAlat)
    {
        $keranjang = Session::get('keranjang');
        
        if (isset($keranjang[$nomorAlat])) {
            $namaAlat = $keranjang[$nomorAlat]['nama_alat'];
            unset($keranjang[$nomorAlat]);
            Session::put('keranjang', $keranjang);
            return back()->with('success', $namaAlat . ' berhasil dihapus dari keranjang.');
        }
        
        return back()->with('error', 'Item tidak ditemukan di keranjang.');
    }
}