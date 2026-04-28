<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter - Klinik Drg. Noviandri</title>

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

        .nav-link:hover, .nav-link.active {
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

        .admin-layout {
            transition: all 0.3s ease;
        }

        .admin-sidebar {
            transition: width 0.3s ease, padding 0.3s ease, opacity 0.2s ease;
            overflow: hidden;
        }

        .sidebar-toggle-btn {
            background-color: #FFFFFF;
            border: 1px solid rgba(234, 148, 29, 0.2);
            color: #EA941D;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .sidebar-toggle-btn:hover {
            background-color: #FFF7ED;
        }

        .sidebar-toggle-icon {
            transition: transform 0.3s ease;
        }

        body.sidebar-collapsed .admin-sidebar {
            width: 0;
            padding-left: 0;
            padding-right: 0;
            opacity: 0;
            border: 0;
            margin-right: 0;
        }

        body.sidebar-collapsed .sidebar-toggle-icon {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="min-h-screen text-gray-800 flex relative overflow-hidden">

    <div class="w-full flex h-screen p-4 sm:p-6 gap-6 relative z-10 admin-layout">
        <button id="sidebarToggle" type="button" class="sidebar-toggle-btn absolute top-8 left-8 z-20 w-10 h-10 rounded-xl flex items-center justify-center transition-all">
            <svg class="sidebar-toggle-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Sidebar -->
        <aside class="admin-sidebar w-64 h-full flex flex-col glass-panel rounded-3xl p-6 shrink-0">
            <!-- Logo area -->
            <div class="flex flex-col items-center mb-6 pt-2">
                <div class="w-14 h-14 rounded-2xl bg-orange-500 shadow-lg shadow-orange-500/30 flex items-center justify-center text-white mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <h2 class="font-extrabold text-xl tracking-tight text-gray-900">Dashboard Dokter</h2>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-0.5">drg.Noviandri</p>
                <div class="w-full border-b border-gray-200 mt-4 relative"></div>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto pr-2 scrollbar-none">
                <div class="sidebar-section">KELOLA ANTRIAN</div>
                <a href="{{ route('dashboard.dokter') }}" class="nav-link font-semibold {{ request()->routeIs('dashboard.dokter') ? 'bg-orange-50 text-orange-500 shadow-sm' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Monitor Antrian
                </a>
            </div>

            <!-- User Info & Logout -->
            <div class="mt-4 pt-4 border-t border-gray-200/50">
                <!-- <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold">
                        {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'D' }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 leading-none">{{ auth()->check() ? auth()->user()->name : 'Dokter' }}</p>
                        <p class="text-xs text-green-600 font-semibold mt-1 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Online
                        </p>
                    </div>
                </div> -->
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
        <main class="flex-1 h-full min-h-0 overflow-y-auto pb-10 pr-4 w-full max-w-7xl mx-auto">
            @yield('content')
        </main>
    </div>

    <script>
        (function () {
            const body = document.body;
            const toggleButton = document.getElementById('sidebarToggle');
            const storageKey = 'dokter-sidebar-collapsed';

            if (!toggleButton) return;

            const applyState = (isCollapsed) => {
                body.classList.toggle('sidebar-collapsed', isCollapsed);
            };

            applyState(localStorage.getItem(storageKey) === '1');

            toggleButton.addEventListener('click', () => {
                const nextState = !body.classList.contains('sidebar-collapsed');
                applyState(nextState);
                localStorage.setItem(storageKey, nextState ? '1' : '0');
            });
        })();
    </script>
    
    @stack('scripts')
</body>
</html>
