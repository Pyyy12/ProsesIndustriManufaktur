<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Peminjam;
use App\Models\Plp;
use App\Models\Tool;
use App\Models\DetailPeminjaman; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\PeminjamansExport;

class PeminjamanController extends Controller
{
    /**
     * Helper untuk menentukan basis path view ('admin' atau 'peminjam').
     */
    private function getBaseViewPath()
    {
        return Session::get('user_type') === 'plp' ? 'admin' : 'peminjam'; 
    }

    /**
     * Helper untuk mendapatkan route prefix ('peminjam.' atau '').
     */
    private function getRoutePrefix()
    {
        return Session::get('user_type') === 'plp' ? '' : 'peminjam.'; 
    }

    // ====================================================================================
    // 1. INDEX (Tampilkan Daftar Transaksi)
    // ====================================================================================
    public function index(Request $request)
    {
        $viewPath = $this->getBaseViewPath();
        $search = $request->input('search');
        $now = Carbon::now('Asia/Jakarta');
        
        $peminjamansQuery = Peminjaman::with(['peminjam', 'plp', 'detailPeminjaman.tool']);

        // Filter role peminjam (hanya melihat milik sendiri)
        if ($viewPath === 'peminjam') {
            $nim = Session::get('user_id'); 
            $peminjamansQuery->where('nim', $nim);
        }

        // --- LOGIKA SEARCH ---
        if ($search) {
            $peminjamansQuery->where(function($q) use ($search, $now) {
                $q->where('no_pinjam', 'LIKE', "%{$search}%")
                  ->orWhere('nim', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%")
                  ->orWhereHas('peminjam', function($subQ) use ($search) {
                      $subQ->where('nama', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('plp', function($subQ) use ($search) {
                      $subQ->where('nama', 'LIKE', "%{$search}%");
                  });
                
                // Pencarian status manual "Menunggu Konfirmasi"
                if (stripos('Menunggu', $search) !== false) {
                    $q->orWhereNull('status');
                }
            });
        }
        
        $peminjamans = $peminjamansQuery->latest()
                        ->get()
                        ->map(function ($peminjaman) use ($now) {
                            $waktuRencanaKembali = $peminjaman->waktu_kembali ?? '17:00:00'; 
                            $batasWaktuKembali = Carbon::parse($peminjaman->due_date . ' ' . $waktuRencanaKembali, 'Asia/Jakarta');
                            
                            $displayStatus = $peminjaman->status;
                            
                            // Logika tampilan status (UI Only)
                            if ($peminjaman->status === null) {
                                $displayStatus = 'Menunggu Konfirmasi';
                            } elseif ($peminjaman->status === 'Disetujui') {
                                $displayStatus = 'Disetujui (Siap Ambil)';
                            } elseif ($peminjaman->status === 'Dipinjam' && $now->greaterThan($batasWaktuKembali)) {
                                $displayStatus = 'Terlambat (Belum Kembali)';
                            }

                            $peminjaman->display_status = $displayStatus;
                            $peminjaman->due_date_time = $batasWaktuKembali->format('d M Y H:i');
                            
                            return $peminjaman;
                        });

        return view($viewPath . '.peminjaman.index', compact('peminjamans'));
    }

    // ====================================================================================
    // 2. CREATE (Hanya untuk Peminjam)
    // ====================================================================================
    public function create()
    {
        $viewPath = $this->getBaseViewPath();
        
        if ($viewPath === 'peminjam') {
            $nim = Session::get('user_id'); 
            $peminjam = Peminjam::where('nim', $nim)->first(); 
            $plps = Plp::all(); 
            $keranjangItems = Session::get('keranjang', []); 
            
            if (!$peminjam) return back()->with('error', 'Data peminjam tidak ditemukan.');
            if (empty($keranjangItems)) return redirect()->route('peminjam.tools.index')->with('error', 'Keranjang Anda kosong.');
            
            return view('peminjam.peminjaman.create', compact('peminjam', 'plps', 'keranjangItems')); 
        } 

        return redirect()->route('peminjaman.index')->with('error', 'Fitur tidak tersedia untuk Admin.');
    }

    // ====================================================================================
    // 3. STORE (Simpan Transaksi Baru)
    // ====================================================================================
    public function store(Request $request)
    {
        $routePrefix = $this->getRoutePrefix();
        
        if (Session::get('user_type') === 'peminjam' && !empty(Session::get('user_id'))) {
            $request->merge(['nim' => Session::get('user_id')]); 
        }

        $request->validate([
            'no_pinjam' => 'required|string|max:20|unique:peminjaman,no_pinjam',
            'nim' => 'required|exists:peminjam,nim', 
            'nip' => 'required|exists:plp,nip', 
            'dosen_pengampu' => 'required|string|max:100', 
            'mata_kuliah' => 'required|string|max:100',
            'due_date' => 'required|date|after_or_equal:today', 
            'waktu_pinjam_rencana' => 'required|date_format:H:i', 
            'waktu_kembali_rencana' => 'required|date_format:H:i', 
            'alat' => 'required|array|min:1', 
            'alat.*.nomor_alat' => 'required|exists:tools,nomor_alat',
            'alat.*.qty' => 'required|integer|min:1',
        ]);
        
        // Cek Stok
        foreach ($request->alat as $detail) {
            $tool = Tool::where('nomor_alat', $detail['nomor_alat'])->first();
            if (!$tool || $tool->stok < $detail['qty']) {
                return back()->withInput()->withErrors(['stok_err' => "Stok alat {$detail['nomor_alat']} tidak mencukupi."]);
            }
        }

        DB::beginTransaction();
        try {
            $nowWIB = Carbon::now('Asia/Jakarta');
            
            $peminjaman = Peminjaman::create([
                'no_pinjam' => $request->no_pinjam, 
                'nim' => $request->nim, 
                'nip' => $request->nip,
                'dosen_pengampu' => $request->dosen_pengampu, 
                'mata_kuliah' => $request->mata_kuliah,
                'tanggal' => $nowWIB->toDateString(), 
                'tanggal_pinjam' => $nowWIB->toDateString(), 
                'waktu_pinjam' => $request->waktu_pinjam_rencana, 
                'due_date' => $request->due_date, 
                'waktu_kembali' => $request->waktu_kembali_rencana, // Ini adalah waktu rencana kembali
                'status' => NULL, 
            ]);

            foreach ($request->alat as $detail) {
                // Kurangi stok
                Tool::where('nomor_alat', $detail['nomor_alat'])->decrement('stok', $detail['qty']);
                
                $peminjaman->detailPeminjaman()->create([
                    'nomor_alat' => $detail['nomor_alat'], 
                    'qty' => $detail['qty'], 
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);
            }
            
            Session::forget('keranjang'); 
            DB::commit(); 
            return redirect()->route($routePrefix . 'peminjaman.index')->with('success', 'Pengajuan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack(); 
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    // ====================================================================================
    // 4. SHOW (Lihat Detail)
    // ====================================================================================
    public function show(Peminjaman $peminjaman)
    {
        $viewPath = $this->getBaseViewPath();
        $routePrefix = $this->getRoutePrefix();
        
        if ($viewPath === 'peminjam' && $peminjaman->nim !== Session::get('user_id')) {
            return redirect()->route($routePrefix . 'peminjaman.index')->with('error', 'Akses ditolak.');
        }

        $peminjaman->load(['peminjam', 'plp', 'detailPeminjaman.tool']);
        return view($viewPath . '.peminjaman.show', compact('peminjaman'));
    }

    // ====================================================================================
    // 5. UPDATE (Konfirmasi Peminjaman & Pengembalian Terlambat)
    // ====================================================================================
    public function update(Request $request, Peminjaman $peminjaman) 
    {
        $routePrefix = $this->getRoutePrefix();
        $actionType = $request->input('action_type'); 
        
        if (Session::get('user_type') !== 'plp') return back()->with('error', 'Akses ditolak.');

        DB::beginTransaction();
        try {
            // A. KONFIRMASI PENGAMBILAN ALAT
            if ($actionType === 'confirm_borrow') {
                if ($peminjaman->status !== NULL) throw new \Exception('Status tidak valid.');
                $peminjaman->update(['status' => 'Dipinjam']);
                $message = 'Alat berhasil dipinjam.';

            // B. TOLAK PENGAJUAN
            } elseif ($actionType === 'reject_borrow') {
                if ($peminjaman->status !== NULL) throw new \Exception('Sudah diproses.');
                // Kembalikan stok karena batal dipinjam
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    Tool::where('nomor_alat', $detail->nomor_alat)->increment('stok', $detail->qty);
                }
                $peminjaman->update(['status' => 'Ditolak']);
                $message = 'Pengajuan ditolak.';

            // C. KONFIRMASI PENGEMBALIAN (LOGIKA TERLAMBAT DI SINI)
            } elseif ($actionType === 'confirm_return') {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    Tool::where('nomor_alat', $detail->nomor_alat)->increment('stok', $detail->qty);
                }

                $now = Carbon::now('Asia/Jakarta');
                
                // Bandingkan waktu sekarang dengan batas due_date + waktu rencana kembali
                $waktuRencana = $peminjaman->waktu_kembali ?? '17:00:00'; 
                $batasWaktu = Carbon::parse($peminjaman->due_date . ' ' . $waktuRencana, 'Asia/Jakarta');

                // Tentukan status akhir
                $finalStatus = 'Dikembalikan';
                if ($now->greaterThan($batasWaktu)) {
                    $finalStatus = 'Terlambat';
                }

                $peminjaman->update([
                    'waktu_kembali' => $now->toTimeString(), 
                    'status' => $finalStatus,
                    'tanggal_kembali' => $now->toDateString()
                ]);

                $message = ($finalStatus === 'Terlambat') 
                            ? 'Berhasil dikembalikan (Terlambat).' 
                            : 'Berhasil dikembalikan tepat waktu.';
            }

            DB::commit();
            return redirect()->route($routePrefix . 'peminjaman.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    // ====================================================================================
    // 6. DESTROY (Hapus Transaksi)
    // ====================================================================================
    public function destroy(Peminjaman $peminjaman) 
    {
        if (Session::get('user_type') !== 'plp') return back()->with('error', 'Akses ditolak.');

        DB::beginTransaction();
        try {
            // Jika dihapus saat masih "Dipinjam", kembalikan stoknya
            if (!in_array($peminjaman->status, ['Dikembalikan', 'Ditolak', 'Terlambat'])) {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    Tool::where('nomor_alat', $detail->nomor_alat)->increment('stok', $detail->qty);
                }
            }
            $peminjaman->detailPeminjaman()->delete();
            $peminjaman->delete();
            DB::commit();
            return redirect()->route($this->getRoutePrefix() . 'peminjaman.index')->with('success', 'Berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // ====================================================================================
    // 7. EXPORT (Unduh Excel)
    // ====================================================================================
    public function export()
    {
        if (Session::get('user_type') !== 'plp') return back()->with('error', 'Akses ditolak.');
        $fileName = 'Laporan_Peminjaman_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';
        return Excel::download(new PeminjamansExport, $fileName);
    }
}