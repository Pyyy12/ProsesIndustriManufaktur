@extends('layouts.app')

@section('title', 'Keranjang Peminjaman')

@section('content')
<div class="p-4 sm:p-0">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-2">🛒 Keranjang Peminjaman Anda</h1>
    <p class="text-lg text-gray-600 mb-6">
        Daftar alat yang siap Anda ajukan untuk peminjaman.
    </p>

    {{-- Notifikasi Umum (Success/Error dari Redirect) --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-md"><i class="fas fa-check-circle mr-2"></i> {!! session('success') !!}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 shadow-md"><i class="fas fa-times-circle mr-2"></i> {!! session('error') !!}</div>
    @endif

    @if (count($keranjangItems) > 0)
        
        {{-- Tentukan Status Checkout --}}
        @php
            $readyToCheckout = true;
            $itemsWithIssue = [];
            
            foreach ($keranjangItems as $item) {
                // Amankan akses data:
                $qty = $item['qty'] ?? 0;
                $stokTersedia = $item['stok_tersedia'] ?? 0;
                $namaAlat = $item['nama_alat'] ?? 'Item Hilang';
                
                // Cek QTY melebihi stok atau stok gudang 0
                if ($stokTersedia < $qty || $stokTersedia == 0 || $qty < 1) {
                    $readyToCheckout = false;
                    $itemsWithIssue[] = $namaAlat;
                }
            }
            // Hapus duplikasi jika nama alat sama, agar notifikasi lebih rapi
            $itemsWithIssue = array_unique($itemsWithIssue);
        @endphp

        {{-- Notifikasi Blokir Checkout --}}
        @if (!$readyToCheckout)
            <div id="checkout-block-notification" class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded relative mb-6 shadow-md">
                <i class="fas fa-exclamation-triangle mr-2"></i> **Pengajuan Diblokir:** Mohon periksa kembali keranjang Anda. Beberapa item memiliki masalah stok (QTY melebihi stok yang tersedia atau stok gudang 0).
                <ul class="list-disc list-inside ml-4 font-semibold">
                    @foreach ($itemsWithIssue as $namaAlat)
                        <li>{{ $namaAlat }}</li>
                    @endforeach
                </ul>
                Mohon perbaiki atau hapus item tersebut sebelum melanjutkan.
            </div>
        @endif
        
        {{-- Tabel Keranjang --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-4 w-4/12">Alat</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-4 hidden sm:table-cell w-2/12">No. Alat</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6 w-3/12">Jumlah (Qty)</th>
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-6 w-3/12">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($keranjangItems as $item)
                        {{-- Amankan variabel kunci --}}
                        @php
                            $nomorAlat = $item['nomor_alat'] ?? 'ERROR-NA';
                            $stokTersedia = $item['stok_tersedia'] ?? 0;
                            // Cek error untuk styling inline di View
                            $hasQtyError = ($item['qty'] ?? 1) > $stokTersedia || ($item['qty'] ?? 1) < 1;
                        @endphp
                        
                        <tr class="hover:bg-gray-50 transition duration-100">
                            <td class="px-3 py-4 text-sm font-medium text-gray-900 sm:px-4">{{ $item['nama_alat'] ?? 'N/A' }}</td>
                            <td class="px-3 py-4 text-center text-sm text-gray-500 sm:px-4 hidden sm:table-cell">{{ $nomorAlat }}</td>
                            
                            {{-- KOLOM QTY DENGAN FORM UPDATE (Responsive Spacing) --}}
                            <td class="px-3 py-4 text-center sm:px-6">
                                @if ($nomorAlat !== 'ERROR-NA')
                                <form action="{{ route('peminjam.keranjang.update', $nomorAlat) }}" method="POST" class="flex flex-col items-center justify-center space-y-1">
                                    @csrf
                                    @method('PATCH') {{-- Menggunakan PATCH untuk update --}}
                                    
                                    <div class="flex items-center space-x-2">
                                        {{-- Input QTY (Diberi ID Unik) --}}
                                        <input type="number" 
                                               name="qty" 
                                               id="qty-{{ $nomorAlat }}"
                                               value="{{ old('qty', $item['qty'] ?? 1) }}" 
                                               min="1" 
                                               max="{{ $stokTersedia }}"
                                               oninput="checkMaxStock('{{ $nomorAlat }}')"
                                               class="w-16 text-center p-2 rounded-lg text-sm transition duration-150 border
                                               {{ $hasQtyError ? 'border-red-500 focus:ring-red-500' : 'border-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500' }}">
                                        
                                        {{-- Tombol Ubah (Diberi ID Unik) --}}
                                        <button type="submit" 
                                                id="update-btn-{{ $nomorAlat }}"
                                                class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs py-1 px-3 rounded transition duration-150 border border-indigo-200 hover:bg-indigo-100 shadow-sm hover:shadow-md transform hover:scale-[1.05]"
                                                title="Perbarui Jumlah">
                                            <i class="fas fa-edit mr-1"></i> Ubah
                                        </button>
                                    </div>
                                    
                                    {{-- NOTIFIKASI STOK TIDAK CUKUP (Inline Sederhana) --}}
                                    @if ($hasQtyError)
                                        <p class="text-red-500 text-xs font-medium mt-1 w-full max-w-[120px]">
                                            QTY Max: {{ $stokTersedia }}
                                        </p>
                                    @endif
                                </form>
                                @else
                                    <span class="text-red-500 text-xs font-semibold">Error Data</span>
                                @endif
                            </td>
                            
                            {{-- KOLOM HAPUS (Responsive Spacing) --}}
                            <td class="px-3 py-4 text-center text-sm font-medium sm:px-6">
                                @if ($nomorAlat !== 'ERROR-NA')
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('peminjam.keranjang.destroy', $nomorAlat) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                    @csrf
                                    @method('DELETE')
                                    {{-- 💡 PERBAIKAN: Tombol Hapus disamakan dengan Ubah, tetapi warna merah --}}
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 font-semibold text-xs py-1 px-3 rounded transition duration-150 border border-red-200 hover:bg-red-100 shadow-sm hover:shadow-md transform hover:scale-[1.05]" 
                                            title="Hapus Item">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Checkout Button --}}
        <div class="mt-8 text-right">
            @if (!$readyToCheckout)
                 <button disabled class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gray-400 cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i> Pengajuan Diblokir
                </button>
            @else
                {{-- Tombol Checkout yang memicu redirect ke halaman create peminjaman --}}
                <a href="{{ route('peminjam.peminjaman.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-green-600 hover:bg-green-700 transition duration-150 transform hover:scale-[1.03] shadow-green-500/50">
                    <i class="fas fa-paper-plane mr-2"></i> Lanjutkan ke Pengajuan Peminjaman
                </a>
            @endif
        </div>

    @else
        {{-- Keranjang Kosong --}}
        <div class="bg-white p-10 rounded-xl shadow-lg text-center border-2 border-dashed border-gray-300">
            <i class="fas fa-shopping-basket text-5xl text-gray-400 mb-4"></i>
            <p class="text-xl text-gray-600 font-semibold mb-2">Keranjang Anda Kosong</p>
            <p class="text-gray-500">Silakan jelajahi katalog alat untuk mulai menambahkan item.</p>
            <a href="{{ route('peminjam.tools.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800 font-medium">
                <i class="fas fa-search mr-1"></i> Cari Alat Sekarang
            </a>
        </div>
    @endif
</div>

<script>
    function checkMaxStock(nomorAlat) {
        const input = document.getElementById(`qty-${nomorAlat}`);
        const button = document.getElementById(`update-btn-${nomorAlat}`);
        
        // Safety check
        if (!input || !button) return;

        const maxStock = parseInt(input.getAttribute('max'));
        const currentValue = parseInt(input.value);

        // Tentukan apakah QTY Invalid
        const isInvalid = (currentValue > maxStock || currentValue < 1 || isNaN(currentValue));

        // Styling untuk input: Merah jika Invalid
        if (isInvalid) {
            input.classList.remove('border-gray-500', 'focus:border-indigo-500');
            input.classList.add('border-red-500', 'focus:ring-red-500');
        } else {
            input.classList.remove('border-red-500', 'focus:ring-red-500');
            input.classList.add('border-gray-500', 'focus:border-indigo-500');
        }

        // Logika Tombol Ubah: Nonaktif jika tidak valid
        if (isInvalid) {
            button.disabled = true;
            // Menghilangkan efek hover/interaktif
            button.classList.remove('text-indigo-600', 'hover:text-indigo-800', 'border-indigo-200', 'hover:bg-indigo-100', 'shadow-sm', 'hover:shadow-md', 'transform', 'hover:scale-[1.05]');
            // Menambahkan efek nonaktif
            button.classList.add('text-gray-500', 'border-gray-300', 'bg-gray-100', 'cursor-not-allowed', 'shadow-none');
            button.title = 'QTY Tidak Valid';
        } else {
            button.disabled = false;
            // Menambahkan efek interaktif
            button.classList.remove('text-gray-500', 'border-gray-300', 'bg-gray-100', 'cursor-not-allowed', 'shadow-none');
            button.classList.add('text-indigo-600', 'hover:text-indigo-800', 'border-indigo-200', 'hover:bg-indigo-100', 'shadow-sm', 'hover:shadow-md', 'transform', 'hover:scale-[1.05]');
            button.title = 'Perbarui Jumlah';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Panggil fungsi cek saat halaman dimuat untuk semua input
        document.querySelectorAll('input[name="qty"]').forEach(input => {
            const id = input.id;
            if (id && id.startsWith('qty-')) {
                const nomorAlat = id.replace('qty-', '');
                checkMaxStock(nomorAlat);
            }
        });
    });
</script>
@endsection