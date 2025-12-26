<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjam extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang sesuai di database
    protected $table = 'peminjam';
    
    // Menentukan Kunci Primer (Primary Key)
    protected $primaryKey = 'nim';
    
    // Menetapkan bahwa Primary Key BUKAN auto-incrementing integer
    public $incrementing = false;
    
    // Menetapkan tipe data Primary Key (karena 'nim' adalah string)
    protected $keyType = 'string';

    // Mendefinisikan kolom yang boleh diisi (Mass Assignable)
    protected $fillable = [
        'nim',
        'nama',
        'tgl_lahir', // Kolom yang direvisi
    ];

    /**
     * Relasi ke Model Peminjaman.
     * Satu Peminjam (Mahasiswa) dapat melakukan banyak transaksi Peminjaman.
     * Hubungan: One-to-Many
     */
    public function peminjaman()
    {
        // hasMany(NamaModelRelasi, 'foreign_key_di_tabel_relasi', 'local_key_di_tabel_ini')
        return $this->hasMany(Peminjaman::class, 'nim', 'nim');
    }
}