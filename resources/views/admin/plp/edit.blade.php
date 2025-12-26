@extends('layouts.app')

@section('title', 'Ubah PLP')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-xl max-w-lg mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Ubah Data PLP: {{ $plp->nama }}</h2>
    
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('plp.update', $plp->nip) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="nip" class="block text-gray-700 text-sm font-bold mb-2">NIP</label>
            <input type="text" name="nip" id="nip" value="{{ old('nip', $plp->nip) }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nip') border-red-500 @enderror">
        </div>

        <div class="mb-4">
            <label for="nama" class="block text-gray-700 text-sm font-bold mb-2">Nama PLP</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $plp->nama) }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nama') border-red-500 @enderror">
        </div>
        
        <div class="mb-6">
            <label for="tgl_lahir" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Lahir</label>
            <input type="date" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir', $plp->tgl_lahir) }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tgl_lahir') border-red-500 @enderror">
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200">
                Perbarui Data
            </button>
            <a href="{{ route('plp.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection