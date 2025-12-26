@extends('layouts.app')

@section('title', 'Daftar Alat Laboratorium')

@section('content')
<div class="bg-white p-4 sm:p-6 rounded-xl shadow-2xl">
    
    {{-- HEADER JUDUL --}}
    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-800 mb-4 flex items-center border-b pb-3">
        <i class="fas fa-microscope mr-3 text-blue-600"></i> Kelola Data Alat Lab
    </h2>
    
    {{-- 🛑 NOTIFIKASI DIHAPUS: Sekarang ditangani oleh Toast Global di layout.app.blade.php --}}
    
    {{-- TOMBOL TAMBAH & FORM FILTER/SEARCH --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        
        {{-- Tombol Tambah --}}
        <a href="{{ route('tools.create') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl transition duration-200 shadow-md transform hover:scale-[1.02]">
            ➕ Tambah Alat Baru
        </a>

        {{-- Form Pencarian dan Filter --}}
        <form action="{{ route('tools.index') }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
            
            {{-- Search Input --}}
            <input type="text" name="search" placeholder="Cari Kode atau Nama Alat..." value="{{ $search ?? '' }}"
                   class="w-full sm:w-64 p-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            
            {{-- Filter Kategori --}}
            <select name="category" 
                    class="w-full sm:w-48 p-2.5 border border-gray-300 rounded-xl shadow-sm 
                           focus:ring-indigo-500 focus:border-indigo-500 text-sm 
                           bg-white text-gray-700">
                <option value="">-- Semua Kategori --</option>
                
                {{-- Opsi Kategori Tambahan: Mesin --}}
                @php
                    $isMesinSelected = (($currentCategory ?? '') == 'Mesin') ? 'selected' : '';
                @endphp
                <option value="Mesin" {{ $isMesinSelected }}>Mesin</option>

                {{-- Loop Kategori dari Database --}}
                @foreach ($categories ?? [] as $category)
                    {{-- Pastikan tidak menduplikasi jika 'Mesin' sudah ada di database --}}
                    @if ($category !== 'Mesin')
                        <option value="{{ $category }}" {{ ($currentCategory ?? '') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endif
                @endforeach
            </select>

            {{-- Tombol Filter --}}
            <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-md transition duration-200">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>

    {{-- Grid Kartu (Responsive) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8">
        
        @forelse ($tools as $tool)
            {{-- Penentuan Warna Stok --}}
            @php
                $stok = $tool->stok ?? 0;
                $stok_color = 'bg-red-600'; 

                if ($stok > 5) {
                    $stok_color = 'bg-green-600';
                } elseif ($stok > 0) {
                    $stok_color = 'bg-yellow-600';
                }
            @endphp
            
            <div class="bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden flex flex-col transition-transform duration-200 hover:scale-[1.03] hover:shadow-2xl">
                
                {{-- Area Gambar --}}
                <div class="h-40 bg-gray-100 relative">
                    @if ($tool->gambar)
                        {{-- Menggunakan asset() untuk path gambar --}}
                        <img src="{{ asset($tool->gambar) }}" alt="{{ $tool->nama_alat }}" 
                             class="w-full h-full object-cover">
                    @else
                        {{-- Placeholder jika tidak ada gambar --}}
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <i class="fas fa-box-open text-5xl"></i>
                        </div>
                    @endif
                    
                    {{-- Badge Stok (Hanya Teks 'Stok' dan Angka) --}}
                    <span class="absolute top-3 right-3 px-3 py-1 text-sm font-extrabold text-white rounded-lg shadow-lg {{ $stok_color }} 
                                 flex items-center space-x-1">
                        {{-- Icon --}}
                        <i class="fas fa-cube text-xs"></i> 
                        {{-- Teks dan Angka Stok --}}
                        <span class="text-base">Stok {{ $stok }}</span>
                    </span>
                    
                    {{-- Badge Kategori --}}
                    @if ($tool->kategori)
                    <span class="absolute top-3 left-3 px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 border border-blue-300">
                        {{ $tool->kategori }}
                    </span>
                    @endif
                </div>
                
                {{-- Detail Alat --}}
                <div class="p-4 flex-grow">
                    <h3 class="text-xl font-extrabold text-gray-900 mb-1 leading-snug truncate">{{ $tool->nama_alat }}</h3>
                    <p class="text-sm font-semibold text-gray-700">Kode: <span class="font-mono text-gray-900">{{ $tool->nomor_alat }}</span></p>
                </div>

                {{-- Aksi (Action Buttons) --}}
                <div class="p-4 border-t bg-gray-50">
                    <div class="flex justify-between items-center space-x-2">
                        
                        {{-- Tombol Lihat (Detail) --}}
                        <a href="{{ route('tools.show', $tool) }}" class="w-1/3 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg text-sm transition shadow-md">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        {{-- Tombol Ubah --}}
                        <a href="{{ route('tools.edit', $tool) }}" class="w-1/3 text-center bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 rounded-lg text-sm transition shadow-md">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        {{-- Tombol Hapus --}}
                        <form action="{{ route('tools.destroy', $tool) }}" method="POST" onsubmit="return confirm('PERINGATAN! Yakin hapus {{ $tool->nama_alat }}? Data ini akan terhapus permanen.');" class="w-1/3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg text-sm transition shadow-md">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-gray-100 rounded-xl border-2 border-dashed border-gray-300">
                <i class="fas fa-search-minus text-4xl text-gray-400 mb-3"></i>
                <p class="text-xl text-gray-700">Data alat tidak ditemukan.</p>
                <p class="text-sm text-gray-500">Coba ubah kriteria pencarian atau filter.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection