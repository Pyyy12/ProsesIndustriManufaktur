@extends('layouts.app')

@section('title', 'Ubah Alat')

@section('content')
<div class="bg-white p-6 sm:p-8 rounded-2xl shadow-3xl border border-gray-100 max-w-lg mx-auto">
    
    <h2 class="text-2xl font-extrabold text-gray-900 mb-6 border-b pb-3 flex items-center">
        <i class="fas fa-edit mr-3 text-indigo-600"></i> Ubah Data Alat: {{ $tool->nama_alat }}
    </h2>
    
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
            <strong class="font-bold">Gagal Memperbarui!</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tools.update', $tool) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
            
            {{-- Nomor Alat / Kode Aset --}}
            <div class="form-group">
                <label for="nomor_alat" class="block text-gray-700 text-sm font-medium mb-1">Nomor Alat / Kode Aset</label>
                <input type="text" name="nomor_alat" id="nomor_alat" value="{{ old('nomor_alat', $tool->nomor_alat) }}" required
                       class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm p-2.5 bg-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nomor_alat') border-red-500 @enderror">
                @error('nomor_alat') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nama Alat --}}
            <div class="form-group">
                <label for="nama_alat" class="block text-gray-700 text-sm font-medium mb-1">Nama Alat</label>
                <input type="text" name="nama_alat" id="nama_alat" value="{{ old('nama_alat', $tool->nama_alat) }}" required
                       class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm p-2.5 bg-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nama_alat') border-red-500 @enderror">
                @error('nama_alat') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>
            
            {{-- KATEGORI DROPDOWN DINAMIS --}}
            <div class="form-group">
                <label for="kategori" class="block text-gray-700 text-sm font-medium mb-1">Kategori</label>
                @php
                    $currentKategori = old('kategori', $tool->kategori);
                    $kategoriList = collect($categories ?? [])->unique()->sort()->all();
                    
                    // Daftar kategori yang ingin ditampilkan secara eksplisit
                    $explicitCategories = ['Mesin', 'Cutting Tools'];
                    $displayedCategories = [];
                @endphp
                
                <select name="kategori" id="kategori" 
                       class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm p-2.5 bg-white text-gray-700 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('kategori') border-red-500 @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    
                    {{-- Tampilkan kategori eksplisit --}}
                    @foreach ($explicitCategories as $expCat)
                        @php
                            // Tambahkan ke daftar yang sudah ditampilkan
                            $displayedCategories[] = $expCat;
                        @endphp
                        <option value="{{ $expCat }}" {{ $currentKategori == $expCat ? 'selected' : '' }}>
                            {{ $expCat }}
                        </option>
                    @endforeach

                    {{-- Loop Kategori dari Database (yang belum ditampilkan) --}}
                    @foreach ($kategoriList as $category)
                        @if ($category !== null && !in_array($category, $displayedCategories))
                            <option value="{{ $category }}" {{ $currentKategori == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('kategori') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>
            
            {{-- Stok Saat Ini --}}
            <div class="mb-4">
                <label for="stok" class="block text-gray-700 text-sm font-bold mb-2">Stok Saat Ini</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok', $tool->stok) }}" required min="0"
                       class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm p-2.5 bg-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('stok') border-red-500 @enderror">
                @error('stok') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Display Gambar Lama dan Input Gambar Baru --}}
            <div class="border border-gray-200 p-4 rounded-xl bg-gray-50 shadow-inner">
                <p class="block text-gray-700 text-base font-extrabold mb-3 flex items-center">
                    <i class="fas fa-image mr-2 text-indigo-500"></i> Kelola Gambar
                </p>
                
                {{-- Gambar Lama --}}
                @if ($tool->gambar)
                    <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                    <div class="mb-3">
                        <img src="{{ asset($tool->gambar) }}" alt="Gambar {{ $tool->nama_alat }}" class="max-h-40 rounded-lg shadow-md object-cover border border-gray-300">
                    </div>
                @else
                    <p class="text-sm text-gray-500 mb-3">Tidak ada gambar tersimpan.</p>
                @endif

                {{-- Input Gambar Baru --}}
                <label for="gambar_baru" class="block text-gray-700 text-sm font-medium mb-2 mt-4">Upload Gambar BARU (Opsional)</label>
                <input type="file" name="gambar_baru" id="gambar_baru"
                       class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white file:border-0 file:bg-indigo-50 file:py-1.5 file:px-2.5 file:mr-3 hover:file:bg-indigo-100 @error('gambar_baru') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG. Maks: 2MB. Biarkan kosong jika tidak ingin mengganti.</p>
                @error('gambar_baru') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Footer Tombol (Posisi Ditukar) --}}
        <div class="mt-8 flex items-center justify-between pt-4 border-t border-gray-100">
            
            {{-- Tombol Batal (Kiri) --}}
            <a href="{{ route('tools.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg transition duration-200 transform hover:scale-[1.02] hover:bg-gray-100">
                <i class="fas fa-times-circle mr-1"></i> Batal
            </a>

            {{-- Tombol Submit (Kanan) --}}
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 transform hover:scale-[1.02]">
                <i class="fas fa-sync-alt mr-1"></i> Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection