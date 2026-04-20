<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Klinik Sehat</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FDF8F0;
            overflow-x: hidden;
        }
        
        .blob-bg { display: none; } /* Hide blobs */
        
        /* Override glass with solid flat theme */
        .glass-panel {
            background-color: #FFFFFF;
            border: 1px solid rgba(234, 148, 29, 0.1);
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .glass-card {
            background-color: #FFFFFF;
            border: 1px solid rgba(234, 148, 29, 0.1);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            box-shadow: 0 10px 25px -5px rgba(234, 148, 29, 0.15);
            transform: translateY(-2px);
        }

        .text-gradient {
            color: #EA941D;
        }

        .sidebar-section {
            font-size: 0.7rem;
            font-weight: 700;
            color: #9CA3AF;
            letter-spacing: 0.05em;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            padding-bottom: 0.25rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.85rem;
            color: #6B7280;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.25rem;
            border-radius: 0.75rem;
            transition: all 0.2s;
            font-weight: 600;
        }
        
        .nav-link:hover {
            color: #EA941D;
            background-color: #FFF7ED;
        }

        .nav-icon {
            width: 1.25rem;
            height: 1.25rem;
            color: inherit;
        }

        .btn-gradient {
            background-color: #EA941D;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            background-color: #D97706;
            box-shadow: 0 4px 15px rgba(234, 148, 29, 0.25);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="min-h-screen text-gray-800 flex relative overflow-hidden">

    <!-- Decorative Blobs -->
    <div class="blob-bg blob-1"></div>
    <div class="blob-bg blob-2"></div>

    <div class="w-full flex h-screen p-4 sm:p-6 gap-6 relative z-10">
        
        <!-- Sidebar -->
        <aside class="w-64 h-full flex flex-col glass-panel rounded-3xl p-6 shrink-0">
            <!-- Logo area -->
            <div class="flex flex-col items-center mb-6 pt-2">
                <div class="w-14 h-14 rounded-2xl bg-orange-500 shadow-lg shadow-orange-500/30 flex items-center justify-center text-white mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <h2 class="font-extrabold text-xl tracking-tight text-gray-900">Klinik<span class="text-gradient">Sehat</span></h2>
                <div class="w-full border-b border-gray-200 mt-4 relative"></div>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto pr-2 scrollbar-none">
                <a href="{{ route('dashboard.admin') }}" class="nav-link font-semibold {{ request()->routeIs('dashboard.admin') ? 'bg-orange-50 text-orange-500 shadow-sm' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                <div class="sidebar-section">MASTER DATA</div>
                <a href="{{ route('pasien.index') }}" class="nav-link {{ request()->routeIs('pasien.*') ? 'bg-orange-50 text-orange-500 shadow-sm' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Pasien
                </a>
                <a href="{{ route('layanan.index') }}" class="nav-link {{ request()->routeIs('layanan.*') ? 'bg-orange-50 text-orange-500 shadow-sm' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Layanan
                </a>

                <div class="sidebar-section">TRANSAKSI</div>
                <a href="{{ route('pembayaran.index') }}" class="nav-link {{ request()->routeIs('pembayaran.*') ? 'bg-orange-50 text-orange-500 shadow-sm' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Pembayaran
                </a>
                <a href="{{ route('pendaftaran.index') }}" class="nav-link {{ request()->routeIs('pendaftaran.*') ? 'bg-orange-50 text-orange-500 shadow-sm' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Pendaftaran
                </a>

                <div class="sidebar-section">KELOLA ANTRIAN</div>
                <a href="{{ route('antrian.index') }}" class="nav-link {{ request()->routeIs('antrian.*') ? 'bg-orange-50 text-orange-500 shadow-sm' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Antrian
                </a>

                <div class="sidebar-section">PENGATURAN</div>
                <a href="#" class="nav-link">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    Dokter tidak hadir
                </a>
            </div>

            <!-- Logout -->
            <div class="mt-4 pt-4 border-t border-gray-200/50">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-red-600 hover:text-white hover:bg-red-500 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 h-full min-h-0 overflow-y-auto pb-10 pr-4">
            @yield('content')
        </main>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('scripts')
</body>
</html>
