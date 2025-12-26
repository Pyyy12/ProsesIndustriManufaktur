@extends('layouts.app')

@section('title', 'Data Peminjam')

@section('content')
<div class="bg-white p-4 sm:p-6 rounded-xl shadow-2xl">
    
    {{-- HEADER & TOMBOL TAMBAH --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b pb-4">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-800 flex items-center mb-3 sm:mb-0">
            <i class="fas fa-user-graduate mr-3 text-purple-600"></i> Daftar Data Peminjam
        </h2>
        
        <div class="w-full sm:w-auto">
            <a href="{{ route('peminjam.create') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition duration-200 text-sm transform hover:scale-[1.02]">
                <i class="fas fa-plus mr-2"></i> Tambah Peminjam Baru
            </a>
        </div>
    </div>
    
    {{-- NOTIFIKASI SUCCESS --}}
    @if ($message = Session::get('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
            <p class="font-semibold">{{ $message }}</p>
        </div>
    @endif

    {{-- 1. TABEL VIEW (Desktop: lg dan ke atas) --}}
    <div class="hidden lg:block border border-gray-200 rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal border-collapse">
            <thead>
                <tr class="bg-indigo-50 text-indigo-800 uppercase text-xs font-semibold">
                    <th class="py-3 px-6 text-left">NIM</th>
                    <th class="py-3 px-6 text-left">Nama Lengkap</th>
                    <th class="py-3 px-6 text-left whitespace-nowrap">Tgl. Lahir</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm font-light">
                @forelse ($peminjams as $peminjam)
                    <tr class="border-b border-gray-200 hover:bg-indigo-50/50 transition duration-150">
                        <td class="py-4 px-6 text-left whitespace-nowrap font-medium text-gray-900">{{ $peminjam->nim }}</td>
                        <td class="py-4 px-6 text-left whitespace-nowrap">{{ $peminjam->nama }}</td>
                        <td class="py-4 px-6 text-left whitespace-nowrap">{{ \Carbon\Carbon::parse($peminjam->tgl_lahir)->format('d M Y') }}</td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex item-center justify-center space-x-2">
                                
                                {{-- Tombol Lihat (Indigo) --}}
                                <a href="{{ route('peminjam.show', $peminjam->nim) }}" title="Lihat Detail" class="w-8 h-8 flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 text-white rounded-full transition duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                
                                {{-- Tombol Ubah (Yellow) --}}
                                <a href="{{ route('peminjam.edit', $peminjam->nim) }}" title="Ubah Data" class="w-8 h-8 flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white rounded-full transition duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                
                                {{-- Tombol Hapus (Red) --}}
                                <form action="{{ route('peminjam.destroy', $peminjam->nim) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data peminjam: {{ $peminjam->nama }}? Aksi ini akan menghapus semua data peminjaman terkait.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Data" class="w-8 h-8 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-full transition duration-200 shadow-md hover:shadow-lg">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-10 px-6 text-center text-lg text-gray-500 bg-gray-50/50">
                            <i class="fas fa-user-slash text-3xl mb-2 text-gray-400"></i>
                            <p>Belum ada data peminjam yang terdaftar dalam sistem.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- 2. CARD VIEW (Mobile & Tablet: Default sampai md) --}}
    <div class="lg:hidden space-y-4">
        @forelse ($peminjams as $peminjam)
            <div class="bg-white p-4 rounded-xl shadow-lg border-l-4 border-purple-400 hover:shadow-xl transition duration-150">
                
                {{-- NIM & Nama --}}
                <div class="mb-3 border-b pb-2">
                    <p class="text-xs font-medium text-purple-600 uppercase">NIM</p>
                    <p class="text-base font-bold text-gray-900">{{ $peminjam->nim }}</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $peminjam->nama }}</p>
                </div>
                
                {{-- Detail --}}
                <div class="mb-4">
                    <p class="text-xs font-medium text-gray-500 uppercase">Tanggal Lahir</p>
                    <p class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($peminjam->tgl_lahir)->format('d M Y') }}</p>
                </div>
                
                {{-- Aksi --}}
                <div class="flex justify-end space-x-3 pt-2 border-t">
                    
                    {{-- Tombol Lihat (Indigo) --}}
                    <a href="{{ route('peminjam.show', $peminjam->nim) }}" title="Lihat Detail" 
                        class="w-8 h-8 flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 text-white rounded-full 
                               transition duration-200 shadow-md hover:shadow-lg transform hover:scale-105"> {{-- 💡 Efek Hover --}}
                        <i class="fas fa-eye text-xs"></i>
                    </a>
                    
                    {{-- Tombol Ubah (Yellow) --}}
                    <a href="{{ route('peminjam.edit', $peminjam->nim) }}" title="Ubah Data" 
                        class="w-8 h-8 flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white rounded-full 
                               transition duration-200 shadow-md hover:shadow-lg transform hover:scale-105"> {{-- 💡 Efek Hover --}}
                        <i class="fas fa-edit text-xs"></i>
                    </a>
                    
                    {{-- Tombol Hapus (Red) --}}
                    <form action="{{ route('peminjam.destroy', $peminjam->nim) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data peminjam: {{ $peminjam->nama }}? Aksi ini akan menghapus semua data peminjaman terkait.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Hapus Data" 
                            class="w-8 h-8 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-full 
                                   transition duration-200 shadow-md hover:shadow-lg transform hover:scale-105"> {{-- 💡 Efek Hover --}}
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-10 px-6 text-center text-lg text-gray-500 bg-gray-50/50 rounded-xl shadow-lg">
                <i class="fas fa-user-slash text-3xl mb-2 text-gray-400"></i>
                <p>Belum ada data peminjam yang terdaftar dalam sistem.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection