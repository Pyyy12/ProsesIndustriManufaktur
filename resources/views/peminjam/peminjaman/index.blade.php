@extends('layouts.app')

@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="bg-white p-4 sm:p-6 rounded-xl shadow-2xl border border-gray-100">
    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-800 mb-6 border-b pb-3 flex items-center">
        <i class="fas fa-history mr-3 text-indigo-600"></i> Riwayat Peminjaman Alat
    </h2>
    
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        
        {{-- Form Pencarian --}}
        <form action="{{ route('peminjam.peminjaman.index') }}" method="GET" class="w-full sm:w-1/3">
            <div class="relative">
                <input type="text" name="search" placeholder="Cari No. Pinjam atau Nama PLP..." 
                       value="{{ $search ?? '' }}"
                       class="w-full p-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm pr-10">
                <button type="submit" class="absolute right-0 top-0 mt-2.5 mr-3 text-gray-500 hover:text-indigo-600">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        {{-- Tombol Aksi & Total --}}
        <div class="flex items-center space-x-4 w-full sm:w-auto justify-between sm:justify-end">
             <p class="text-base text-gray-600 font-semibold">Total: {{ $peminjamans->count() }} Transaksi</p>
             <a href="{{ route('peminjam.tools.index') }}" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-3 sm:px-4 rounded-xl transition duration-200 shadow-lg transform hover:scale-[1.02] text-sm">
                <i class="fas fa-plus mr-1"></i> Ajukan Baru
             </a>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- TAMPILAN DESKTOP (MD:TABLE) --}}
    {{-- ================================================================= --}}
    <div class="hidden md:block relative shadow-md rounded-lg overflow-hidden border border-gray-200">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="py-3 px-6">No. Pinjam</th>
                    <th scope="col" class="py-3 px-6">Tgl Pinjam</th>
                    <th scope="col" class="py-3 px-6">Diverifikasi PLP</th>
                    <th scope="col" class="py-3 px-6 text-center">Status</th>
                    <th scope="col" class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($peminjamans as $pinjam)
                    <tr class="bg-white border-b hover:bg-indigo-50/50 transition duration-100">
                        <td class="py-4 px-6 font-semibold text-gray-900 whitespace-nowrap">{{ $pinjam->no_pinjam }}</td>
                        <td class="py-4 px-6 text-sm">{{ \Carbon\Carbon::parse($pinjam->tanggal)->format('d M Y') }}</td>
                        <td class="py-4 px-6 text-sm">{{ $pinjam->plp->nama ?? 'Menunggu Verifikasi' }}</td>
                        
                        {{-- Status --}}
                        <td class="py-4 px-6 text-center">
                            @php
                                $statusDisplay = $pinjam->display_status ?? 'N/A';
                                $statusClass = match ($statusDisplay) {
                                    'Dipinjam' => 'bg-yellow-200 text-yellow-800 border-yellow-500',
                                    'Dikembalikan' => 'bg-green-200 text-green-800 border-green-500',
                                    'Terlambat' => 'bg-red-200 text-red-800 border-red-500',
                                    'Menunggu Konfirmasi' => 'bg-blue-200 text-blue-800 border-blue-500',
                                    default => 'bg-gray-200 text-gray-800 border-gray-500',
                                };
                            @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $statusClass }} shadow-sm">
                                {{ $statusDisplay }}
                            </span>
                        </td>
                        
                        {{-- Aksi --}}
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('peminjam.peminjaman.show', $pinjam->no_pinjam) }}" 
                               class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-3 rounded-lg text-xs transition duration-200 shadow-md transform hover:scale-105">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-gray-500 bg-gray-50/70">
                            <i class="fas fa-search-minus text-4xl mb-3 text-gray-400"></i><br>
                            <p class="text-lg font-semibold">Riwayat peminjaman tidak ditemukan.</p>
                            @if (isset($search) || isset($currentCategory))
                                <p class="text-sm">Coba bersihkan filter atau kata kunci pencarian.</p>
                            @else
                                <a href="{{ route('peminjam.tools.index') }}" class="text-indigo-600 hover:underline mt-2 inline-block">
                                    Mulai Ajukan Peminjaman
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ================================================================= --}}
    {{-- TAMPILAN MOBILE (MD:HIDDEN) - Card List --}}
    {{-- ================================================================= --}}
    <div class="block md:hidden space-y-4">
        @forelse ($peminjamans as $pinjam)
            @php
                $statusDisplay = $pinjam->display_status ?? 'N/A';
                $statusColor = match ($statusDisplay) {
                    'Dipinjam' => 'border-yellow-500 bg-yellow-50',
                    'Dikembalikan' => 'border-green-500 bg-green-50',
                    'Terlambat' => 'border-red-500 bg-red-50',
                    'Menunggu Konfirmasi' => 'border-blue-500 bg-blue-50',
                    default => 'border-gray-500 bg-gray-50',
                };
                $badgeClass = match ($statusDisplay) {
                    'Dipinjam' => 'text-yellow-800 bg-yellow-200 border-yellow-500',
                    'Dikembalikan' => 'text-green-800 bg-green-200 border-green-500',
                    'Terlambat' => 'text-red-800 bg-red-200 border-red-500',
                    'Menunggu Konfirmasi' => 'text-blue-800 bg-blue-200 border-blue-500',
                    default => 'text-gray-800 bg-gray-200 border-gray-500',
                };
            @endphp
            
            <div class="p-4 border-l-4 rounded-lg shadow-md {{ $statusColor }} flex flex-col space-y-2">
                
                {{-- Baris 1: No. Pinjam & Status --}}
                <div class="flex justify-between items-start border-b border-gray-200 pb-2">
                    <span class="text-sm font-bold text-gray-900">
                        <i class="fas fa-receipt mr-1 text-indigo-500"></i> No: {{ $pinjam->no_pinjam }}
                    </span>
                    <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $badgeClass }} shadow-sm">
                        {{ $statusDisplay }}
                    </span>
                </div>
                
                {{-- Baris 2: Tanggal & PLP --}}
                <div class="text-xs text-gray-700">
                    <p class="truncate"><i class="far fa-calendar-alt w-4 mr-1"></i> **Tgl. Pinjam:** {{ \Carbon\Carbon::parse($pinjam->tanggal)->format('d M Y') }}</p>
                    <p class="mt-1 truncate"><i class="fas fa-user-check w-4 mr-1"></i> **Oleh PLP:** {{ $pinjam->plp->nama ?? 'Menunggu Verifikasi' }}</p>
                </div>

                {{-- Baris 3: Aksi --}}
                <div class="pt-2 border-t border-gray-200 text-right">
                    <a href="{{ route('peminjam.peminjaman.show', $pinjam->no_pinjam) }}" 
                       class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-3 rounded-lg text-xs transition duration-200 shadow-md transform hover:scale-105">
                        <i class="fas fa-eye mr-1"></i> Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="py-10 text-center text-gray-500 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <i class="fas fa-box-open text-4xl mb-3 text-gray-400"></i><br>
                <p class="text-lg font-semibold">Anda belum memiliki riwayat peminjaman.</p>
                <a href="{{ route('peminjam.tools.index') }}" class="text-indigo-600 hover:underline mt-2 inline-block">
                    Mulai Ajukan Peminjaman
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection