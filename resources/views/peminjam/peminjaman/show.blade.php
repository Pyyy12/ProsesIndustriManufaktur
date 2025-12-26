@extends('layouts.app')

@section('title', 'Detail Peminjaman: ' . $peminjaman->no_pinjam)

@section('content')
<div class="bg-white p-6 rounded-lg shadow-xl">
    <div class="flex justify-between items-center mb-6 border-b pb-2">
        <h2 class="text-3xl font-extrabold text-gray-800">
            Detail Transaksi ({{ $peminjaman->no_pinjam }})
        </h2>
        <a href="{{ route('peminjam.peminjaman.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold py-1 px-3 rounded transition duration-200">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Riwayat
        </a>
    </div>

    {{-- Logika untuk menentukan status waktu pengembalian --}}
    @php
        // Menggunakan nilai default yang lebih informatif
        $statusDb = $peminjaman->status; 
        $statusDisplay = $peminjaman->display_status ?? ($statusDb === null ? 'Menunggu Konfirmasi' : $statusDb);
        $isLate = $peminjaman->is_late ?? false;
        
        // Gabungkan tanggal dan waktu batas untuk objek Carbon
        $dueDate = \Carbon\Carbon::parse($peminjaman->due_date . ' ' . ($peminjaman->waktu_kembali ?? '17:00:00'), 'Asia/Jakarta');
        
        // FIX: PENENTUAN KELAS WARNA STATUS UTAMA
        $statusClass = match ($statusDisplay) {
            'Dikembalikan' => 'bg-green-600',      // HIJAU FIX
            'Terlambat' => 'bg-red-600',           // Merah
            'Dipinjam' => 'bg-yellow-600',         // Kuning
            'Menunggu Konfirmasi' => 'bg-blue-600',// Biru
            'Ditolak' => 'bg-gray-600',
            default => 'bg-gray-500',
        };

        // Logika untuk menentukan status pengembalian (jika sudah dikembalikan)
        $returnStatusText = '';
        $returnStatusColor = 'text-gray-600';
        if ($statusDb === 'Dikembalikan') { // Menggunakan status DB yang paling akurat
            $tanggalKembali = $peminjaman->tanggal_kembali ?? $dueDate->toDateString();
            $waktuKembali = $peminjaman->waktu_kembali ?? '00:00:00';
            
            $actualReturnTime = \Carbon\Carbon::parse($tanggalKembali . ' ' . $waktuKembali, 'Asia/Jakarta');
            
            if ($actualReturnTime->greaterThan($dueDate)) {
                $returnStatusText = 'Dikembalikan TERLAMBAT';
                $returnStatusColor = 'text-red-600 font-extrabold';
            } else {
                $returnStatusText = 'Dikembalikan TEPAT WAKTU';
                $returnStatusColor = 'text-green-600 font-extrabold';
            }
        }
    @endphp

    {{-- Blok Status Utama dan Detail Waktu --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        
        {{-- Card 1: STATUS UTAMA (FIXED COLOR LOGIC) --}}
        <div class="p-4 rounded-xl shadow-lg text-white font-extrabold text-center flex flex-col justify-center min-h-[120px] {{ $statusClass }} md:col-span-1">
            <div class="text-xs uppercase mb-1">Status Peminjaman</div>
            <div class="text-2xl">{{ strtoupper($statusDisplay) }}</div>
        </div>

        {{-- Card 2: BATAS WAKTU --}}
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm col-span-1">
            <h3 class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-calendar-times mr-2"></i> Batas Waktu</h3>
            <p class="text-gray-900 text-lg font-bold">{{ $dueDate->format('d M Y') }}</p>
            <p class="text-sm text-indigo-600 font-semibold">{{ $dueDate->format('H:i') }} WIB</p>
            
            @if ($isLate && $peminjaman->status !== 'Dikembalikan')
                <p class="mt-2 text-xs font-bold text-red-600">⚠️ Sudah Terlambat!</p>
            @endif
        </div>

        {{-- Card 3: DETAIL WAKTU TRANSAKSI --}}
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm col-span-1">
            <h3 class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-clock mr-2"></i> Waktu Transaksi</h3>
            <p class="text-xs text-gray-500">Tgl. Pinjam: <span class="font-semibold">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</span></p>
            <p class="text-xs text-gray-500">Waktu Rencana Ambil: <span class="font-semibold">{{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format('H:i') }} WIB</span></p>

            @if ($statusDb === 'Dikembalikan')
                @php
                    $waktuKembaliAktual = \Carbon\Carbon::parse($peminjaman->waktu_kembali ?? '00:00:00')->format('H:i');
                @endphp
                <p class="text-xs mt-2 text-gray-500 border-t pt-2">Waktu Kembali Aktual:</p>
                <p class="text-sm font-bold {{ $returnStatusColor }}">{{ $waktuKembaliAktual }} ({{ $returnStatusText }})</p>
            @endif
        </div>

        {{-- Card 4: PLP BERTANGGUNG JAWAB --}}
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm col-span-1">
            <h3 class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-user-cog mr-2"></i> Admin Verifikasi</h3>
            <p class="text-gray-900 font-bold">{{ $peminjaman->plp->nama ?? 'N/A' }}</p>
            <p class="text-sm text-gray-500">NIP: {{ $peminjaman->nip }}</p>
        </div>
    </div>
    
    <h3 class="text-2xl font-bold text-gray-800 mb-4 mt-8">Detail Peminjam & Kegiatan</h3>
    
    {{-- Detail Peminjam --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 p-4 bg-indigo-50 rounded-xl border border-indigo-200">
        <div class="space-y-3">
            <h4 class="text-lg font-semibold text-indigo-800">Informasi Dasar</h4>
            <p><strong>NIM:</strong> {{ $peminjaman->peminjam->nim ?? 'N/A' }}</p>
            <p><strong>Nama Peminjam:</strong> {{ $peminjaman->peminjam->nama ?? 'N/A' }}</p>
        </div>
        <div class="space-y-3">
            <h4 class="text-lg font-semibold text-indigo-800">Tujuan Peminjaman</h4>
            <p><strong>Mata Kuliah:</strong> {{ $peminjaman->mata_kuliah }}</p>
            <p><strong>Dosen Pengampu:</strong> {{ $peminjaman->dosen_pengampu }}</p>
        </div>
    </div>


    {{-- Tabel Detail Alat --}}
    <h3 class="text-2xl font-bold text-gray-800 mb-4 mt-8">Alat yang Dipinjam</h3>
    <div class="overflow-x-auto border rounded-lg shadow-md">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Kode Alat</th>
                    <th class="py-3 px-6 text-left">Nama Alat</th>
                    <th class="py-3 px-6 text-center">Qty</th>
                    <th class="py-3 px-6 text-left">Keterangan Peminjam</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm font-light">
                @foreach ($peminjaman->detailPeminjaman as $detail)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6 text-left whitespace-nowrap font-mono text-gray-900">{{ $detail->nomor_alat }}</td>
                        <td class="py-3 px-6 text-left font-medium">{{ $detail->tool->nama_alat ?? 'Alat Dihapus' }}</td>
                        <td class="py-3 px-6 text-center font-bold text-indigo-600">{{ $detail->qty }}</td>
                        <td class="py-3 px-6 text-left">{{ $detail->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    

</div>
@endsection