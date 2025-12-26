@extends('layouts.app')

@section('title', 'Detail PLP')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-xl max-w-2xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Detail Data PLP</h2>

    <div class="space-y-4">
        <div class="flex border-b pb-2">
            <p class="w-1/3 text-gray-600 font-semibold">NIP</p>
            <p class="w-2/3 text-gray-800">{{ $plp->nip }}</p>
        </div>
        <div class="flex border-b pb-2">
            <p class="w-1/3 text-gray-600 font-semibold">Nama PLP</p>
            <p class="w-2/3 text-gray-800">{{ $plp->nama }}</p>
        </div>
        <div class="flex border-b pb-2">
            <p class="w-1/3 text-gray-600 font-semibold">Tanggal Lahir</p>
            <p class="w-2/3 text-gray-800">
                {{ \Carbon\Carbon::parse($plp->tgl_lahir)->format('d F Y') }}
            </p>
        </div>
        <div class="flex border-b pb-2">
            <p class="w-1/3 text-gray-600 font-semibold">Waktu Ditambahkan</p>
            <p class="w-2/3 text-gray-800">
                {{ \Carbon\Carbon::parse($plp->created_at)->format('d M Y H:i') }}
            </p>
        </div>
    </div>
    
    <div class="mt-8 flex justify-between">
        <a href="{{ route('plp.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
            ← Kembali ke Daftar
        </a>
        <a href="{{ route('plp.edit', $plp->nip) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition duration-200">
            Ubah Data
        </a>
    </div>
</div>
@endsection