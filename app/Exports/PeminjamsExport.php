<?php

namespace App\Exports;

use App\Models\Peminjam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Opsional: untuk menyesuaikan lebar kolom

class PeminjamsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * Mengambil data dari database.
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil semua data peminjam
        return Peminjam::select('nim', 'nama', 'tgl_lahir')->get();
    }
    
    /**
     * Menentukan header kolom di Excel.
     */
    public function headings(): array
    {
        return [
            'NIM',
            'Nama Lengkap',
            'Tanggal Lahir (YYYY-MM-DD)',
        ];
    }

    /**
     * Memetakan data dari koleksi ke baris Excel.
     * @param Peminjam $peminjam
     */
    public function map($peminjam): array
    {
        // Memformat tanggal lahir ke format string YYYY-MM-DD agar konsisten
        $tglLahirFormatted = $peminjam->tgl_lahir 
                               ? \Carbon\Carbon::parse($peminjam->tgl_lahir)->format('Y-m-d') 
                               : null;
                               
        return [
            $peminjam->nim,
            $peminjam->nama,
            $tglLahirFormatted,
        ];
    }
}