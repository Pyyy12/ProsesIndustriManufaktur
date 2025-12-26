@extends('layouts.app')

@section('title', 'Detail Peminjam')

@section('content')

{{-- Container Utama --}}
<div class="bg-white p-6 sm:p-8 rounded-xl shadow-2xl max-w-3xl mx-auto border-t-4 border-indigo-500">
    
    {{-- Header Judul --}}
    <h2 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center border-b pb-3">
        <i class="fas fa-address-card mr-3 text-indigo-600"></i> Detail Data Peminjam
    </h2>

    {{-- Kartu Detail --}}
    <div class="space-y-4">
        
        {{-- Item 1: NIM --}}
        <div class="flex flex-col sm:flex-row border-b border-gray-200 pb-3 p-2 bg-indigo-50/50 rounded-lg">
            <p class="w-full sm:w-1/3 text-gray-600 font-semibold flex items-center">
                <i class="fas fa-id-badge w-5 mr-3 text-indigo-500"></i> NIM
            </p>
            <p class="w-full sm:w-2/3 text-gray-900 font-bold mt-1 sm:mt-0">{{ $peminjam->nim }}</p>
        </div>
        
        {{-- Item 2: Nama Lengkap --}}
        <div class="flex flex-col sm:flex-row border-b border-gray-200 pb-3 p-2 hover:bg-gray-50 rounded-lg transition duration-150">
            <p class="w-full sm:w-1/3 text-gray-600 font-semibold flex items-center">
                <i class="fas fa-user w-5 mr-3 text-indigo-500"></i> Nama Lengkap
            </p>
            <p class="w-full sm:w-2/3 text-gray-800 font-medium mt-1 sm:mt-0">{{ $peminjam->nama }}</p>
        </div>
        
        {{-- Item 3: Tanggal Lahir --}}
        <div class="flex flex-col sm:flex-row border-b border-gray-200 pb-3 p-2 hover:bg-gray-50 rounded-lg transition duration-150">
            <p class="w-full sm:w-1/3 text-gray-600 font-semibold flex items-center">
                <i class="fas fa-calendar-alt w-5 mr-3 text-indigo-500"></i> Tanggal Lahir
            </p>
            <p class="w-full sm:w-2/3 text-gray-800 mt-1 sm:mt-0">
                {{ \Carbon\Carbon::parse($peminjam->tgl_lahir)->format('d F Y') }}
            </p>
        </div>
        
        {{-- Item 4: Waktu Ditambahkan --}}
        <div class="flex flex-col sm:flex-row border-b border-gray-200 pb-3 p-2 hover:bg-gray-50 rounded-lg transition duration-150">
            <p class="w-full sm:w-1/3 text-gray-600 font-semibold flex items-center">
                <i class="fas fa-clock w-5 mr-3 text-indigo-500"></i> Ditambahkan Pada
            </p>
            <p class="w-full sm:w-2/3 text-gray-800 mt-1 sm:mt-0">
                {{ \Carbon\Carbon::parse($peminjam->created_at)->format('d M Y H:i') }}
            </p>
        </div>
        
        {{-- Item 5: Terakhir Diperbarui --}}
        <div class="flex flex-col sm:flex-row border-b border-gray-200 pb-3 p-2 hover:bg-gray-50 rounded-lg transition duration-150">
            <p class="w-full sm:w-1/3 text-gray-600 font-semibold flex items-center">
                <i class="fas fa-history w-5 mr-3 text-indigo-500"></i> Terakhir Diperbarui
            </p>
            <p class="w-full sm:w-2/3 text-gray-800 mt-1 sm:mt-0">
                {{ \Carbon\Carbon::parse($peminjam->updated_at)->format('d M Y H:i') }}
            </p>
        </div>

    </div>
    
    {{-- Tombol Aksi --}}
    <div class="mt-8 flex justify-between space-x-3">
        
        {{-- Tombol Kembali --}}
        <a href="{{ route('peminjam.index') }}" 
           class="inline-flex items-center text-sm sm:text-base bg-gray-500 hover:bg-gray-600 text-white font-bold 
                  py-2 px-3 sm:px-4 rounded-lg transition duration-200 shadow-md transform hover:scale-[1.02]">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
        
        {{-- Tombol Ubah Data --}}
        <a href="{{ route('peminjam.edit', $peminjam->nim) }}" 
           class="inline-flex items-center text-sm sm:text-base bg-yellow-500 hover:bg-yellow-600 text-white font-bold 
                  py-2 px-3 sm:px-4 rounded-lg transition duration-200 shadow-md transform hover:scale-[1.02]">
            <i class="fas fa-edit mr-2"></i> Ubah Data
        </a>
    </div>
</div>
@endsection