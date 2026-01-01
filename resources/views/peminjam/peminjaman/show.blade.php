@extends('layouts.app')

@section('title', 'Peminjaman: ' . $peminjaman->no_pinjam)

@section('content')
<div class="bg-white p-4 md:p-6 rounded-xl shadow-xl border border-gray-100">
    {{-- Header: Stack di mobile, row di desktop --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b pb-4 gap-4">
        <h2 class="text-xl md:text-3xl font-extrabold text-gray-800">
            <span class="text-indigo-600">Detail Transaksi</span> <span class="block md:inline text-gray-500 text-lg md:text-3xl">#{{ $peminjaman->no_pinjam }}</span>
        </h2>
        <a href="{{ route('peminjam.peminjaman.index') }}" class="w-full md:w-auto text-center bg-gray-100 text-gray-600 hover:bg-gray-200 font-semibold py-2 px-4 rounded-lg transition duration-200 text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @php
        $statusDb = $peminjaman->status; 
        $statusDisplay = $peminjaman->display_status ?? ($statusDb === null ? 'Menunggu Konfirmasi' : $statusDb);
        $isLate = $peminjaman->is_late ?? false;
        $dueDate = \Carbon\Carbon::parse($peminjaman->due_date . ' ' . ($peminjaman->waktu_kembali ?? '17:00:00'), 'Asia/Jakarta');
        
        $statusClass = match ($statusDisplay) {
            'Dikembalikan' => 'bg-green-600',
            'Terlambat' => 'bg-red-600',
            'Dipinjam' => 'bg-yellow-500',
            'Menunggu Konfirmasi' => 'bg-blue-600',
            'Ditolak' => 'bg-gray-600',
            default => 'bg-gray-500',
        };

        $returnStatusText = '';
        $returnStatusColor = 'text-gray-600';
        if ($statusDb === 'Dikembalikan') {
            $tanggalKembali = $peminjaman->tanggal_kembali ?? $dueDate->toDateString();
            $waktuKembali = $peminjaman->waktu_kembali ?? '00:00:00';
            $actualReturnTime = \Carbon\Carbon::parse($tanggalKembali . ' ' . $waktuKembali, 'Asia/Jakarta');
            
            if ($actualReturnTime->greaterThan($dueDate)) {
                $returnStatusText = 'TERLAMBAT';
                $returnStatusColor = 'text-red-600 font-extrabold';
            } else {
                $returnStatusText = 'TEPAT WAKTU';
                $returnStatusColor = 'text-green-600 font-extrabold';
            }
        }
    @endphp

    {{-- Info Cards: Grid 2 kolom di mobile, 4 di desktop --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-8">
        <div class="p-4 rounded-xl shadow-md text-white font-extrabold text-center flex flex-col justify-center {{ $statusClass }} col-span-2 md:col-span-1">
            <div class="text-[10px] uppercase opacity-80">Status Utama</div>
            <div class="text-lg md:text-xl">{{ strtoupper($statusDisplay) }}</div>
        </div>

        <div class="bg-gray-50 p-3 md:p-4 rounded-xl border border-gray-200 shadow-sm">
            <h3 class="text-[10px] md:text-xs font-bold text-gray-500 uppercase mb-1"><i class="fas fa-calendar-times mr-1"></i> Batas Waktu</h3>
            <p class="text-gray-900 text-sm md:text-base font-bold">{{ $dueDate->format('d M Y') }}</p>
            <p class="text-xs text-indigo-600 font-semibold">{{ $dueDate->format('H:i') }} WIB</p>
        </div>

        <div class="bg-gray-50 p-3 md:p-4 rounded-xl border border-gray-200 shadow-sm">
            <h3 class="text-[10px] md:text-xs font-bold text-gray-500 uppercase mb-1"><i class="fas fa-clock mr-1"></i> Transaksi</h3>
            <p class="text-[11px] text-gray-600 leading-tight">Pinjam: <span class="font-bold">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/y') }}</span></p>
            @if ($statusDb === 'Dikembalikan')
                <p class="text-[11px] text-gray-600 mt-1 pt-1 border-t border-gray-200 leading-tight">Kembali: <span class="{{ $returnStatusColor }}">{{ $returnStatusText }}</span></p>
            @endif
        </div>

        <div class="bg-gray-50 p-3 md:p-4 rounded-xl border border-gray-200 shadow-sm">
            <h3 class="text-[10px] md:text-xs font-bold text-gray-500 uppercase mb-1"><i class="fas fa-user-cog mr-1"></i> Verifikator</h3>
            <p class="text-gray-900 text-xs md:text-sm font-bold truncate">{{ $peminjaman->plp->nama ?? 'Sistem' }}</p>
            <p class="text-[10px] text-gray-400">NIP: {{ $peminjaman->nip ?? '-' }}</p>
        </div>
    </div>
    
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <span class="w-1 h-6 bg-indigo-600 rounded mr-2"></span> Informasi Kegiatan
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 p-5 bg-indigo-50 rounded-2xl border border-indigo-100">
        <div class="space-y-2">
            <div class="flex flex-col">
                <span class="text-[10px] font-bold text-indigo-400 uppercase">Peminjam</span>
                <span class="text-sm font-bold text-indigo-900">{{ $peminjaman->peminjam->nama ?? 'N/A' }} ({{ $peminjaman->peminjam->nim ?? 'N/A' }})</span>
            </div>
        </div>
        <div class="space-y-2">
            <div class="flex flex-col">
                <span class="text-[10px] font-bold text-indigo-400 uppercase">Mata Kuliah / Dosen</span>
                <span class="text-sm font-bold text-indigo-900">{{ $peminjaman->mata_kuliah }}</span>
                <span class="text-xs text-indigo-700">{{ $peminjaman->dosen_pengampu }}</span>
            </div>
        </div>
    </div>

    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <span class="w-1 h-6 bg-green-500 rounded mr-2"></span> Daftar Alat
    </h3>

    {{-- Desktop Table: Muncul di md ke atas --}}
    <div class="hidden md:block overflow-x-auto border rounded-xl shadow-sm">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <th class="py-4 px-6 text-left">Kode & Nama Alat</th>
                    <th class="py-4 px-6 text-center">Qty</th>
                    <th class="py-4 px-6 text-left">Keterangan</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-200">
                @foreach ($peminjaman->detailPeminjaman as $detail)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6">
                            <div class="font-mono text-xs text-indigo-600 font-bold mb-1">{{ $detail->nomor_alat }}</div>
                            <div class="font-bold text-gray-800">{{ $detail->tool->nama_alat ?? 'Alat Dihapus' }}</div>
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-lg text-gray-900">{{ $detail->qty }}</td>
                        <td class="py-4 px-6 text-gray-500 italic">{{ $detail->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Card List: Muncul di bawah layar md --}}
    <div class="md:hidden space-y-4">
        @foreach ($peminjaman->detailPeminjaman as $detail)
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-indigo-600 text-white px-3 py-1 rounded-bl-lg font-bold text-sm">
                    {{ $detail->qty }} pcs
                </div>
                <div class="font-mono text-[10px] text-indigo-500 font-bold mb-1">{{ $detail->nomor_alat }}</div>
                <div class="font-bold text-gray-800 pr-12">{{ $detail->tool->nama_alat ?? 'Alat Dihapus' }}</div>
                <div class="mt-3 pt-2 border-t border-dashed border-gray-200 text-xs text-gray-500">
                    <span class="font-bold text-gray-400 uppercase text-[9px] block mb-1">Keterangan:</span>
                    {{ $detail->keterangan ?? 'Tidak ada catatan' }}
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection