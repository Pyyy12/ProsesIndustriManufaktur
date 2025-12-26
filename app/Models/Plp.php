<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plp extends Model
{
    use HasFactory;

    // Menentukan nama tabel di database
    protected $table = 'plp';
    
    // Menentukan Kunci Primer (Primary Key)
    protected $primaryKey = 'nip'; // Menggunakan NIP
    
    // Menetapkan bahwa Primary Key BUKAN auto-incrementing integer
    public $incrementing = false;
    
    // Menetapkan tipe data Primary Key (karena 'nip' adalah string)
    protected $keyType = 'string';

    // Mendefinisikan kolom yang boleh diisi (Mass Assignable)
    protected $fillable = [
        'nip',
        'nama',
        'tgl_lahir',
    ];

    /**
     * Relasi ke Model Peminjaman.
     * Satu PLP dapat terlibat dalam banyak transaksi Peminjaman.
     * Hubungan: One-to-Many
     */
    public function peminjaman()
    {
        // hasMany(NamaModelRelasi, 'foreign_key_di_tabel_relasi', 'local_key_di_tabel_ini')
        return $this->hasMany(Peminjaman::class, 'nip', 'nip');
    }
}