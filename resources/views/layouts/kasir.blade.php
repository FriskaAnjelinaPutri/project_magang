<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Klinik - Pembayaran</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FDF8F0;
        }
        
        .glass-panel {
            background-color: #FFFFFF;
            border: 1px solid rgba(234, 148, 29, 0.1);
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }
        
        .text-gradient {
            color: #EA941D;
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
<body class="min-h-screen text-gray-800 flex flex-col relative w-full">

    <!-- Top Navigation Bar for Kasir -->
    <nav class="w-full h-20 bg-white border-b border-orange-100 flex items-center justify-between px-6 sm:px-10 z-20 shrink-0 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-orange-500 shadow-md shadow-orange-500/30 flex items-center justify-center text-white">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <div>
                <h2 class="font-extrabold text-xl tracking-tight text-gray-900 leading-tight">Pos Kasir <span class="text-gradient">Klinik Sehat</span></h2>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-0.5">Akses Pembayaran Terpusat</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="hidden sm:block text-right">
                <p class="text-sm font-bold text-gray-800">{{ auth()->check() ? auth()->user()->name : 'Kasir Offline' }}</p>
                <div class="flex items-center justify-end gap-1 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <p class="text-xs font-semibold text-green-600">Terhubung</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-red-600 hover:text-white hover:bg-red-500 transition-all border border-red-100 hover:border-transparent">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-1 w-full max-w-6xl mx-auto p-4 sm:p-8 mt-2">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
