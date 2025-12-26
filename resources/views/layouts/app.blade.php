<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIM | @yield('title')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* CSS Ditingkatkan untuk Aesthetics & Interactivity */
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
        }

        /* 💡 Sidebar Styling yang lebih tajam dan modern */
        .sidebar { 
            width: var(--sidebar-width); 
            transform: translateX(-100%); 
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 6px 0 20px rgba(0, 0, 0, 0.3); /* Shadow yang lebih elegan */
        }
        
        /* Margin untuk Konten Area */
        .content-area { 
            margin-left: 0;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Desktop State (Default Expanded) */
        @media (min-width: 1025px) {
            .sidebar {
                transform: translateX(0);
            }
            .content-area {
                margin-left: var(--sidebar-width);
            }
            
            /* STATE COLLAPSED */
            .sidebar.collapsed {
                width: var(--sidebar-collapsed-width);
            }
            .content-area.collapsed {
                margin-left: var(--sidebar-collapsed-width);
            }
            /* Menyembunyikan teks menu saat collapsed */
            .sidebar.collapsed .sidebar-text,
            .sidebar.collapsed .logo-text-full {
                display: none !important;
            }
            
            /* STABILITAS LOGO SAAT COLLAPSED */
            .sidebar.collapsed .logo-link {
                justify-content: center !important;
            }
            .sidebar.collapsed .logo-header {
                padding: 8px 0 !important; 
            }
            .sidebar.collapsed .logo-icon {
                width: 48px !important; 
                height: 48px !important;
            }
            /* Ikon menu yang sejajar saat collapsed */
            .sidebar.collapsed nav a {
                justify-content: center;
                padding: 12px 0;
            }
        }
        
        /* Mobile/Open State */
        .sidebar.open {
            transform: translateX(0);
        }

        /* Active Link Styling (Lebih menonjol) */
        .sidebar a.active {
            background-color: #3b82f6; /* Blue-500 */
            color: white;
            border-left: 4px solid #fcd34d; /* Kuning/Amber */
            box-shadow: 0 2px 10px rgba(59, 130, 246, 0.5); /* Shadow halus untuk active state */
            transform: scale(1.01); /* Efek subtle */
        }

        /* Hover Link Styling yang lebih gelap */
        .sidebar a:not(.active):hover {
            background-color: #1f2937; /* Gray-800 */
            color: #ffffff; /* Putih */
            transform: translateX(2px); /* Efek subtle bergeser ke kanan */
        }

        /* Dropdown Styling */
        .dropdown-menu {
            transition: opacity 0.15s ease, transform 0.15s ease;
            transform: translateY(5px);
            opacity: 0;
            pointer-events: none;
        }
        .dropdown-menu.active {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        
        /* STATE AKTIF UNTUK TOMBOL TOGGLE */
        .toggle-active {
            background-color: #e5e7eb !important; /* bg-gray-200 */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .toggle-active i {
            color: #4f46e5; /* Indigo-600 */
        }
        
        /* HILANGKAN HOVER SCALE PADA IKON TOGGLE */
        .navbar-toggle-btn {
            /* Menghapus semua transform saat hover kecuali yang dikelola JS (rotation) */
            transform: none !important;
        }
    </style>
</head>
<body class="bg-gray-50">

    @if (session()->has('user_type'))
        <aside id="sidebar" class="sidebar fixed top-0 left-0 h-full bg-gray-900 text-white z-50">
            
            {{-- HEADER LOGO --}}
            <div class="p-4 border-b border-gray-700 bg-gray-800 flex items-center justify-center h-[64px] logo-header">
                <a href="{{ url('/') }}" class="flex items-center space-x-2 logo-link">
                    {{-- Logo Image --}}
                    <img src="{{ asset('images/logostmi.png') }}" alt="Logo STMI" class="w-10 h-10 rounded-full logo-icon transition-all duration-300">
                    
                    {{-- Teks Politeknik STMI --}}
                    <span class="text-xl font-extrabold text-white tracking-wide logo-text-full transition-opacity duration-300">
                        Politeknik STMI
                    </span>
                </a>
            </div>
            
            <nav class="mt-4 space-y-1 px-4 overflow-y-auto h-[calc(100vh-64px)] pb-10">
                @if (session('user_type') == 'plp')
                    {{-- === MENU ADMIN/PLP === --}}
                    <h3 class="text-xs uppercase text-gray-500 font-semibold mb-2 px-2 pt-2 sidebar-text">Utama</h3>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 rounded-lg text-gray-300 hover:text-white transition duration-200 @if(Request::routeIs('admin.dashboard')) active @endif"><i class="fas fa-chart-line w-6 text-center"></i><span class="ml-3 font-medium sidebar-text">Dashboard</span></a>
                    <a href="{{ route('peminjaman.index') }}" class="flex items-center p-3 rounded-lg text-gray-300 hover:text-white transition duration-200 @if(Request::routeIs('peminjaman.*') && !Request::routeIs('peminjam.*')) active @endif"><i class="fas fa-file-invoice w-6 text-center"></i><span class="ml-3 font-medium sidebar-text">Kelola Peminjaman</span></a>
                    
                    <h3 class="text-xs uppercase text-gray-500 font-semibold mt-6 mb-2 px-2 pt-2 menu-header sidebar-text">Data Master</h3>
                    <a href="{{ route('tools.index') }}" class="flex items-center p-3 rounded-lg text-gray-300 hover:text-white transition duration-200 @if(Request::routeIs('tools.*') && !Request::routeIs('peminjam.tools.*')) active @endif"><i class="fas fa-microscope w-6 text-center"></i><span class="ml-3 font-medium sidebar-text">Alat Lab (Tools)</span></a>
                    <a href="{{ route('peminjam.index') }}" class="flex items-center p-3 rounded-lg text-gray-300 hover:text-white transition duration-200 @if(Request::routeIs('peminjam.*') && !Request::routeIs('peminjam.dashboard')) active @endif"><i class="fas fa-user-graduate w-6 text-center"></i><span class="ml-3 font-medium sidebar-text">Data Peminjam</span></a>
                @else
                    {{-- === MENU PEMINJAM/peminjam === --}}
                    <h3 class="text-xs uppercase text-gray-500 font-semibold mb-2 px-2 pt-2 sidebar-text">Navigasi Peminjam</h3>
                    <a href="{{ route('peminjam.dashboard') }}" class="flex items-center p-3 rounded-lg text-gray-300 hover:text-white transition duration-200 @if(Request::routeIs('peminjam.dashboard')) active @endif"><i class="fas fa-home w-6 text-center"></i><span class="ml-3 font-medium sidebar-text">Dashboard Saya</span></a>
                    <a href="{{ route('peminjam.tools.index') }}" class="flex items-center p-3 rounded-lg text-gray-300 hover:text-white transition duration-200 @if(Request::routeIs('peminjam.tools.*')) active @endif"><i class="fas fa-flask w-6 text-center"></i><span class="ml-3 font-medium sidebar-text">Cari Alat & Katalog</span></a>
                    
                    <h3 class="text-xs uppercase text-gray-500 font-semibold mt-6 mb-2 px-2 pt-2 menu-header sidebar-text">Transaksi</h3>
                    {{-- Keranjang menjadi aktif saat di halaman Keranjang/Create Form --}}
                    <a href="{{ route('peminjam.keranjang.index') }}" class="flex items-center p-3 rounded-lg text-gray-300 hover:text-white transition duration-200 @if(Request::routeIs('peminjam.keranjang.*') || Request::routeIs('peminjam.peminjaman.create') || Request::routeIs('peminjam.peminjaman.store')) active @endif"><i class="fas fa-shopping-basket w-6 text-center"></i><span class="ml-3 font-medium sidebar-text">Keranjang Peminjaman</span></a>
                    
                    {{-- Riwayat Peminjaman hanya aktif saat di index/show --}}
                    <a href="{{ route('peminjam.peminjaman.index') }}" class="flex items-center p-3 rounded-lg text-gray-300 hover:text-white transition duration-200 @if(Request::routeIs('peminjam.peminjaman.index') || Request::routeIs('peminjam.peminjaman.show')) active @endif"><i class="fas fa-history w-6 text-center"></i><span class="ml-3 font-medium sidebar-text">Riwayat Peminjaman</span></a>
                @endif
            </nav>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    @endif
    
    <div id="content-area" class="content-area @if(!session()->has('user_type')) no-sidebar @endif">

        @if (session()->has('user_type'))
            <nav class="bg-white shadow-md p-4 sticky top-0 z-30 border-b border-gray-200 h-[64px]">
                <div class="flex justify-between items-center h-full">
                    
                    {{-- Tombol Toggle Mobile --}}
                    <button id="menu-toggle-mobile" 
                            class="navbar-toggle-btn p-2 rounded-full text-gray-600 transition duration-300 focus:outline-none lg:hidden mr-4" 
                            onclick="toggleSidebar()">
                        <i id="mobile-toggle-icon" class="fas fa-bars text-xl transition-transform duration-300"></i>
                    </button>
                    
                    {{-- Tombol Toggle Desktop (Hamburger) --}}
                    <button id="menu-toggle-desktop-nav" 
                            class="navbar-toggle-btn p-2 rounded-full text-gray-600 transition duration-300 focus:outline-none hidden lg:block mr-4" 
                            onclick="toggleDesktopSidebar()">
                        <i id="desktop-toggle-icon" class="fas fa-bars text-xl transition-transform duration-300"></i>
                    </button>

                    {{-- Dynamic Page Title --}}
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800 flex-grow">@yield('title', 'Aplikasi Peminjaman')</h1>
                    
                    <div class="relative flex items-center space-x-4">
                        
                        {{-- 🔔 KOMPONEN NOTIFIKASI LONCENG --}}
                        <div class="relative">
                            @php
                                $notifCount = $notification_count ?? 0;
                                $notifications = $notifications ?? collect([]);
                            @endphp

                            <button id="notification-menu-button" 
                                    class="p-2 rounded-full text-gray-600 hover:bg-gray-100 hover:scale-110 transition duration-150 focus:outline-none"
                                    onclick="toggleDropdown('notification')">
                                <i class="fas fa-bell text-lg"></i>
                                @if ($notifCount > 0)
                                    {{-- Badge Notifikasi Baru --}}
                                    <span class="absolute top-1 right-1 w-5 h-5 bg-red-600 rounded-full text-white text-xs font-bold flex items-center justify-center border-2 border-white transform scale-90">{{ $notifCount }}</span>
                                @endif
                            </button>

                            <div id="notification-menu" 
                                 class="dropdown-menu absolute right-0 mt-3 w-72 bg-white rounded-lg shadow-xl z-50 border border-gray-100 origin-top-right">
                                
                                <div class="p-3 text-sm font-bold text-gray-800 border-b flex justify-between">
                                    <span>Notifikasi Terbaru ({{ $notifCount }})</span>
                                    @if ($notifCount > 0)
                                        <a href="{{ route('peminjam.peminjaman.index') }}" class="text-xs text-indigo-600 hover:underline font-normal">Lihat Semua</a>
                                    @endif
                                </div>
                                <div class="max-h-60 overflow-y-auto">
                                    @forelse ($notifications as $notif)
                                        @php
                                            $statusText = match ($notif->status) {
                                                'Disetujui' => 'Pengajuan disetujui, siap diambil.',
                                                'Terlambat' => 'Telah melewati batas waktu pengembalian!',
                                                'Ditolak' => 'Pengajuan Anda ditolak oleh PLP.',
                                                default => 'Pembaruan status peminjaman.',
                                            };
                                            $statusColor = match ($notif->status) {
                                                'Terlambat' => 'text-red-700 font-bold',
                                                'Disetujui' => 'text-green-700 font-bold',
                                                'Ditolak' => 'text-red-500',
                                                default => 'text-gray-700',
                                            };
                                        @endphp
                                        <a href="{{ route('peminjam.peminjaman.show', $notif->no_pinjam) }}" 
                                           class="block p-3 text-sm hover:bg-indigo-50 transition duration-100 border-b last:border-b-0">
                                            <p class="font-semibold {{ $statusColor }}">{{ $statusText }}</p>
                                            <p class="text-xs text-gray-500 mt-1"><i class="far fa-clock mr-1"></i> {{ $notif->created_at->diffForHumans() }}</p>
                                        </a>
                                    @empty
                                        <div class="p-3 text-center text-xs text-gray-500 py-6">
                                            <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                            <p>Tidak ada notifikasi yang perlu Anda tangani.</p>
                                        </div>
                                    @endforelse
                                </div>
                                
                            </div>
                        </div>
                        
                        {{-- 👨‍💻 KOMPONEN PROFILE DROPDOWN --}}
                        <div class="relative">
                            @php
                                // FIX: Tentukan rute profil berdasarkan tipe pengguna
                                $profileRoute = (session('user_type') == 'plp') 
                                    ? route('admin.profile.index') 
                                    : route('peminjam.profile.index');
                            @endphp
                            <button id="profile-menu-button" 
                                    class="flex items-center space-x-2 p-2 rounded-full hover:bg-gray-100 transition duration-150 focus:outline-none"
                                    onclick="toggleDropdown('profile')">
                                <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white text-base">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <span class="hidden sm:inline text-gray-700 font-semibold text-sm">
                                    {{ session('user_name') }}
                                </span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>

                            <div id="profile-menu" 
                                 class="dropdown-menu absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-xl z-50 border border-gray-100 origin-top-right">
                                
                                <div class="px-4 py-3 text-sm text-gray-900 border-b">
                                    <div class="font-bold">{{ session('user_name') }}</div>
                                    <div class="text-xs text-gray-500">{{ session('user_id') }}</div>
                                </div>

                                <a href="{{ $profileRoute }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-100">
                                    <i class="fas fa-user-circle mr-2"></i> Lihat Profil
                                </a>
                                
                                {{-- Tombol Logout memicu modal konfirmasi --}}
                                <button type="button" onclick="showGlobalLogoutModal()" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition duration-100 border-t">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar (Logout)
                                </button>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        @endif

        <main class="p-4 sm:p-8">
            {{-- Alert Messages (DIHAPUS KARENA MENGGUNAKAN TOAST GLOBAL) --}}
            {{-- Blok ini harusnya dihapus atau diganti dengan @stack('content-alerts') jika diperlukan --}}
            {{-- Saya akan menghapusnya di kode final di bawah. --}}
            
            @yield('content')
        </main>
    </div>
    
    {{-- GLOBAL POP-UP MODAL KONFIRMASI LOGOUT --}}
    <div id="global-confirm-logout-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-[10000] transition-opacity duration-300 modal-container">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-sm transform scale-100 transition duration-300">
            <div class="text-center">
                <i class="fas fa-sign-out-alt text-red-600 text-5xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Logout</h3>
                <p class="text-gray-700 mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
                
                <form id="global-logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <div class="flex justify-center space-x-4">
                        {{-- Tombol Cancel --}}
                        <button type="button" onclick="hideGlobalLogoutModal()" class="px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition duration-150">
                            Batal
                        </button>
                        
                        {{-- Tombol Konfirmasi Logout (Memicu Submit Form) --}}
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-150 shadow-md">
                            Ya, Logout
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- JavaScript untuk Interaksi Sidebar & Dropdown --}}
    <script>
        // Memastikan fungsi-fungsi global tersedia
        window.showGlobalLogoutModal = function() {
            // Tutup dropdown profil jika terbuka sebelum membuka modal
            const profileMenu = document.getElementById('profile-menu');
            if (profileMenu) profileMenu.classList.remove('active'); 
            
            document.getElementById('global-confirm-logout-modal').classList.remove('hidden');
        }

        window.hideGlobalLogoutModal = function() {
            document.getElementById('global-confirm-logout-modal').classList.add('hidden');
        }

        // Fungsi untuk mengelola rotasi ikon
        function setToggleIconRotation(iconElement, isSidebarOpenOrExpanded) {
            if (!iconElement) return;
            // Rotasi ikon 90 derajat saat terbuka/expanded (untuk desktop)
            if (isSidebarOpenOrExpanded) {
                iconElement.style.transform = 'rotate(90deg)';
            } else {
                iconElement.style.transform = 'rotate(0deg)';
            }
        }
        
        // Fungsi untuk mengelola styling tombol toggle aktif
        function setToggleButtonStyle(buttonElement, isActive) {
            if (!buttonElement) return;
            
            // 1. Atur kelas aktif (warna latar belakang)
            if (isActive) {
                buttonElement.classList.add('toggle-active');
            } else {
                buttonElement.classList.remove('toggle-active');
            }
            
            // 2. Kontrol Hover (Membalik logika hover)
            if (isActive) {
                 // Nonaktifkan efek hover bg-gray-100 jika sudah aktif (sidebar terbuka)
                buttonElement.classList.remove('hover:bg-gray-100');
            } else {
                 // Aktifkan efek hover bg-gray-100 jika tidak aktif (sidebar tertutup/collapsed)
                buttonElement.classList.add('hover:bg-gray-100');
            }
        }


        // Fungsi Toggle Sidebar Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const button = document.getElementById('menu-toggle-mobile');
            const icon = document.getElementById('mobile-toggle-icon');
            
            sidebar.classList.toggle('open');
            const isOpen = sidebar.classList.contains('open');

            if (isOpen) {
                overlay.classList.remove('hidden');
            } else {
                overlay.classList.add('hidden');
            }
            
            // Efek Rotasi & Styling Tombol Mobile (Logika Dibalik: !isOpen)
            setToggleIconRotation(icon, isOpen);
            setToggleButtonStyle(button, isOpen);
        }
        
        // Fungsi Toggle Sidebar Desktop (Collapse/Expand)
        function toggleDesktopSidebar() {
            const sidebar = document.getElementById('sidebar');
            const contentArea = document.getElementById('content-area');
            const sidebarText = document.querySelectorAll('.sidebar-text');
            const logoTextFull = document.querySelector('.logo-text-full');
            const logoIcon = document.querySelector('.logo-icon');
            const button = document.getElementById('menu-toggle-desktop-nav');
            const icon = document.getElementById('desktop-toggle-icon');
            
            // Toggle collapsed state
            const isCollapsed = sidebar.classList.toggle('collapsed');
            contentArea.classList.toggle('collapsed');
            
            // Sembunyikan/Tampilkan teks menu dan logo teks penuh
            sidebarText.forEach(item => {
                if (isCollapsed) {
                    item.classList.add('hidden');
                } else {
                    item.classList.remove('hidden');
                }
            });
            if (logoTextFull) logoTextFull.classList.toggle('hidden');

            // Ubah ukuran ikon logo untuk collapsed state
            if (isCollapsed) {
                if (logoIcon) {
                    logoIcon.classList.remove('w-10', 'h-10');
                    logoIcon.classList.add('w-12', 'h-12'); 
                }
            } else {
                if (logoIcon) {
                    logoIcon.classList.remove('w-12', 'h-12');
                    logoIcon.classList.add('w-10', 'h-10');
                }
            }
            
            // Efek Rotasi & Styling Tombol Desktop (Logika Dibalik: isCollapsed)
            // isCollapsed = true artinya tombol AKTIF
            setToggleIconRotation(icon, !isCollapsed); 
            setToggleButtonStyle(button, isCollapsed); // Tombol active jika sidebar collapsed
        }

        // 🔔 Fungsi Toggle Dropdown yang lebih general (dapat menutup dropdown lain)
        function toggleDropdown(targetId) {
            const menuProfile = document.getElementById('profile-menu');
            const menuNotification = document.getElementById('notification-menu');
            const targetMenu = document.getElementById(targetId + '-menu');

            // Tutup semua menu yang aktif kecuali target
            if (menuProfile && menuProfile.classList.contains('active') && targetId !== 'profile') {
                menuProfile.classList.remove('active');
            }
            if (menuNotification && menuNotification.classList.contains('active') && targetId !== 'notification') {
                menuNotification.classList.remove('active');
            }

            // Toggle target menu
            if (targetMenu) {
                targetMenu.classList.toggle('active');
            }
        }

        // Tutup dropdown ketika mengklik di luar
        document.addEventListener('click', function (event) {
            const profileButton = document.getElementById('profile-menu-button');
            const notificationButton = document.getElementById('notification-menu-button');
            const profileMenu = document.getElementById('profile-menu');
            const notificationMenu = document.getElementById('notification-menu');
            
            // Cek apakah klik berasal dari luar tombol dan luar menu
            const isClickOutsideProfile = profileMenu && profileButton && !profileButton.contains(event.target) && !profileMenu.contains(event.target);
            const isClickOutsideNotification = notificationMenu && notificationButton && !notificationButton.contains(event.target) && !notificationMenu.contains(event.target);

            if (profileMenu && profileMenu.classList.contains('active') && isClickOutsideProfile) {
                profileMenu.classList.remove('active');
            }
            if (notificationMenu && notificationMenu.classList.contains('active') && isClickOutsideNotification) {
                notificationMenu.classList.remove('active');
            }
        });
        
        // INITIALIZATION AND RESIZE LOGIC
        function initializeDesktopState() {
            const sidebar = document.getElementById('sidebar');
            const contentArea = document.getElementById('content-area');
            const sidebarText = document.querySelectorAll('.sidebar-text');
            const logoTextFull = document.querySelector('.logo-text-full');
            const logoIcon = document.querySelector('.logo-icon');
            const desktopButton = document.getElementById('menu-toggle-desktop-nav');
            const desktopIcon = document.getElementById('desktop-toggle-icon');
            const mobileButton = document.getElementById('menu-toggle-mobile');
            const mobileIcon = document.getElementById('mobile-toggle-icon');

            // Hapus semua state collapse di mobile
            if (window.innerWidth <= 1024) {
                 sidebar.classList.remove('collapsed');
                 contentArea.classList.remove('collapsed');
                 sidebarText.forEach(item => item.classList.remove('hidden'));
                 if (logoTextFull) logoTextFull.classList.remove('hidden');
                 if (logoIcon) {
                     logoIcon.classList.remove('w-12', 'h-12');
                     logoIcon.classList.add('w-10', 'h-10');
                 }
                 // Reset styling tombol di mobile
                 setToggleIconRotation(desktopIcon, false);
                 setToggleButtonStyle(desktopButton, false);
                 setToggleIconRotation(mobileIcon, false);
                 setToggleButtonStyle(mobileButton, false);
            } else {
                // Inisialisasi default expanded state di desktop
                const isSidebarCollapsed = sidebar.classList.contains('collapsed');

                if (!isSidebarCollapsed) {
                    sidebar.classList.remove('collapsed');
                    contentArea.classList.remove('collapsed');
                    sidebarText.forEach(item => item.classList.remove('hidden'));
                    if (logoTextFull) logoTextFull.classList.remove('hidden');
                    if (logoIcon) {
                        logoIcon.classList.remove('w-12', 'h-12');
                        logoIcon.classList.add('w-10', 'h-10');
                    }
                    // Atur ikon desktop ke state expanded (active = false)
                    setToggleIconRotation(desktopIcon, true);
                    setToggleButtonStyle(desktopButton, false);
                } else {
                    // Jika memang collapsed, atur styling collapsed (active = true)
                    setToggleIconRotation(desktopIcon, false);
                    setToggleButtonStyle(desktopButton, true);
                }
            }
        }
        
        // Panggil saat DOM dimuat
        window.addEventListener('DOMContentLoaded', initializeDesktopState);
        // Panggil saat Resize
        window.addEventListener('resize', initializeDesktopState);
    </script>
    
    {{-- Load Chart.js for Content Area --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    @stack('scripts') 


</body>
</html>