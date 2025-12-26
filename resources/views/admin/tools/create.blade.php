@extends('layouts.app')

@section('title', 'Tambah Alat')

@section('content')
<div class="bg-white p-6 sm:p-8 rounded-xl shadow-2xl max-w-lg mx-auto border border-gray-100">
    <h2 class="text-3xl font-extrabold text-indigo-700 mb-6 border-b pb-3 flex items-center">
        <i class="fas fa-plus-circle mr-3 text-2xl"></i> Tambah Alat Laboratorium Baru
    </h2>
    
    {{-- Display Errors --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-6 shadow-sm">
            <strong class="font-bold">Terdapat Kesalahan Input:</strong>
            <ul class="mt-1 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Tambah Alat --}}
    <form action="{{ route('tools.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        {{-- KODE ALAT / NOMOR ALAT --}}
        <div>
            <label for="nomor_alat" class="block text-gray-700 text-sm font-semibold mb-2">Nomor Alat / Kode Aset <span class="text-red-500">*</span></label>
            <input type="text" name="nomor_alat" id="nomor_alat" value="{{ old('nomor_alat') }}" required
                   placeholder="Contoh: TIO-MCH-001"
                   class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition @error('nomor_alat') border-red-500 @enderror">
        </div>

        {{-- NAMA ALAT --}}
        <div>
            <label for="nama_alat" class="block text-gray-700 text-sm font-semibold mb-2">Nama Alat <span class="text-red-500">*</span></label>
            <input type="text" name="nama_alat" id="nama_alat" value="{{ old('nama_alat') }}" required
                   placeholder="Contoh: Mikrometer Sekrup Digital"
                   class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition @error('nama_alat') border-red-500 @enderror">
        </div>
        
        {{-- KATEGORI (Dropdown Select) --}}
        <div>
            <label for="kategori" class="block text-gray-700 text-sm font-semibold mb-2">Kategori <span class="text-red-500">*</span></label>
            <select name="kategori" id="kategori" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition @error('kategori') border-red-500 @enderror">
                <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>-- Pilih Kategori Alat --</option>
                <option value="Measure" {{ old('kategori') == 'Measure' ? 'selected' : '' }}>Measure (Alat Ukur)</option>
                <option value="Cutting Tools" {{ old('kategori') == 'Cutting Tools' ? 'selected' : '' }}>Cutting Tools (Alat Potong)</option>
                <option value="Mesin" {{ old('kategori') == 'Mesin' ? 'selected' : '' }}>Mesin (Machines)</option>
                <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>

        {{-- STOK AWAL --}}
        <div>
            <label for="stok" class="block text-gray-700 text-sm font-semibold mb-2">Stok Awal <span class="text-red-500">*</span></label>
            <input type="number" name="stok" id="stok" value="{{ old('stok', 1) }}" required min="0"
                   class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition @error('stok') border-red-500 @enderror">
        </div>

        {{-- UPLOAD GAMBAR --}}
        <div>
            <label for="gambar" class="block text-gray-700 text-sm font-semibold mb-2">Upload Gambar (Opsional)</label>
            <input type="file" name="gambar" id="gambar" 
                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition duration-150 @error('gambar') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks: 2MB.</p>
        </div>

        {{-- Aksi --}}
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <a href="{{ route('tools.index') }}" 
               class="font-semibold text-gray-600 hover:text-gray-800 transition duration-200 py-2 px-4">
                <i class="fas fa-chevron-left mr-1"></i> Batal
            </a>
            
            <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition duration-200 transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                <i class="fas fa-save mr-1"></i> Simpan Alat Baru
            </button>
        </div>
    </form>
</div>
@endsection