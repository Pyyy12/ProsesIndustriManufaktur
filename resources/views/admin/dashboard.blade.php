@extends('layouts.app')

@section('title', 'Dashboard Admin PLP')

@section('content')

{{-- 
    ========================================================================
    POP-UP MODAL LOGIN BERHASIL
    ========================================================================
--}}
@if (Session::get('success'))
<div id="login-success-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md transform scale-100 transition duration-300">
        <div class="text-center">
            <i class="fas fa-check-circle text-green-500 text-6xl mb-4 animate-pulse"></i>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Login Berhasil!</h3>
            <p class="text-lg text-gray-700 font-semibold mb-4">{!! Session::get('success') !!}</p>
            <p class="text-sm text-gray-500">Selamat datang kembali di sistem PIM.</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('login-success-modal');
        if (modal) {
            setTimeout(() => {
                modal.style.opacity = '0';
                setTimeout(() => { modal.remove(); }, 300); 
            }, 2500); 
        }
    });
</script>
@endif

<div class="p-2 sm:p-4 md:p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-3">Statistik Sistem</h2>
    
    {{-- Grid Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-8">
        
        {{-- Card 1: Total Peminjam --}}
        <a href="{{ route('peminjam.index') }}" class="block">
            <div class="bg-white p-3 sm:p-4 rounded-xl shadow-lg hover:shadow-xl border-l-4 border-purple-600 transform hover:translate-y-[-2px] transition duration-200">
                <p class="text-xs font-medium text-gray-500 uppercase truncate">Peminjam Terdaftar</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-0.5">{{ $total_peminjam_count ?? 0 }}</p> 
                <p class="text-xs text-purple-600 mt-1"><i class="fas fa-user-graduate mr-1"></i> Lihat Data</p>
            </div>
        </a>

        {{-- Card 2: Transaksi Terlambat (Selesai tapi telat) --}}
        <a href="{{ route('peminjaman.index', ['search' => 'Terlambat']) }}" class="block">
            <div class="bg-white p-3 sm:p-4 rounded-xl shadow-lg hover:shadow-xl border-l-4 border-red-600 transform hover:translate-y-[-2px] transition duration-200">
                <p class="text-xs font-medium text-gray-500 uppercase truncate">Total Terlambat</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-0.5">{{ $transaksi_terlambat_count ?? 0 }}</p> 
                <p class="text-xs text-red-600 mt-1">
                    <i class="fas fa-history mr-1"></i> Riwayat Keterlambatan
                </p>
            </div>
        </a>

        {{-- Card 3: Peminjaman Aktif (Sedang Dipinjam) --}}
        <a href="{{ route('peminjaman.index', ['search' => 'Dipinjam']) }}" class="block">
            <div class="bg-white p-3 sm:p-4 rounded-xl shadow-lg hover:shadow-xl border-l-4 border-yellow-600 transform hover:translate-y-[-2px] transition duration-200">
                <p class="text-xs font-medium text-gray-500 uppercase truncate">Sedang Dipinjam</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-0.5">{{ $transaksi_aktif_count ?? 0 }}</p>
                <p class="text-xs text-yellow-600 mt-1">
                    <i class="fas fa-hourglass-half mr-1"></i> Pantau Alat
                </p>
            </div>
        </a>
        
        {{-- Card 4: Total Alat Lab --}}
        <a href="{{ route('tools.index') }}" class="block">
            <div class="bg-white p-3 sm:p-4 rounded-xl shadow-lg hover:shadow-xl border-l-4 border-blue-600 transform hover:translate-y-[-2px] transition duration-200">
                <p class="text-xs font-medium text-gray-500 uppercase truncate">Total Jenis Alat</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-0.5">{{ $total_tools_count ?? 0 }}</p>
                <p class="text-xs text-blue-600 mt-1"><i class="fas fa-tools mr-1"></i> Kelola Inventaris</p>
            </div>
        </a>
    </div>
    
    <hr class="my-6 border-gray-300">

    {{-- ANALISIS TRANSAKSI --}}
    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-chart-bar mr-2 text-indigo-600"></i> Analisis Tren Peminjaman (6 Bulan Terakhir)
    </h2>

    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-xl border border-gray-200">
        <div class="h-72 sm:h-96 w-full relative">
            <canvas id="adminPeminjamanChart"></canvas>
            
            @php
                $chartLabels = $chartLabels ?? [];
                $chartData = $chartData ?? [];
                $hasValidData = count($chartData) > 0 && array_sum($chartData) > 0;
            @endphp

            @if(!$hasValidData)
                <div class="absolute inset-0 flex items-center justify-center text-center text-gray-500 bg-white bg-opacity-70 rounded-xl">
                    <div class="p-4">
                        <i class="fas fa-exclamation-circle text-4xl mb-2"></i>
                        <p>Data transaksi selesai tidak tersedia untuk grafik.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('adminPeminjamanChart');
        const phpLabels = {!! json_encode($chartLabels) !!};
        const phpData = {!! json_encode($chartData) !!};

        if (ctx && phpData.length > 0) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: phpLabels,
                    datasets: [{
                        label: 'Transaksi Selesai',
                        data: phpData,
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 3,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection