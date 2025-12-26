<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    // Menentukan nama tabel di database
    protected $table = 'detail_peminjaman';

    // Karena tabel ini menggunakan kunci komposit (bukan integer ID tunggal)
    // kita harus menonaktifkan auto-incrementing.
    public $incrementing = false; 
    
    // Kunci komposit terdiri dari string
    protected $keyType = 'string';

    // Mendefinisikan kolom yang boleh diisi (Mass Assignable)
    protected $fillable = [
        'no_pinjam',    // Bagian dari Kunci Komposit, Foreign Key ke Peminjaman
        'nomor_alat',   // Bagian dari Kunci Komposit, Foreign Key ke Tool
        'qty',          // Jumlah alat yang dipinjam (Diubah dari qty_int)
        'keterangan',   // Keterangan tambahan (Diubah dari kategori)
    ];

    /**
     * Relasi Many-to-One: DetailPeminjaman dimiliki oleh satu transaksi Peminjaman.
     */
    public function peminjaman()
    {
        // belongsTo(NamaModelRelasi, 'foreign_key_di_tabel_ini', 'local_key_di_tabel_relasi')
        return $this->belongsTo(Peminjaman::class, 'no_pinjam', 'no_pinjam');
    }

    /**
     * Relasi Many-to-One: DetailPeminjaman merujuk ke satu Alat (Tool).
     */
    public function tool()
    {
        return $this->belongsTo(Tool::class, 'nomor_alat', 'nomor_alat');
    }
}