<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    // Menentukan nama tabel di database
    protected $table = 'tools';
    
    // Menentukan Kunci Primer (Primary Key)
    protected $primaryKey = 'nomor_alat';
    
    // Menetapkan bahwa Primary Key BUKAN auto-incrementing integer
    public $incrementing = false;
    
    // Menetapkan tipe data Primary Key (karena 'nomor_alat' adalah string)
    protected $keyType = 'string';

    // Mendefinisikan kolom yang boleh diisi (Mass Assignable)
    protected $fillable = [
        'nomor_alat',
        'nama_alat',
        'stok',
        'gambar', // Asumsi path atau URL gambar
        'kategori', // Kolom yang baru ditambahkan
    ];

    /**
     * Relasi ke Model DetailPeminjaman.
     * Satu Alat dapat muncul di banyak Detail Peminjaman.
     * Hubungan: One-to-Many
     */
    public function detailPeminjaman()
    {
        // hasMany(NamaModelRelasi, 'foreign_key_di_tabel_relasi', 'local_key_di_tabel_ini')
        return $this->hasMany(DetailPeminjaman::class, 'nomor_alat', 'nomor_alat');
    }
}