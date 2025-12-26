@extends('layouts.app')

@section('title', 'Ubah Peminjam')

@section('content')
<div class="bg-white p-6 sm:p-8 rounded-xl shadow-2xl max-w-lg mx-auto border-t-4 border-yellow-500">
    
    {{-- HEADER --}}
    <h2 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center border-b pb-3">
        <i class="fas fa-edit mr-3 text-yellow-600"></i> Ubah Data Peminjam: {{ $peminjam->nama }}
    </h2>
    
    {{-- ERROR VALIDATION MESSAGE --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
            <p class="font-bold mb-1">Terjadi Kesalahan Input:</p>
            <ul class="list-disc list-inside ml-2 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('peminjam.update', $peminjam->nim) }}" method="POST">
        @csrf
        @method('PUT')
        
        {{-- Input NIM --}}
        <div class="mb-5">
            <label for="nim" class="block text-gray-700 text-sm font-semibold mb-2">NIM</label>
            <input type="text" name="nim" id="nim" value="{{ old('nim', $peminjam->nim) }}" required
                   class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-150 @error('nim') border-red-500 @enderror"
                   placeholder="Masukkan NIM Peminjam">
        </div>

        {{-- Input Nama Lengkap --}}
        <div class="mb-5">
            <label for="nama" class="block text-gray-700 text-sm font-semibold mb-2">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $peminjam->nama) }}" required
                   class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-150 @error('nama') border-red-500 @enderror"
                   placeholder="Masukkan Nama Lengkap Peminjam">
        </div>
        
        {{-- Input Tanggal Lahir --}}
        <div class="mb-6">
            <label for="tgl_lahir" class="block text-gray-700 text-sm font-semibold mb-2">Tanggal Lahir</label>
            <input type="date" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir', $peminjam->tgl_lahir) }}" required
                   class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-800 leading-tight focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-150 @error('tgl_lahir') border-red-500 @enderror">
        </div>

        {{-- Tombol Aksi (Posisi ditukar) --}}
        <div class="flex items-center justify-between">
            
            {{-- Tombol Batal (Di sebelah kiri - Secondary Button) --}}
            <a href="{{ route('peminjam.index') }}" 
               class="inline-flex items-center justify-center font-semibold text-sm 
                      py-2.5 px-4 rounded-xl transition duration-200 
                      text-gray-600 border border-gray-300 bg-white
                      hover:bg-gray-100 hover:text-gray-800 transform hover:scale-[1.01] shadow-md">
                <i class="fas fa-times-circle mr-2"></i> Batal
            </a>

            {{-- Tombol Perbarui Data (Di sebelah kanan - Primary Action) --}}
            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition duration-200 transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-green-500">
                <i class="fas fa-arrow-up mr-2"></i> Perbarui Data
            </button>
            
        </div>
    </form>
</div>
@endsection