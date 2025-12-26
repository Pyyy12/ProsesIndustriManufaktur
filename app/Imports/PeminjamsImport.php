<?php

namespace App\Imports;

use App\Models\Peminjam;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class PeminjamsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Periksa apakah 'nim' ada dan bukan kosong
        if (empty($row['nim'])) {
            return null;
        }

        // Cari Peminjam berdasarkan NIM. Jika ditemukan, update. Jika tidak, buat baru.
        $peminjam = Peminjam::firstOrNew(['nim' => $row['nim']]);
        
        // Ubah format tanggal lahir jika diperlukan (asumsi input dari Excel adalah YYYY-MM-DD)
        $tgl_lahir = null;
        if (!empty($row['tanggal_lahir_yyyymmdd'])) {
             // Maatwebsite sering kali menganggap tanggal sebagai float.
             // Kita coba parse sebagai tanggal atau dari integer
             try {
                 if (is_numeric($row['tanggal_lahir_yyyymmdd'])) {
                     $tgl_lahir = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir_yyyymmdd']));
                 } else {
                     $tgl_lahir = Carbon::parse($row['tanggal_lahir_yyyymmdd']);
                 }
             } catch (\Exception $e) {
                 // Abaikan jika parsing gagal, biarkan validasi menangkapnya
             }
        }

        $peminjam->nim = $row['nim'];
        $peminjam->nama = $row['nama_lengkap'];
        $peminjam->tgl_lahir = $tgl_lahir;
        
        // Password diatur default sebagai TGL LAHIR (YYYY-MM-DD)
        // Catatan: Ini perlu disesuaikan dengan logika hashing password Anda di model/event listener.
        if ($tgl_lahir) {
             $peminjam->password = $tgl_lahir->format('Y-m-d'); // Simpan sebagai plaintext untuk dibandingkan saat login
        }

        return $peminjam;
    }
    
    /**
     * Aturan Validasi untuk setiap baris Excel.
     */
    public function rules(): array
    {
        return [
            'nim' => 'required|string|max:15|unique:peminjam,nim,NULL,NULL', // unique:table,column,except,idColumn
            'nama_lengkap' => 'required|string|max:150',
            'tanggal_lahir_yyyymmdd' => 'required|date_format:Y-m-d',
        ];
    }
    
    /**
     * Memastikan header baris di Excel dibaca dengan benar.
     * Maatwebsite menggunakan nama kolom yang sudah dinormalisasi (snake_case).
     */
    public function customValidationAttributes()
    {
        return [
            'nim' => 'NIM',
            'nama_lengkap' => 'Nama Lengkap',
            'tanggal_lahir_yyyymmdd' => 'Tanggal Lahir (YYYY-MM-DD)',
        ];
    }
}