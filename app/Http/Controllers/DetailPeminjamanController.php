<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjaman; 
use App\Models\Tool; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Exception;

class DetailPeminjamanController extends Controller
{
    /**
     * Tampilkan daftar semua detail peminjaman (Admin).
     */
    

    /**
     * Update keterangan/kondisi alat pada detail peminjaman.
     * Menggunakan Query Builder karena tabel menggunakan Composite Key.
     */
    public function update(Request $request, $no_pinjam, $nomor_alat)
    {
        $request->validate([
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            // Karena tidak ada PK tunggal (id), kita gunakan kueri manual agar presisi
            DB::table('detail_peminjaman')
                ->where('no_pinjam', $no_pinjam)
                ->where('nomor_alat', $nomor_alat)
                ->update([
                    'keterangan' => $request->keterangan,
                    'updated_at' => now()
                ]);

            return back()->with('success', 'Keterangan alat berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memperbarui keterangan.');
        }
    }

    /**
     * Hapus satu item detail peminjaman (DELETE).
     */
    public function destroy($no_pinjam, $nomor_alat)
    {
        DB::beginTransaction();

        try {
            // 1. Cari data menggunakan where ganda
            $detail = DetailPeminjaman::where('no_pinjam', $no_pinjam)
                                      ->where('nomor_alat', $nomor_alat)
                                      ->firstOrFail();

            // Ambil nama alat untuk notifikasi
            $tool = Tool::where('nomor_alat', $nomor_alat)->first();
            $nama_alat = $tool ? $tool->nama_alat : $nomor_alat;
            
            // 2. Logika Pengembalian Stok
            $peminjaman = DB::table('peminjaman')->where('no_pinjam', $no_pinjam)->first();
            $statusPerluKembalikanStok = ['Dipinjam', 'Terlambat', null];

            if ($peminjaman && in_array($peminjaman->status, $statusPerluKembalikanStok)) {
                if ($tool) {
                    $tool->increment('stok', $detail->qty);
                }
            }

            // 3. Hapus item detail menggunakan Query Builder agar aman dari kendala Primary Key
            DB::table('detail_peminjaman')
                ->where('no_pinjam', $no_pinjam)
                ->where('nomor_alat', $nomor_alat)
                ->delete();

            DB::commit();
            return back()->with('success', "Item {$nama_alat} berhasil dihapus dan stok telah diperbarui.");

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus item: ' . $e->getMessage());
        }
    }
}