@extends('layouts.app')

@section('title', 'Katalog Alat Laboratorium')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-xl">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b pb-4">
        
        {{-- HEADER --}}
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 mb-1">Katalog Alat Lab</h2>
            <p class="text-md text-gray-600">Pilih alat yang Anda butuhkan dan masukkan ke keranjang.</p>
        </div>

        {{-- 🚩 WRAPPER PENCARIAN & FILTER --}}
        @php
            $search = $search ?? ''; 
            $currentCategory = $currentCategory ?? ''; 
            $baseRoute = route('peminjam.tools.index');
            
            $manualCategories = ['Cutting Tools', 'Measure', 'Mesin'];
            $categoriesToUse = collect($categories ?? [])
                                ->merge($manualCategories)
                                ->unique()
                                ->sort()
                                ->all();
                                
            $activeLabel = $currentCategory ?: 'Semua Kategori'; 
        @endphp

        <div class="flex flex-col sm:flex-row w-full sm:w-auto mt-4 sm:mt-0 space-y-3 sm:space-y-0 sm:space-x-3">
            
            <form action="{{ $baseRoute }}" method="GET" class="w-full sm:w-64 flex items-center">
                @if ($currentCategory)
                    <input type="hidden" name="category" value="{{ $currentCategory }}">
                @endif
                
                <div class="relative w-full">
                    <input type="text" name="search" placeholder="Cari Kode/Nama Alat..." value="{{ $search }}"
                           class="w-full p-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm pr-10">
                    <button type="submit" class="absolute right-0 top-0 mt-2.5 mr-3 text-gray-500 hover:text-indigo-600">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <div class="relative w-full sm:w-auto" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" 
                        class="inline-flex items-center justify-center w-full rounded-md shadow-lg px-4 py-2 bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 transform hover:scale-[1.02]">
                    <i class="fas fa-filter mr-2"></i> 
                    {{ $activeLabel }}
                    <i class="fas fa-chevron-down ml-2 -mr-1 text-xs transition-transform" :class="{'transform rotate-180': open}"></i>
                </button>

                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="origin-top-right absolute left-0 sm:right-0 mt-2 w-full sm:w-56 rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-10 focus:outline-none z-20">
                    
                    <div class="py-1">
                        @php
                            $resetUrl = $baseRoute;
                            if ($search) { $resetUrl .= '?search=' . urlencode($search); }
                        @endphp
                        <a href="{{ $resetUrl }}" 
                           class="block px-4 py-2 text-sm @if(!$currentCategory) bg-indigo-50 text-indigo-700 font-semibold @else text-gray-700 hover:bg-gray-100 @endif"
                           @click="open = false">
                            <i class="fas fa-th-large mr-2"></i> Semua Alat
                        </a>

                        <div class="border-t border-gray-100 mt-1">
                            @foreach ($categoriesToUse as $category)
                                @php
                                    $categoryUrl = $baseRoute . '?category=' . urlencode($category);
                                    if ($search) { $categoryUrl .= '&search=' . urlencode($search); }
                                @endphp
                                <a href="{{ $categoryUrl }}" 
                                   class="block px-4 py-2 text-sm @if($currentCategory == $category) bg-indigo-50 text-indigo-700 font-semibold @else text-gray-700 hover:bg-gray-100 @endif"
                                   @click="open = false">
                                    {{ $category }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            @if ($search || $currentCategory)
                 <a href="{{ $baseRoute }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2.5 px-4 rounded-xl shadow-md transition duration-200 w-full sm:w-auto mt-3 sm:mt-0 flex items-center justify-center">
                    <i class="fas fa-undo-alt mr-1"></i> Reset
                </a>
            @endif

        </div>
    </div>
    
    {{-- Notifikasi --}}
    @if (session('keranjang_success'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4">
            <i class="fas fa-check-circle mr-2"></i> {{ session('keranjang_success') }}
            <a href="{{ route('peminjam.keranjang.index') }}" class="font-bold underline ml-2 hover:text-blue-900">Lihat Keranjang</a>
        </div>
    @endif
    
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Grid Kartu (Responsive) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        
        @forelse ($tools as $tool)
            @php
                $stok = $tool->stok ?? 0;
                $stok_color = ($stok > 5) ? 'bg-green-600' : (($stok > 0) ? 'bg-yellow-600' : 'bg-red-600');
                $isDisabled = ($stok == 0);
            @endphp
            
            <div class="bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl hover:border-indigo-500 transform hover:scale-[1.03]">
                
                {{-- Area Gambar --}}
                <div class="h-40 bg-gray-100 relative flex items-center justify-center">
                    @if ($tool->gambar)
                        <img src="{{ asset($tool->gambar) }}" alt="{{ $tool->nama_alat }}" 
                               class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-500">
                            <i class="fas fa-flask text-5xl"></i>
                        </div>
                    @endif
                    
                    {{-- Badge Stok (Tetap di Kanan Atas) --}}
                    <span class="absolute top-3 right-3 px-3 py-1 text-xs font-bold text-white rounded-full shadow-md z-10 {{ $stok_color }}">
                        Stok: {{ $stok }}
                    </span>
                    
                    {{-- 💡 PERBAIKAN: Kategori dipindahkan ke KIRI BAWAH area gambar --}}
                    @if ($tool->kategori)
                    <span class="absolute bottom-3 left-3 max-w-[80%] px-2.5 py-1 text-[11px] font-black uppercase tracking-wider text-indigo-700 bg-white/90 backdrop-blur-sm border border-indigo-100 rounded shadow-sm truncate z-10" title="{{ $tool->kategori }}">
                        {{ $tool->kategori }}
                    </span>
                    @endif
                </div>
                
                {{-- Detail Alat --}}
                <div class="p-4 flex-grow">
                    <h3 class="text-base font-extrabold text-gray-900 mb-1 leading-tight line-clamp-2 h-10">{{ $tool->nama_alat }}</h3>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">KODE: {{ $tool->nomor_alat }}</p>
                </div>

                {{-- Aksi (Add to Cart) --}}
                <div class="p-3 border-t bg-gray-50/50">
                    <form action="{{ route('peminjam.keranjang.store') }}" method="POST" class="flex justify-between items-center space-x-2">
                        @csrf
                        <input type="hidden" name="nomor_alat" value="{{ $tool->nomor_alat }}">
                        
                        <div class="relative flex-grow">
                            <input type="number" name="qty" value="1" min="1" max="{{ $stok }}" 
                                   class="w-full pl-8 pr-2 py-1.5 border border-gray-300 rounded-lg text-sm font-bold focus:ring-2 focus:ring-indigo-500 @if($isDisabled) bg-gray-200 @endif" 
                                   required 
                                   @if($isDisabled) disabled @endif>
                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[9px] font-black text-gray-400">QTY</span>
                        </div>

                        <button type="submit" 
                                 class="w-10 h-10 flex items-center justify-center rounded-lg font-bold shadow-md transition-all active:scale-95
                                 @if($isDisabled) 
                                    bg-gray-300 text-gray-500 cursor-not-allowed
                                 @else 
                                    bg-indigo-600 text-white hover:bg-indigo-700 hover:shadow-indigo-500/50
                                 @endif"
                                 @if($isDisabled) disabled @endif>
                            <i class="fas fa-cart-plus text-sm"></i> 
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-10 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <i class="fas fa-search-minus text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-600">Tidak ada alat yang tersedia.</p>
                <a href="{{ $baseRoute }}" class="text-sm text-indigo-500 hover:text-indigo-700 mt-2 block font-bold">Tampilkan Semua Alat</a>
            </div>
        @endforelse
    </div>
</div>
@endsection