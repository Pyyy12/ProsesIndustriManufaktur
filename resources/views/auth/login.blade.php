<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PIM Politeknik STMI</title>
    <script src="https://cdn.tailwindcss.com"></script> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* Mengatur background dan font dasar */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8; /* Warna latar belakang lembut */
        }
        /* Styling untuk logo/icon */
        .logo-container {
            width: 80px;
            height: 80px;
            background-color: #2563eb; /* Biru Primer STMI */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.4);
            margin: 0 auto 1.5rem;
        }
        .logo-container i {
            color: white;
            font-size: 32px;
        }
        /* Custom input focus color */
        input:focus {
            --tw-ring-color: #2563eb;
            border-color: #2563eb;
        }
    </style>
</head>
<body>

<div class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-white p-8 sm:p-10 rounded-2xl shadow-2xl transition duration-500 hover:shadow-3xl">
        
        {{-- LOGO & HEADING --}}
        <div class="text-center">
            <div class="logo-container">
                <i class="fas fa-industry"></i> 
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
                Sistem Peminjaman Alat (PIM)
            </h2>
            <p class="text-sm text-gray-500 mb-8">
                Politeknik STMI Jakarta
            </p>
        </div>

        {{-- 1. DISPLAY ERROR UMUM --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-4 text-sm">
                <strong class="font-bold">Gagal Login!</strong>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        {{-- 2. DISPLAY SUCCESS MESSAGE --}}
        @if (Session::get('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-6 text-sm font-semibold">
                <i class="fas fa-user-check mr-2"></i> {{ Session::get('success') }}
            </div>
        @endif

        {{-- FORM LOGIN --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">NIM / NIP</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-user text-gray-400"></i>
                    </span>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                        placeholder="Masukkan NIM atau NIP">
                </div>
            </div>

            {{-- BLOK TANGGAL LAHIR DIBUAT 3 INPUT TEXT --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir (DD/MM/YYYY)</label>
                
                <div class="flex space-x-2">
                    
                    {{-- DD (Hari) --}}
                    <div class="relative w-1/4">
                        <input type="text" name="day" id="day" value="{{ old('day') }}" required maxlength="2"
                            class="w-full text-center py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            placeholder="DD">
                    </div>

                    {{-- MM (Bulan) --}}
                    <div class="relative w-1/4">
                        <input type="text" name="month" id="month" value="{{ old('month') }}" required maxlength="2"
                            class="w-full text-center py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            placeholder="MM">
                    </div>

                    {{-- YYYY (Tahun) --}}
                    <div class="relative w-1/2">
                        <input type="text" name="year" id="year" value="{{ old('year') }}" required maxlength="4"
                            class="w-full text-center py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            placeholder="YYYY">
                    </div>
                </div>
                
                {{-- INPUT TERSEMBUNYI UNTUK MENGIRIM DATA DALAM FORMAT YYYY-MM-DD --}}
                <input type="hidden" name="tgl_lahir" id="tgl_lahir_hidden">
                
                <p class="text-xs text-gray-500 mt-1">Gunakan format 2 digit untuk Hari/Bulan (misal: 01, 05).</p>

            </div>
            {{-- AKHIR BLOK 3 INPUT TEXT --}}

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-lg shadow-blue-300/50 transition duration-300 transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-blue-500/50">
                <i class="fas fa-sign-in-alt mr-2"></i> MASUK KE SISTEM
            </button>
        </form>

        {{-- FOOTER INFO --}}
        <p class="mt-8 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Politeknik STMI Jakarta. Program Studi TIO & TRO.
        </p>

    </div>
</div>

{{-- SCRIPT JAVASCRIPT UNTUK MENGGABUNGKAN INPUT TANGGAL --}}
<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        const day = document.getElementById('day').value.trim();
        const month = document.getElementById('month').value.trim();
        const year = document.getElementById('year').value.trim();
        const hiddenInput = document.getElementById('tgl_lahir_hidden');

        // Fungsi untuk memastikan angka memiliki 2 digit (misal: 01, 05)
        const pad = (num) => num.padStart(2, '0');

        // Validasi dasar
        if (day === '' || month === '' || year === '' || year.length !== 4) {
            alert("Mohon masukkan tanggal lahir lengkap dalam format DD/MM/YYYY.");
            event.preventDefault();
            return;
        }
        
        // Gabungkan dan format menjadi YYYY-MM-DD (format yang dibutuhkan Laravel/PHP)
        // Gunakan pad untuk hari dan bulan jika pengguna hanya memasukkan 1 digit
        const formattedDay = pad(day);
        const formattedMonth = pad(month);
        
        hiddenInput.value = `${year}-${formattedMonth}-${formattedDay}`;
    });
</script>

</body>
</html>