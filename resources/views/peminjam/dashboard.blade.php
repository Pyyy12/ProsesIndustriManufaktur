@extends('layouts.app')

@section('title', 'Dashboard Peminjam')

@section('content')

{{-- 
    ========================================================================
    LOGIKA MODAL LOGIN BERHASIL (Dihapus dari sini, kini ditangani Toast Global)
    ========================================================================
--}}
@if (Session::get('success'))
{{-- Dibiarkan kosong agar Toast Global (di layouts/app) yang menangani notifikasi --}}
@endif

<div class="p-4 sm:p-6">
    
    {{-- RINGKASAN AKUN (CARDS) --}}
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-6 flex items-center border-b pb-2">
        <i class="fas fa-cubes mr-2 text-indigo-500"></i> Status Peminjaman
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-10">
        
        {{-- Card 1: Peminjaman Berlangsung (Link ke Riwayat) --}}
        <a href="{{ route('peminjam.peminjaman.index') }}" class="block">
            <div class="bg-white p-5 sm:p-6 rounded-xl shadow-xl hover:shadow-2xl border-l-4 border-yellow-500 transform hover:translate-y-[-4px] transition duration-300 flex justify-between items-center cursor-pointer">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Sedang Aktif</p>
                    <p class="text-5xl font-extrabold text-yellow-600 mt-2">{{ $peminjaman_aktif_count ?? 0 }}</p> 
                    <p class="text-xs text-gray-500 mt-3 font-semibold">Transaksi menunggu pengembalian.</p>
                </div>
                <i class="fas fa-hourglass-half text-5xl text-yellow-200"></i>
            </div>
        </a>

        {{-- Card 2: Total Peminjaman Selesai (Link ke Riwayat) --}}
        <a href="{{ route('peminjam.peminjaman.index') }}" class="block">
            <div class="bg-white p-5 sm:p-6 rounded-xl shadow-xl hover:shadow-2xl border-l-4 border-green-500 transform hover:translate-y-[-4px] transition duration-300 flex justify-between items-center cursor-pointer">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Selesai</p>
                    <p class="text-5xl font-extrabold text-green-600 mt-2">{{ $peminjaman_selesai_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-3 font-semibold">Total riwayat pengembalian alat.</p>
                </div>
                <i class="fas fa-handshake text-5xl text-green-200"></i>
            </div>
        </a>
        
        {{-- Card 3: Katalog Alat (Link ke Katalog) --}}
        <a href="{{ route('peminjam.tools.index') }}" class="block">
            <div class="bg-white p-5 sm:p-6 rounded-xl shadow-xl hover:shadow-2xl border-l-4 border-blue-500 transform hover:translate-y-[-4px] transition duration-300 flex justify-between items-center cursor-pointer">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Alat Tersedia</p>
                    <p class="text-5xl font-extrabold text-blue-600 mt-2">{{ $total_alat_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-3 font-semibold">Cek stok & ajukan peminjaman.</p>
                </div>
                <i class="fas fa-wrench text-5xl text-blue-200"></i>
            </div>
        </a>
    </div>

    <hr class="my-6 sm:my-8 border-gray-200">

    {{-- ALAT PALING SERING DIPINJAM OLEH PENGGUNA INI (GRID CARDS) --}}
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-flask mr-2 text-yellow-500"></i> Alat Sering Saya Pinjam
    </h2>

    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-2xl border border-gray-200">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            @forelse (($myMostBorrowedTools ?? []) as $tool)
                {{-- Penentuan Warna Stok untuk Badge --}}
                @php
                    $stok = $tool->stok ?? 0;
                    $stok_color = ($stok > 5) ? 'bg-green-600' : (($stok > 0) ? 'bg-yellow-600' : 'bg-red-600');
                    // Variabel dari Controller: my_borrow_count
                    $borrowCount = $tool->my_borrow_count ?? '-'; 
                @endphp
                
                {{-- Card Alat Favorit Saya --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:border-indigo-500 transform hover:translate-y-[-2px]">
                    
                    {{-- Area Gambar --}}
                    <div class="h-32 bg-gray-100 relative flex items-center justify-center">
                        @if ($tool->gambar)
                            <img src="{{ asset($tool->gambar) }}" alt="{{ $tool->nama_alat }}" 
                                  class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-500">
                                <i class="fas fa-tools text-4xl"></i>
                            </div>
                        @endif
                        
                        {{-- Badge Stok --}}
                        <span class="absolute top-2 right-2 px-3 py-1 text-xs font-bold text-white rounded-full shadow-md {{ $stok_color }}">
                            Stok: {{ $stok }}
                        </span>
                    </div>
                    
                    {{-- Detail & Count --}}
                    <div class="p-3 flex-grow">
                        <p class="text-sm font-semibold text-gray-700 leading-tight truncate">{{ $tool->nama_alat }}</p>
                        <p class="text-xs text-indigo-500 font-medium mt-1">{{ $tool->kategori ?? 'Umum' }}</p>
                    </div>

                    {{-- Footer Count --}}
                    <div class="p-3 border-t bg-indigo-50 flex items-center justify-center space-x-2">
                        <i class="fas fa-chart-bar text-indigo-600"></i>
                        <span class="text-sm font-extrabold text-gray-800">
                            {{ $borrowCount }}x Anda Pinjam
                        </span>
                    </div>
                </div>
            @empty
                {{-- Pesan jika tidak ada data alat favorit --}}
                <div class="col-span-full text-center py-8 text-gray-500 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                    <i class="fas fa-box-open text-4xl mb-2 text-gray-400"></i>
                    <p class="font-medium">Anda belum memiliki riwayat peminjaman alat yang dicatat.</p>
                    <a href="{{ route('peminjam.tools.index') }}" class="text-indigo-600 hover:underline mt-2 block font-medium text-sm">
                        Mulai Pinjam Alat Pertama Anda
                    </a>
                </div>
            @endforelse
        </div>
        
        {{-- Tombol Lihat Semua Tools --}}
        <div class="text-center mt-6">
            <a href="{{ route('peminjam.tools.index') }}" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl transition duration-200 shadow-lg text-sm transform hover:scale-[1.01]">
                <i class="fas fa-flask mr-2"></i> Lihat Katalog Alat Lengkap
            </a>
        </div>
    </div>
</div>
@endsection