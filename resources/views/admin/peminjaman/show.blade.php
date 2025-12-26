{{-- File: resources/views/admin/peminjaman/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Detail Transaksi Peminjaman')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">
    
    {{-- 1. HEADER SECTION & STATUS --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center space-x-4">
            <div class="bg-indigo-600 p-4 rounded-2xl shadow-lg shadow-indigo-200">
                <i class="fas fa-file-invoice text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">Detail Transaksi</h1>
                <p class="text-indigo-600 font-bold font-mono uppercase tracking-widest text-sm">#{{ $peminjaman->no_pinjam }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            @php
                $status = $peminjaman->display_status;
                $statusClass = match ($status) {
                    'Dikembalikan' => 'bg-green-100 text-green-700 ring-green-600/20',
                    'Terlambat' => 'bg-red-100 text-red-700 ring-red-600/20',
                    'Dipinjam' => 'bg-amber-100 text-amber-700 ring-amber-600/20',
                    'Menunggu Konfirmasi' => 'bg-blue-100 text-blue-700 ring-blue-600/20', 
                    'Disetujui' => 'bg-indigo-100 text-indigo-700 ring-indigo-600/20',
                    default => 'bg-gray-100 text-gray-700 ring-gray-600/20',
                };
            @endphp
            <span class="px-4 py-2 rounded-2xl text-xs font-black uppercase tracking-tighter ring-1 ring-inset {{ $statusClass }}">
                {{ $status }}
            </span>
        </div>
    </div>

    {{-- 2. INFO CARDS SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- PROFIL PEMINJAM --}}
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 text-gray-50 group-hover:text-indigo-50 transition-colors duration-500">
                <i class="fas fa-user-circle text-8xl"></i>
            </div>
            <h3 class="text-gray-400 font-bold uppercase tracking-widest text-[10px] mb-4">Profil Peminjam</h3>
            <div class="relative z-10 space-y-4">
                <div>
                    <p class="text-xs text-gray-500 font-medium leading-none mb-1">Nama Lengkap</p>
                    <p class="text-lg font-black text-gray-800 leading-tight">{{ $peminjaman->peminjam->nama ?? 'N/A' }}</p>
                </div>
                <div class="inline-flex items-center bg-indigo-50 px-3 py-1 rounded-xl">
                    <i class="fas fa-id-card text-indigo-400 mr-2 text-xs"></i>
                    <span class="text-xs font-bold text-indigo-600">{{ $peminjaman->nim }}</span>
                </div>
            </div>
        </div>

        {{-- WAKTU TRANSAKSI --}}
       {{-- Ganti bagian INFO CARDS SECTION pada Kartu ke-2 (Detail Logistik) --}}

<div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
    <h3 class="text-gray-400 font-bold uppercase tracking-widest text-[10px] mb-4 text-center md:text-left">Detail Logistik</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center md:text-left">
            <p class="text-xs text-gray-500 font-medium mb-1">Tanggal Pinjam</p>
            <p class="text-sm font-black text-gray-800">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</p>
            <p class="text-[10px] font-bold text-gray-400 italic">Pukul {{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format('H:i') }} WIB</p>
        </div>
        <div class="text-center md:text-left border-y md:border-y-0 md:border-x border-gray-100 py-4 md:py-0 md:px-6">
            <p class="text-xs text-gray-500 font-medium mb-1">Batas Pengembalian</p>
            <p class="text-sm font-black @if($peminjaman->is_late) text-red-600 @else text-gray-800 @endif">
                {{ \Carbon\Carbon::parse($peminjaman->due_date_time)->format('d M Y') }}
            </p>
            <p class="text-[10px] font-bold text-gray-400 italic">Pukul 16:00 WIB</p>
        </div>
        <div class="text-center md:text-left flex flex-col justify-center">
            @if($peminjaman->is_late)
                <div class="bg-red-50 text-red-600 p-2 rounded-2xl text-center border border-red-100 animate-pulse">
                    <p class="text-[9px] font-black uppercase tracking-tighter leading-none mb-1">Masa Pinjam</p>
                    <p class="text-xs font-bold uppercase">Melewati Batas</p>
                </div>
            @else
                {{-- MENGGANTI KATA 'AMAN' --}}
                <div class="bg-indigo-50 text-indigo-600 p-2 rounded-2xl text-center border border-indigo-100">
                    <i class="fas fa-history text-xs mb-1"></i>
                    <p class="text-[9px] font-black uppercase tracking-tighter leading-none mb-1"></p>
                    <p class="text-xs font-bold uppercase">Status Durasi</p>
                </div>
            @endif
        </div>
    </div>
</div>
    </div>

    {{-- 3. DAFTAR ALAT SECTION --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-black text-gray-700 uppercase tracking-widest">Inventaris yang Dibawa</h3>
            <span class="bg-white px-3 py-1 rounded-full text-[10px] font-bold text-gray-500 shadow-sm border">
                {{ count($peminjaman->detailPeminjaman) }} Item
            </span>
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="hidden lg:block">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b border-gray-50">
                        <th class="px-6 py-4 font-bold uppercase tracking-tighter text-[10px]">Informasi Alat</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-tighter text-[10px] text-center">Jumlah</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-tighter text-[10px]">Catatan Kondisi</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-tighter text-[10px] text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($peminjaman->detailPeminjaman as $detail)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="bg-gray-100 h-10 w-10 rounded-xl flex items-center justify-center text-gray-400 group-hover:bg-white group-hover:text-indigo-500 transition-all shadow-inner">
                                    <i class="fas fa-toolbox"></i>
                                </div>
                                <div>
                                    <p class="font-black text-gray-800 leading-none mb-1">{{ $detail->tool->nama_alat ?? 'Alat Dihapus' }}</p>
                                    <span class="text-[10px] font-bold text-indigo-400 font-mono tracking-tighter uppercase">{{ $detail->nomor_alat }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg font-black text-xs">{{ $detail->qty }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($detail->keterangan)
                                <p class="text-xs text-gray-600 bg-amber-50 border border-amber-100 px-3 py-2 rounded-xl italic">
                                    "{{ $detail->keterangan }}"
                                </p>
                            @else
                                <span class="text-[10px] font-bold text-gray-300 italic">Tidak ada catatan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="openModal('{{ $detail->nomor_alat }}', '{{ addslashes($detail->keterangan) }}')" 
                                    class="h-9 w-9 bg-white text-indigo-600 rounded-xl border border-gray-100 shadow-sm hover:bg-indigo-600 hover:text-white transition-all duration-300">
                                <i class="fas fa-pen-nib text-xs"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARD VIEW --}}
        <div class="lg:hidden divide-y divide-gray-50">
            @foreach ($peminjaman->detailPeminjaman as $detail)
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-start">
                    <div class="flex items-center space-x-3">
                        <div class="bg-indigo-50 h-10 w-10 rounded-xl flex items-center justify-center text-indigo-500">
                            <i class="fas fa-toolbox"></i>
                        </div>
                        <div>
                            <p class="font-black text-gray-800 leading-none mb-1">{{ $detail->tool->nama_alat ?? 'Alat Dihapus' }}</p>
                            <span class="text-[10px] font-bold text-gray-400 font-mono tracking-tighter">{{ $detail->nomor_alat }}</span>
                        </div>
                    </div>
                    <span class="bg-indigo-600 text-white px-2 py-1 rounded-lg font-black text-[10px]">{{ $detail->qty }} PCS</span>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-4 flex justify-between items-center border border-gray-100 shadow-inner">
                    <div class="pr-4">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 leading-none">Kondisi</p>
                        <p class="text-xs italic text-gray-600 leading-tight">
                            {{ $detail->keterangan ?? 'Belum ada catatan...' }}
                        </p>
                    </div>
                    <button onclick="openModal('{{ $detail->nomor_alat }}', '{{ addslashes($detail->keterangan) }}')" 
                            class="bg-white text-indigo-600 h-8 w-8 rounded-lg shadow-sm border border-gray-100">
                        <i class="fas fa-pen-nib text-[10px]"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- 4. FOOTER ACTIONS --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4 px-2">
        <a href="{{ route('peminjaman.index') }}" 
           class="group flex items-center text-gray-400 hover:text-gray-800 transition-colors font-bold text-sm">
            <i class="fas fa-long-arrow-alt-left mr-2 group-hover:-translate-x-1 transition-transform"></i> 
            Kembali ke Daftar
        </a>

        @if ($peminjaman->status === 'Dipinjam' || $peminjaman->display_status === 'Terlambat')
            <form action="{{ route('peminjaman.update', $peminjaman->no_pinjam) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Konfirmasi pengembalian transaksi ini?')">
                @csrf @method('PUT')
                <input type="hidden" name="action_type" value="confirm_return">
                <button type="submit" class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-600 text-white font-black text-xs uppercase tracking-widest px-8 py-4 rounded-2xl shadow-lg shadow-emerald-100 transition-all active:scale-95">
                    <i class="fas fa-check-double mr-2"></i> Konfirmasi Pengembalian
                </button>
            </form>
        @endif
    </div>
</div>

{{-- MODAL POP-UP --}}
<div id="modalKet" class="fixed inset-0 z-[9999] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-[40px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <form action="{{ route('detailpeminjaman.update', [$peminjaman->no_pinjam, 'placeholder_alat']) }}" method="POST" id="formUpdateKet">
                @csrf @method('PUT')
                <div class="bg-white px-8 pt-8 pb-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="bg-indigo-100 p-3 rounded-2xl text-indigo-600">
                            <i class="fas fa-comment-medical text-xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-800 tracking-tight">Catatan Kondisi</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Instruksi</p>
                            <p class="text-xs text-gray-600 leading-relaxed">Berikan catatan detail jika alat dalam kondisi rusak, kotor, atau ada part yang hilang saat dikembalikan.</p>
                        </div>
                        <textarea name="keterangan" id="modal_keterangan" rows="4" 
                                  class="w-full px-5 py-4 border border-gray-100 bg-gray-50 rounded-3xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner outline-none text-sm" 
                                  placeholder="Contoh: Baut agak kendor, alat sudah dibersihkan..."></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-8 py-6 flex flex-col sm:flex-row-reverse gap-3 mt-4">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl shadow-lg shadow-indigo-100 transition-all active:scale-95">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeModal()" class="w-full bg-white text-gray-400 hover:text-gray-600 font-bold text-xs uppercase tracking-widest py-4 rounded-2xl border border-gray-200 transition-all">
                        Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(nomorAlat, keterangan) {
        const modal = document.getElementById('modalKet');
        const form = document.getElementById('formUpdateKet');
        const textArea = document.getElementById('modal_keterangan');

        let actionUrl = "{{ route('detailpeminjaman.update', [$peminjaman->no_pinjam, ':nomor_alat']) }}";
        form.action = actionUrl.replace(':nomor_alat', nomorAlat);

        textArea.value = (keterangan === 'null' || keterangan === '') ? '' : keterangan;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // stop scroll background
    }

    function closeModal() {
        document.getElementById('modalKet').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>

@endsection