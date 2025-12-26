<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class PeminjamansExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * Mengambil data peminjaman beserta relasi yang diperlukan.
    */
    public function collection()
    {
        return Peminjaman::with(['peminjam', 'plp'])
            ->latest()
            ->get()
            ->map(function ($peminjaman) {
                // Gunakan logika status yang sama dengan Controller agar sinkron
                $now = Carbon::now('Asia/Jakarta');
                $waktuRencanaKembali = $peminjaman->waktu_kembali ?? '17:00:00';
                $batasWaktuKembali = Carbon::parse($peminjaman->due_date . ' ' . $waktuRencanaKembali, 'Asia/Jakarta');
                
                $displayStatus = $peminjaman->status ?? 'Menunggu Konfirmasi';
                
                if ($peminjaman->status === 'Dipinjam' && $now->greaterThan($batasWaktuKembali)) {
                    $displayStatus = 'Terlambat';
                }

                $peminjaman->export_status = $displayStatus;
                $peminjaman->export_due_date = $batasWaktuKembali->format('d-m-Y H:i');
                return $peminjaman;
            });
    }

    /**
    * Header kolom di Excel.
    */
    public function headings(): array
    {
        return [
            'No. Pinjam',
            'NIM',
            'Nama Peminjam',
            'PLP/Admin',
            'Tanggal Pinjam',
            'Batas Waktu Kembali',
            'Status Akhir',
            'Mata Kuliah',
            'Dosen Pengampu'
        ];
    }

    /**
    * Mapping data ke kolom Excel.
    */
    public function map($peminjaman): array
    {
        return [
            $peminjaman->no_pinjam,
            $peminjaman->nim,
            $peminjaman->peminjam->nama ?? 'N/A',
            $peminjaman->plp->nama ?? 'N/A',
            Carbon::parse($peminjaman->tanggal_pinjam)->format('d-m-Y'),
            $peminjaman->export_due_date . ' WIB',
            $peminjaman->export_status,
            $peminjaman->mata_kuliah,
            $peminjaman->dosen_pengampu,
        ];
    }
}