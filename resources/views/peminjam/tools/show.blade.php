@extends('layouts.app')

@section('title', 'Detail Alat')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-xl max-w-2xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Detail Data Alat</h2>

    <div class="flex items-center space-x-8">
        {{-- Kolom Gambar (Jika ada) --}}
        @if ($tool->gambar)
            <div class="w-1/3">
                <img src="{{ $tool->gambar }}" alt="{{ $tool->nama_alat }}" class="w-full h-auto object-cover rounded-lg shadow-md border">
            </div>
        @endif
        
        {{-- Kolom Detail --}}
        <div class="{{ $tool->gambar ? 'w-2/3' : 'w-full' }} space-y-4">
            <div class="flex border-b pb-2">
                <p class="w-1/3 text-gray-600 font-semibold">Nama Alat</p>
                <p class="w-2/3 text-gray-800 font-bold text-lg">{{ $tool->nama_alat }}</p>
            </div>
            <div class="flex border-b pb-2">
                <p class="w-1/3 text-gray-600 font-semibold">Nomor Alat</p>
                <p class="w-2/3 text-gray-800">{{ $tool->nomor_alat }}</p>
            </div>
            <div class="flex border-b pb-2">
                <p class="w-1/3 text-gray-600 font-semibold">Kategori</p>
                <p class="w-2/3 text-gray-800">{{ $tool->kategori ?? 'N/A' }}</p>
            </div>
            <div class="flex border-b pb-2">
                <p class="w-1/3 text-gray-600 font-semibold">Stok Saat Ini</p>
                <p class="w-2/3 text-gray-800 font-extrabold text-xl {{ $tool->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $tool->stok }} Unit
                </p>
            </div>
            <div class="flex border-b pb-2">
                <p class="w-1/3 text-gray-600 font-semibold">Ditambahkan Pada</p>
                <p class="w-2/3 text-gray-800">
                    {{ \Carbon\Carbon::parse($tool->created_at)->format('d M Y H:i') }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="mt-8 flex justify-between">
        <a href="{{ route('tools.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
            ← Kembali ke Daftar
        </a>
        <a href="{{ route('tools.edit', $tool->nomor_alat) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition duration-200">
            Ubah Data
        </a>
    </div>
</div>
@endsection