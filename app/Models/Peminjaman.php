<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // Menentukan nama tabel di database
    protected $table = 'peminjaman';
    
    // Menentukan Kunci Primer (Primary Key)
    protected $primaryKey = 'no_pinjam';
    
    // Menetapkan bahwa Primary Key BUKAN auto-incrementing integer
    public $incrementing = false;
    
    // Menetapkan tipe data Primary Key (karena 'no_pinjam' adalah string)
    protected $keyType = 'string';

    // Mendefinisikan kolom yang boleh diisi (Mass Assignable)
    protected $fillable = [
        'no_pinjam',
        'nim',            // Foreign Key ke Peminjam
        'nip',            // Foreign Key ke PLP
        'dosen_pengampu',
        'mata_kuliah',
        'tanggal',        // DIGANTI dari tanggal_date
        'waktu_pinjam',
        'waktu_kembali',  // BARU: Bisa NULL saat awal peminjaman
        'status',
    ];

    /**
     * Relasi Many-to-One: Transaksi Peminjaman dimiliki oleh satu Peminjam (Mahasiswa).
     */
    public function peminjam()
    {
        // belongsTo(NamaModelRelasi, 'foreign_key_di_tabel_ini', 'local_key_di_tabel_relasi')
        return $this->belongsTo(Peminjam::class, 'nim', 'nim');
    }

    /**
     * Relasi Many-to-One: Transaksi Peminjaman dimiliki oleh satu PLP.
     */
    public function plp()
    {
        return $this->belongsTo(Plp::class, 'nip', 'nip');
    }

    /**
     * Relasi One-to-Many: Satu Peminjaman memiliki banyak Detail Peminjaman (item alat).
     */
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'no_pinjam', 'no_pinjam');
    }
}