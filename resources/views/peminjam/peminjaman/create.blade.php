@extends('layouts.app')

@section('title', 'Form Pengajuan Peminjaman')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-2xl border border-gray-100 max-w-4xl mx-auto">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">📝 Ajukan Peminjaman Baru</h1>
    <p class="text-md text-gray-600 mb-8">
        Lengkapi detail tujuan peminjaman dan konfirmasi alat yang akan diajukan.
    </p>

    {{-- Error Handling Global --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
            <strong class="font-bold">Gagal Mengajukan!</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 text-sm">{{ session('error') }}</div>
    @endif

    {{-- FORM CHECKOUT UTAMA --}}
    <form action="{{ route('peminjam.peminjaman.store') }}" method="POST" id="peminjaman-form">
        @csrf

        {{-- STEP 1: DETAIL PENERIMA & TUJUAN --}}
        <div class="mb-10">
            <h3 class="text-2xl font-extrabold text-indigo-700 mb-6">1. Detail Penerima & Tujuan</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- A. Data Peminjam (Readonly Card) --}}
                <div class="bg-indigo-50 p-6 md:p-5 rounded-xl border border-indigo-200 shadow-md h-full">
                    <h4 class="text-xl font-extrabold text-indigo-800 mb-3 flex items-center">
                        <i class="fas fa-user-circle mr-2"></i> Data Anda (Peminjam)
                    </h4>
                    <div class="space-y-2 text-base">
                        <p class="text-gray-700"><strong>NIM:</strong> {{ $peminjam->nim ?? 'N/A' }}</p>
                        <p class="text-gray-700"><strong>Nama:</strong> {{ $peminjam->nama ?? 'N/A' }}</p>
                    </div>
                    {{-- Hidden Fields --}}
                    <input type="hidden" name="nim" value="{{ $peminjam->nim ?? '' }}">
                    <input type="hidden" name="no_pinjam" value="PJM-{{ time() }}-{{ rand(100, 999) }}">
                </div>
                
                {{-- B. Detail Tujuan & Admin (Form Inputs) --}}
                <div class="space-y-4">
                    
                    {{-- PROGRAM STUDI (KONTROL UTAMA) --}}
                    <div class="form-group">
                        <label for="program_studi" class="block text-sm font-medium text-gray-700">Program Studi</label>
                        <select name="program_studi_display" id="program_studi" required 
                                class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm p-2 bg-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Pilih Program Studi --</option>
                            <option value="TIO" {{ old('program_studi_display') == 'TIO' ? 'selected' : '' }}>Teknik Industri Otomotif (TIO)</option>
                            <option value="TRO" {{ old('program_studi_display') == 'TRO' ? 'selected' : '' }}>Teknik Rekayasa Otomotif (TRO)</option>
                        </select>
                    </div>

                    {{-- MATA KULIAH (DEPENDEN PADA PRODI) --}}
                    <div class="form-group">
                        <label for="mata_kuliah" class="block text-sm font-medium text-gray-700">Mata Kuliah (Tujuan Penggunaan)</label>
                        <select name="mata_kuliah" id="mata_kuliah" required 
                                class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm p-2 bg-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Pilih Mata Kuliah --</option>
                            {{-- Opsi akan diisi oleh JavaScript --}}
                        </select>
                    </div>
                    
                    {{-- DOSEN PENGAMPU (DEPENDEN PADA PRODI) --}}
                    <div class="form-group">
                        <label for="dosen_pengampu" class="block text-sm font-medium text-gray-700">Dosen Pengampu</label>
                        <select name="dosen_pengampu" id="dosen_pengampu" required 
                                class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm p-2 bg-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Pilih Dosen Pengampu --</option>
                             {{-- Opsi akan diisi oleh JavaScript --}}
                        </select>
                    </div>
                    
                    {{-- KELOMPOK WAKTU PINJAM --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="waktu_pinjam_rencana" class="block text-sm font-medium text-gray-700">Waktu Rencana Pinjam</label>
                            <input type="time" name="waktu_pinjam_rencana" id="waktu_pinjam_rencana" 
                                value="{{ old('waktu_pinjam_rencana', now()->setTimezone('Asia/Jakarta')->format('H:i')) }}" 
                                required 
                                class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm p-2 bg-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="form-group">
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Tanggal Rencana Kembali</label>
                            <input type="date" name="due_date" id="due_date" 
                                value="{{ old('due_date', now()->toDateString()) }}" 
                                required 
                                min="{{ now()->toDateString() }}"
                                class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm p-2 bg-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    
                    {{-- WAKTU RENCANA KEMBALI & PILIH PLP --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="waktu_kembali_rencana" class="block text-sm font-medium text-gray-700">Waktu Rencana Kembali (WIB)</label>
                            <input type="time" name="waktu_kembali_rencana" id="waktu_kembali_rencana" 
                                value="{{ old('waktu_kembali_rencana', '17:00') }}" required 
                                class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm p-2 bg-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <p id="waktu-error" class="text-xs text-red-500 mt-1 hidden">Waktu kembali harus lebih lambat dari waktu pinjam pada hari yang sama.</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="nip" class="block text-sm font-medium text-gray-700">Admin (PLP) Bertanggung Jawab</label>
                            <select name="nip" id="nip" required 
                                    class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm p-2 bg-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">-- Pilih PLP --</option>
                                @foreach ($plps as $plp)
                                    <option value="{{ $plp->nip }}" {{ old('nip') == $plp->nip ? 'selected' : '' }}>
                                        {{ $plp->nama }} ({{ $plp->nip }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 2: DETAIL ORDER (ITEM DI KERANJANG) --}}
        <div class="mb-8">
            <h3 class="text-2xl font-extrabold text-indigo-700 mb-4 border-t pt-4">2. Ringkasan Alat yang Diajukan</h3>

            @if (count($keranjangItems) > 0)
                <div class="overflow-x-auto border rounded-xl shadow-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">No. Alat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Nama Alat</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Qty</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider min-w-[200px]">Keterangan Tambahan (Opsional)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($keranjangItems as $index => $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-800 font-mono">{{ $item['nomor_alat'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $item['nama_alat'] }}</td>
                                <td class="px-4 py-3 text-center text-sm font-bold text-indigo-600">
                                    {{ $item['qty'] }}
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" 
                                            name="alat[{{ $index }}][keterangan]" 
                                            value="{{ old('alat.' . $index . '.keterangan') }}" 
                                            placeholder="Tujuan/lokasi penggunaan alat ini"
                                            class="w-full rounded-md border border-gray-400 shadow-sm p-2 bg-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </td>
                            </tr>
                            {{-- Hidden inputs wajib untuk store method --}}
                            <input type="hidden" name="alat[{{ $index }}][nomor_alat]" value="{{ $item['nomor_alat'] }}">
                            <input type="hidden" name="alat[{{ $index }}][qty]" value="{{ $item['qty'] }}">
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- STEP 3: SUBMIT/CHECKOUT --}}
                <div class="mt-8 flex flex-col-reverse sm:flex-row justify-between items-center pt-4 border-t space-y-3 sm:space-y-0">
                    
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('peminjam.keranjang.index') }}" 
                    class="w-full sm:w-auto inline-flex items-center justify-center text-sm font-bold rounded-xl shadow-md
                                 px-5 py-2.5 border border-gray-300 bg-gray-200 text-gray-700 hover:bg-gray-300 
                                 transition duration-150 transform hover:scale-[1.02]">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Keranjang
                    </a>
                    
                    {{-- Tombol Ajukan (Primary Button Style) --}}
                    <button type="submit" id="submit-button"
                            class="w-full sm:w-auto inline-flex items-center justify-center text-base font-bold rounded-xl shadow-lg 
                                 px-5 py-2.5 border border-transparent text-white bg-indigo-600 hover:bg-indigo-700 
                                 transition duration-150 transform hover:scale-[1.02]">
                        <i class="fas fa-check-circle mr-2"></i> Ajukan Peminjaman Sekarang
                    </button>
                </div>
                
            @else
                {{-- Keranjang Kosong --}}
                <div class="p-8 text-center border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                    <p class="text-xl text-red-600 font-semibold mb-2">Keranjang Peminjaman Kosong!</p>
                    <p class="text-gray-500">Anda harus menambahkan alat ke keranjang terlebih dahulu sebelum mengajukan peminjaman.</p>
                    <a href="{{ route('peminjam.tools.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium">
                        <i class="fas fa-search mr-1"></i> Cari Alat
                    </a>
                </div>
            @endif

        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('peminjaman-form');
        const waktuPinjamInput = document.getElementById('waktu_pinjam_rencana');
        const waktuKembaliInput = document.getElementById('waktu_kembali_rencana');
        const tanggalKembaliInput = document.getElementById('due_date');
        const waktuError = document.getElementById('waktu-error');
        
        // NEW: Elemen Program Studi, Mata Kuliah, dan Dosen
        const prodiInput = document.getElementById('program_studi');
        const mataKuliahSelect = document.getElementById('mata_kuliah');
        const dosenPengampuSelect = document.getElementById('dosen_pengampu');

        // NEW: Daftar mata kuliah statis
        const mataKuliahData = {
            'TIO': [
                'Praktikum Dasar Listrik',
                'Perancangan Sistem Industri',
                'Statistika Industri',
                'Pengendalian Kualitas',
                'Ergonomi dan Perancangan Kerja',
                'Simulasi Sistem Industri'
            ],
            'TRO': [
                'Praktikum Mesin Konversi Energi',
                'Analisis Termodinamika',
                'Sistem Kontrol Otomotif',
                'Mekanika Fluida dan Hidrolik',
                'Perancangan Komponen Otomotif',
                'Uji Kinerja Mesin'
            ]
        };

        // NEW: Daftar Dosen Pengampu statis (dependen pada Prodi)
        const dosenData = {
            'TIO': [
                'Dr. Ir. Budi Santoso',
                'Prof. Dr. Anna Wijaya, S.T., M.Eng.',
                'Ir. Chandra Kirana, M.Sc.',
            ],
            'TRO': [
                'Dr. Eng. Eko Prasetyo',
                'Ir. Siti Rahayu, M.T.',
                'Drs. Dedy Irawan, M.Si.',
            ]
        };
        
        /**
         * Mengisi ulang dropdown Mata Kuliah dan Dosen berdasarkan Program Studi yang dipilih.
         */
        function updateMataKuliahAndDosen() {
            const selectedProdi = prodiInput.value;
            const savedOldMataKuliah = "{{ old('mata_kuliah') }}";
            const savedOldDosen = "{{ old('dosen_pengampu') }}";

            // --- Reset Dropdown ---
            mataKuliahSelect.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';
            dosenPengampuSelect.innerHTML = '<option value="">-- Pilih Dosen Pengampu --</option>';
            
            if (selectedProdi) {
                // 1. Isi Mata Kuliah
                if (mataKuliahData[selectedProdi]) {
                    mataKuliahData[selectedProdi].forEach(mk => {
                        const option = document.createElement('option');
                        option.value = mk;
                        option.textContent = mk;
                        if (savedOldMataKuliah === mk) {
                            option.selected = true;
                        }
                        mataKuliahSelect.appendChild(option);
                    });
                }
                
                // 2. Isi Dosen Pengampu
                if (dosenData[selectedProdi]) {
                    dosenData[selectedProdi].forEach(dosen => {
                        const option = document.createElement('option');
                        option.value = dosen;
                        option.textContent = dosen;
                        if (savedOldDosen === dosen) {
                            option.selected = true;
                        }
                        dosenPengampuSelect.appendChild(option);
                    });
                }

                // 3. Jika tidak ada nilai lama yang disimpan, set default kembali ke '-- Pilih Mata Kuliah/Dosen --'
                if (!savedOldMataKuliah) mataKuliahSelect.value = '';
                if (!savedOldDosen) dosenPengampuSelect.value = '';

            }
        }

        /**
         * Validasi Waktu Pinjam (JS bawaan Anda)
         */
        function validateTime() {
            const pinjamTime = waktuPinjamInput.value;
            const kembaliTime = waktuKembaliInput.value;
            const kembaliDateStr = tanggalKembaliInput.value;

            if (!kembaliDateStr) {
                waktuError.classList.add('hidden');
                return true;
            }

            // Dapatkan tanggal hari ini dalam format YYYY-MM-DD
            const todayStr = new Date().toISOString().split('T')[0];
            
            // Periksa apakah tanggal kembali sama dengan hari ini atau hari pinjam
            const isSameDay = kembaliDateStr === todayStr; 
            
            if (isSameDay) {
                // Jika hari sama, waktu kembali HARUS lebih besar dari waktu pinjam
                if (kembaliTime <= pinjamTime) {
                    waktuError.classList.remove('hidden');
                    return false;
                }
            }
            
            // Jika tanggal kembali di masa depan, validasi waktu tidak diperlukan
            waktuError.classList.add('hidden');
            return true;
        }

        // --- Event Listeners ---
        
        // 1. Trigger saat Program Studi berubah
        prodiInput.addEventListener('change', updateMataKuliahAndDosen);
        
        // 2. Trigger saat waktu berubah
        waktuPinjamInput.addEventListener('change', validateTime);
        waktuKembaliInput.addEventListener('change', validateTime);
        tanggalKembaliInput.addEventListener('change', validateTime);

        // 3. Validasi saat form disubmit
        form.addEventListener('submit', function(event) {
            if (!validateTime()) {
                event.preventDefault(); // Mencegah form disubmit
                waktuError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                alert("Gagal mengajukan: Waktu kembali harus lebih lambat dari waktu pinjam pada tanggal yang sama.");
            }
        });
        
        // --- Initialization ---

        // Panggil update saat load untuk mengisi data lama atau default
        updateMataKuliahAndDosen(); 
        
        // Panggil validasi sekali saat load untuk cek default value
        validateTime();
    });
</script>
@endpush
@endsection