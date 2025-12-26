@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

@php
    // Ambil data dari Session
    $userType = session('user_type');
    $userId = session('user_id');
    $userName = session('user_name');
    
    // Tentukan Judul, Ikon, dan Warna berdasarkan tipe user
    $isPlp = ($userType === 'plp');
    $roleName = $isPlp ? 'PLP (Admin)' : 'Peminjam (Mahasiswa)';
    $idLabel = $isPlp ? 'NIP' : 'NIM';
    
    // Kelas Styling
    $roleIconClass = $isPlp ? 'fas fa-user-cog text-indigo-600' : 'fas fa-user-graduate text-purple-600';
    $accentBorder = $isPlp ? 'border-indigo-500' : 'border-purple-500';
    $accentText = $isPlp ? 'text-indigo-600' : 'text-purple-600';
    $accentBg = $isPlp ? 'bg-indigo-50' : 'bg-purple-50';
    $accentIcon = $isPlp ? 'text-indigo-500' : 'text-purple-500';
@endphp

{{-- Container Utama --}}
<div class="bg-white p-4 sm:p-8 rounded-xl shadow-2xl max-w-lg mx-auto border-t-4 {{ $accentBorder }}">
    
    {{-- Header Judul --}}
    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-800 mb-6 flex items-center border-b pb-3">
        <i class="{{ $roleIconClass }} mr-3"></i> Detail Profil
    </h2>

    {{-- Ringkasan Visual --}}
    <div class="text-center mb-8 {{ $accentBg }} p-6 rounded-xl border border-gray-200">
        <div class="w-28 h-28 bg-white rounded-full mx-auto flex items-center justify-center shadow-lg border-2 {{ $accentBorder }}">
            <i class="fas fa-user-circle text-6xl text-gray-400"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 mt-4">{{ $userName }}</h1>
        <p class="text-md font-semibold {{ $accentText }}">{{ $roleName }}</p>
    </div>

    {{-- Kartu Detail Data --}}
    <div class="space-y-3">
        
        {{-- Item 1: ID (NIP/NIM) --}}
        <div class="p-3 border-b border-gray-200 bg-gray-50 rounded-lg">
            <p class="text-xs font-semibold text-gray-600 flex items-center mb-1">
                <i class="fas fa-id-badge w-5 mr-3 {{ $accentIcon }}"></i> {{ $idLabel }}
            </p>
            <p class="text-base sm:text-lg text-gray-900 font-extrabold ml-3 sm:ml-0">{{ $userId }}</p>
        </div>
        
        {{-- Item 2: Nama --}}
        <div class="p-3 border-b border-gray-200 hover:bg-gray-50 transition duration-150 rounded-lg">
            <p class="text-xs font-semibold text-gray-600 flex items-center mb-1">
                <i class="fas fa-user w-5 mr-3 {{ $accentIcon }}"></i> Nama Lengkap
            </p>
            <p class="text-base text-gray-800 font-medium ml-3 sm:ml-0">{{ $userName }}</p>
        </div>
        
        {{-- Item 3: Tipe Pengguna --}}
        <div class="p-3 border-b border-gray-200 hover:bg-gray-50 transition duration-150 rounded-lg">
            <p class="text-xs font-semibold text-gray-600 flex items-center mb-1">
                <i class="fas fa-shield-alt w-5 mr-3 {{ $accentIcon }}"></i> Peran Akses
            </p>
            <p class="text-base text-gray-800 font-medium ml-3 sm:ml-0">{{ $roleName }}</p>
        </div>
        
        {{-- Pesan Informasi FORMAL --}}
        <div class="pt-4 text-sm text-gray-800 border-t mt-4 bg-gray-100 p-4 rounded-xl shadow-inner">
            <p class="font-bold mb-1 {{ $accentText }}"><i class="fas fa-exclamation-circle mr-1"></i> Kebijakan Data:</p>
            <p class="text-gray-700">Informasi profil ini bersifat permanen dan tidak dapat diubah. Permintaan perubahan data pengguna harus diajukan secara resmi kepada Administrator Sistem (PLP) atau otoritas manajemen data kampus yang berwenang.</p>
        </div>
    </div>
    
</div>
@endsection