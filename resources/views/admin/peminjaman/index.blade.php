{{-- File: resources/views/admin/peminjaman/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Kelola Transaksi Peminjaman')

@section('content')
<div class="bg-white p-4 sm:p-6 rounded-xl shadow-2xl">
    
    {{-- HEADER JUDUL & TOMBOL AKSI --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b pb-3 gap-4">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-800 flex items-center">
            <i class="fas fa-file-invoice mr-3 text-indigo-600"></i> Daftar Transaksi Peminjaman (Admin/PLP)
        </h2>

        {{-- 💡 TOMBOL EXPORT EXCEL --}}
        <a href="{{ route('peminjaman.export') }}" 
           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition duration-200 text-sm transform hover:scale-[1.02]">
            <i class="fas fa-file-excel mr-2"></i> Export Excel
        </a>
    </div>

    {{-- 🔍 FITUR SEARCH --}}
    <div class="mb-6">
        <form action="{{ route('peminjaman.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
            <div class="relative flex-grow">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-200"
                       placeholder="Cari No. Pinjam, NIM, atau Nama Peminjam...">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition duration-200 text-sm">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('peminjaman.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition duration-200 text-sm text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    {{-- 1. TABEL VIEW (Desktop) --}}
    <div class="hidden lg:block overflow-x-auto border border-gray-200 rounded-xl">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-indigo-50">
                <tr>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider whitespace-nowrap rounded-tl-xl">No. Pinjam</th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider whitespace-nowrap">Peminjam</th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider whitespace-nowrap">Tgl. Pinjam</th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider whitespace-nowrap">Batas Kembali</th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider whitespace-nowrap">Status</th>
                    <th class="px-4 sm:px-6 py-3 text-center text-xs font-semibold text-indigo-800 uppercase tracking-wider whitespace-nowrap rounded-tr-xl">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100 text-gray-700 text-sm">
                @forelse ($peminjamans as $peminjaman)
                <tr class="hover:bg-indigo-50/50 transition duration-150">
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold text-gray-900">{{ $peminjaman->no_pinjam }}</td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm">
                        {{ $peminjaman->peminjam->nama ?? 'N/A' }} 
                        <span class="text-gray-500 block text-xs">({{ $peminjaman->nim }})</span>
                    </td>
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                    
                    {{-- Batas Kembali --}}
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm @if($peminjaman->display_status === 'Terlambat (Belum Kembali)') font-bold text-red-600 @else text-gray-600 @endif">
                        {{ $peminjaman->due_date_time }} WIB
                        @if($peminjaman->display_status === 'Terlambat (Belum Kembali)')
                            <span class="block text-xs font-normal text-red-500 uppercase">Segera Kembalikan</span>
                        @endif
                    </td>
                    
                    {{-- Status Badge --}}
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                        @php
                            $status = $peminjaman->display_status;
                            $dbStatus = $peminjaman->status; // Status asli di DB
                            $statusClass = match ($dbStatus) {
                                'Dikembalikan' => 'bg-green-100 text-green-800 border-green-400',
                                'Terlambat' => 'bg-red-100 text-red-800 border-red-400', // Status Terlambat di DB (Sudah Kembali tapi telat)
                                'Dipinjam' => 'bg-yellow-100 text-yellow-800 border-yellow-400',
                                'Ditolak' => 'bg-gray-100 text-gray-500 border-gray-300',
                                default => 'bg-blue-100 text-blue-800 border-blue-400', 
                            };

                            // Override class jika sedang dipinjam tapi terlambat secara waktu
                            if ($status === 'Terlambat (Belum Kembali)') {
                                $statusClass = 'bg-orange-100 text-orange-800 border-orange-400 animate-pulse';
                            }
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }} border">
                            {{ $status }}
                        </span>
                    </td>
                    
                    {{-- Kolom Aksi --}}
                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex space-x-2 justify-center items-center">
                            
                            {{-- 1. Tombol Detail --}}
                            <a href="{{ route('peminjaman.show', $peminjaman->no_pinjam) }}" title="Lihat Detail" 
                               class="w-7 h-7 flex items-center justify-center rounded-full bg-indigo-50 hover:bg-indigo-100 text-indigo-600 transition duration-150 transform hover:scale-110">
                                <i class="fas fa-eye text-xs"></i>
                            </a>

                            {{-- 2. AKSI: KONFIRMASI PEMINJAMAN (Hanya untuk Pengajuan Baru) --}}
                            @if ($dbStatus === NULL)
                                <form action="{{ route('peminjaman.update', $peminjaman->no_pinjam) }}" method="POST" onsubmit="return confirm('Setujui peminjaman ini?');">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="action_type" value="confirm_borrow"> 
                                    <button type="submit" title="Setujui Peminjaman" class="w-7 h-7 flex items-center justify-center rounded-full bg-green-100 hover:bg-green-200 text-green-600 transition duration-150 transform hover:scale-110">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                </form>
                            @endif
                            
                            {{-- 3. AKSI: KONFIRMASI PENGEMBALIAN (Hanya jika status 'Dipinjam') --}}
                            @if ($dbStatus === 'Dipinjam')
                                <form action="{{ route('peminjaman.update', $peminjaman->no_pinjam) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian?');">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="action_type" value="confirm_return">
                                    <button type="submit" title="Konfirmasi Pengembalian" 
                                            class="w-7 h-7 flex items-center justify-center rounded-full bg-orange-100 hover:bg-orange-200 text-orange-600 transition duration-150 transform hover:scale-110">
                                        <i class="fas fa-undo-alt text-xs"></i>
                                    </button>
                                </form>
                            @endif
                            
                            {{-- 4. Tombol Hapus (Hanya muncul jika belum selesai/belum dikembalikan/belum terlambat permanen) --}}
                            @if (!in_array($dbStatus, ['Dikembalikan', 'Terlambat', 'Ditolak']))
                                <form action="{{ route('peminjaman.destroy', $peminjaman->no_pinjam) }}" method="POST" onsubmit="return confirm('Yakin hapus transaksi ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Hapus Permanen" class="w-7 h-7 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition duration-150 transform hover:scale-110">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-300 italic text-[10px]">Selesai</span>
                            @endif

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-lg text-gray-500 bg-gray-50/50">
                        <i class="fas fa-search text-4xl mb-3 text-gray-400"></i>
                        <p>Tidak ada transaksi peminjaman ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 2. CARD VIEW (Mobile & Tablet) --}}
    <div class="lg:hidden space-y-4 pt-4">
        @forelse ($peminjamans as $peminjaman)
            @php 
                $dbStatus = $peminjaman->status; 
                $status = $peminjaman->display_status;
            @endphp
            <div class="bg-white p-4 rounded-xl shadow-lg border-l-4 @if($status === 'Terlambat (Belum Kembali)') border-red-500 @else border-indigo-400 @endif">
                
                <div class="flex justify-between items-start mb-3 border-b pb-2">
                    <p class="text-base font-bold text-gray-900 flex flex-col">
                        <span class="text-xs font-medium text-indigo-600 uppercase">No. Pinjam</span>
                        {{ $peminjaman->no_pinjam }}
                    </p>
                    <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full border 
                        {{ $dbStatus === 'Dikembalikan' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $dbStatus === 'Terlambat' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $dbStatus === 'Dipinjam' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $dbStatus === NULL ? 'bg-blue-100 text-blue-800' : '' }}">
                        {{ $status }}
                    </span>
                </div>
                
                <div class="space-y-2 text-sm">
                    <p class="text-gray-800"><span class="font-semibold text-gray-600">Peminjam:</span> {{ $peminjaman->peminjam->nama ?? 'N/A' }}</p>
                    <p class="text-gray-800"><span class="font-semibold text-gray-600">Pinjam:</span> {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</p>
                    <p class="text-gray-800 @if($status === 'Terlambat (Belum Kembali)') font-bold text-red-600 @endif">
                        <span class="font-semibold text-gray-600">Batas:</span> {{ $peminjaman->due_date_time }} WIB
                    </p>
                </div>
                
                <div class="flex justify-end space-x-3 pt-3 border-t mt-4">
                    <a href="{{ route('peminjaman.show', $peminjaman->no_pinjam) }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                        <i class="fas fa-eye text-xs"></i>
                    </a>

                    @if ($dbStatus === NULL)
                        <form action="{{ route('peminjaman.update', $peminjaman->no_pinjam) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="action_type" value="confirm_borrow"> 
                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-check text-xs"></i>
                            </button>
                        </form>
                    @endif
                    
                    @if ($dbStatus === 'Dipinjam')
                        <form action="{{ route('peminjaman.update', $peminjaman->no_pinjam) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="action_type" value="confirm_return">
                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-orange-100 text-orange-600">
                                <i class="fas fa-undo-alt text-xs"></i>
                            </button>
                        </form>
                    @endif

                    @if (!in_array($dbStatus, ['Dikembalikan', 'Terlambat', 'Ditolak']))
                        <form action="{{ route('peminjaman.destroy', $peminjaman->no_pinjam) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-600">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-10 px-6 text-center text-lg text-gray-500 bg-gray-50/50 rounded-xl shadow-lg">
                <i class="fas fa-search text-4xl mb-3 text-gray-400"></i>
                <p>Tidak ada transaksi peminjaman ditemukan.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection

{{-- Script Toast tetap sama seperti sebelumnya --}}
@push('scripts')
<div id="toast-container" class="fixed top-4 right-4 z-[99999] space-y-2"></div>
<script>
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        const styles = {
            success: { bg: 'bg-green-500', border: 'border-green-700', icon: 'fa-check-circle' },
            error: { bg: 'bg-red-500', border: 'border-red-700', icon: 'fa-times-circle' },
            warning: { bg: 'bg-yellow-500', border: 'border-yellow-700', icon: 'fa-info-circle' }
        };
        const style = styles[type] || styles.warning;
        toast.className = `p-4 max-w-xs rounded-xl shadow-xl transition-all duration-300 ease-out transform translate-x-full opacity-0 ${style.bg} text-white border-b-4 ${style.border} text-sm font-semibold cursor-pointer flex items-center`;
        toast.innerHTML = `<i class="fas ${style.icon} mr-2"></i><span>${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => { toast.classList.remove('translate-x-full', 'opacity-0'); toast.classList.add('translate-x-0', 'opacity-100'); }, 10);
        const removeToast = () => { toast.classList.remove('translate-x-0', 'opacity-100'); toast.classList.add('translate-x-full', 'opacity-0'); setTimeout(() => { toast.remove(); }, 300); };
        setTimeout(removeToast, 5000);
        toast.addEventListener('click', removeToast);
    }
    document.addEventListener('DOMContentLoaded', function() {
        const sessionSuccess = "{{ session('success') }}";
        const sessionError = "{{ session('error') }}";
        if (sessionSuccess) showToast(sessionSuccess, 'success');
        if (sessionError) showToast(sessionError, 'error');
    });
</script>
@endpush