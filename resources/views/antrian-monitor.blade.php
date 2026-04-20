<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrian – Klinik Gigi Drg. Noviandri</title>
    <meta name="description" content="Pantau nomor antrian Anda secara real-time di Klinik Gigi Drg. Noviandri.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        primary:   '#a97142',
                        secondary: '#8a5b34',
                        accent:    '#d4a373',
                        light:     '#f3e9d8',
                        dark:      '#4a403d',
                    },
                    keyframes: {
                        'pulse-ring': {
                            '0%, 100%': { transform: 'scale(1)',    opacity: '0.6' },
                            '50%':      { transform: 'scale(1.15)', opacity: '0.2' },
                        },
                        'float': {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%':      { transform: 'translateY(-10px)' },
                        },
                        'count-in': {
                            '0%':   { opacity: '0', transform: 'scale(0.5)' },
                            '70%':  { transform: 'scale(1.1)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        'shimmer': {
                            '0%':   { backgroundPosition: '-200% center' },
                            '100%': { backgroundPosition: '200% center' },
                        },
                    },
                    animation: {
                        'pulse-ring': 'pulse-ring 2.5s ease-in-out infinite',
                        'float':      'float 3s ease-in-out infinite',
                        'count-in':   'count-in 0.6s ease-out forwards',
                        'shimmer':    'shimmer 3s linear infinite',
                    }
                }
            }
        }
    </script>
    <style>
        .glass-card {
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow: 0 8px 40px rgba(169,113,66,0.12);
        }
        .hero-pattern {
            background-color: #f3e9d8;
            background-image: radial-gradient(#d4a373 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .number-display {
            background: linear-gradient(135deg, #a97142, #d4a373, #8a5b34);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .ring-1 { width: 280px; height: 280px; }
        .ring-2 { width: 340px; height: 340px; }
        .ring-3 { width: 400px; height: 400px; }
        .status-badge.dipanggil  { background: linear-gradient(135deg,#a97142,#d4a373); }
        .status-badge.menunggu   { background: linear-gradient(135deg,#64748b,#94a3b8); }
        .status-badge.selesai    { background: linear-gradient(135deg,#16a34a,#4ade80); }
        .table-row:hover { background: rgba(169,113,66,0.05); }
        .gradient-border {
            position: relative;
        }
        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 2px;
            background: linear-gradient(135deg,#a97142,#d4a373,#8a5b34);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }
    </style>
</head>
<body class="bg-light text-dark font-sans antialiased selection:bg-primary selection:text-white">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-card transition-all duration-300 border-b border-primary/10" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-accent rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/30">
                        <i class="fa-solid fa-tooth text-xl"></i>
                    </div>
                    <span class="font-bold text-xl md:text-2xl text-dark tracking-tight">Klinik <span class="text-primary">Drg. Noviandri</span></span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('landing') }}" class="text-dark hover:text-primary font-semibold transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left text-sm"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main -->
    <main class="pt-20 min-h-screen hero-pattern">

        <!-- Header -->
        <div class="py-12 text-center">
            
            <h1 class="text-4xl md:text-5xl font-extrabold text-dark">
                Status <span class="text-primary">Antrian</span> Hari Ini
            </h1>
            <p class="text-dark/60 mt-3 font-medium text-lg">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

            <!-- Current / Being Called -->
            <div class="flex flex-col lg:flex-row gap-8 mb-10">

                <!-- BIG number being called -->
                <div class="flex-1 glass-card rounded-[40px] p-10 flex flex-col items-center justify-center text-center relative overflow-hidden gradient-border">
                    <!-- Decorative glow blobs -->
                    <div class="absolute -top-16 -right-16 w-56 h-56 bg-primary/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-accent/20 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10">
                        <p class="text-dark/50 font-bold uppercase tracking-widest text-sm mb-6">
                            <i class="fa-solid fa-bell-ring mr-1 text-primary"></i>
                            Nomor yang Sedang Dipanggil
                        </p>

                        @if($sedangDipanggil)
                            <!-- Pulse rings -->
                            <div class="relative flex items-center justify-center mb-6">
                                <div class="ring-3 absolute rounded-full border-2 border-primary/15 animate-pulse-ring" style="animation-delay:0.4s"></div>
                                <div class="ring-2 absolute rounded-full border-2 border-primary/25 animate-pulse-ring" style="animation-delay:0.2s"></div>
                                <div class="ring-1 absolute rounded-full border-2 border-primary/40 animate-pulse-ring"></div>
                                <div class="w-56 h-56 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center shadow-2xl shadow-primary/40 animate-float z-10">
                                    <span class="text-white font-black" style="font-size:6rem;line-height:1">
                                        {{ $sedangDipanggil->nomor_antrian }}
                                    </span>
                                </div>
                            </div>
                            @if($sedangDipanggil->pendaftaran)
                                <p class="text-dark/60 font-medium text-base mt-2">
                                    {{ $sedangDipanggil->pendaftaran->layanan->nama_layanan ?? '-' }}
                                </p>
                            @endif
                            <span class="mt-5 inline-flex items-center gap-2 px-5 py-2 rounded-full status-badge dipanggil text-white font-bold text-sm shadow-lg">
                                <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                                Sedang Dilayani
                            </span>
                        @else
                            <div class="relative flex items-center justify-center mb-6">
                                <div class="w-56 h-56 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex flex-col items-center justify-center shadow-inner">
                                    <i class="fa-solid fa-hourglass-half text-5xl text-gray-400 mb-2"></i>
                                </div>
                            </div>
                            <p class="text-2xl font-bold text-dark/50 mt-2">Belum Ada Antrian</p>
                            <p class="text-dark/40 font-medium mt-1">yang sedang dipanggil saat ini</p>
                        @endif
                    </div>
                </div>

                <!-- Summary stats -->
                <div class="flex flex-col gap-6 lg:w-72">
                    <!-- Menunggu -->
                    <div class="glass-card rounded-3xl p-7 flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-yellow-50 flex items-center justify-center text-3xl text-yellow-500 shrink-0 shadow-sm">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-dark/50 font-semibold text-sm uppercase tracking-wider">Menunggu</p>
                            <p class="text-4xl font-black text-dark">{{ $stats['menunggu'] }}</p>
                        </div>
                    </div>
                    <!-- Dipanggil -->
                    <div class="glass-card rounded-3xl p-7 flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-3xl text-primary shrink-0 shadow-sm">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>
                        <div>
                            <p class="text-dark/50 font-semibold text-sm uppercase tracking-wider">Dipanggil</p>
                            <p class="text-4xl font-black text-dark">{{ $stats['dipanggil'] }}</p>
                        </div>
                    </div>
                    <!-- Selesai -->
                    <div class="glass-card rounded-3xl p-7 flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-green-50 flex items-center justify-center text-3xl text-green-500 shrink-0 shadow-sm">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div>
                            <p class="text-dark/50 font-semibold text-sm uppercase tracking-wider">Selesai</p>
                            <p class="text-4xl font-black text-dark">{{ $stats['selesai'] }}</p>
                        </div>
                    </div>
                    <!-- Auto-refresh info -->
                    <div class="glass-card rounded-2xl p-4 flex items-center gap-3 text-sm text-dark/50 font-medium">
                        <i class="fa-solid fa-rotate text-primary text-lg" id="refresh-icon"></i>
                        <span>Halaman diperbarui otomatis setiap <strong class="text-primary">30 detik</strong></span>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-8 border-t-[6px] border-primary">
        <div class="max-w-7xl mx-auto px-4 text-center text-white/50 font-medium text-sm">
            &copy; {{ date('Y') }} Klinik Gigi Drg. Noviandri &mdash; Monitor Antrian Real-time
        </div>
    </footer>

    <script>
        // Auto refresh every 30 seconds
        let countdown = 30;
        const icon = document.getElementById('refresh-icon');

        setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                icon.classList.add('fa-spin');
                setTimeout(() => window.location.reload(), 500);
            }
        }, 1000);

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('shadow-md','backdrop-blur-xl','bg-white/90');
                navbar.classList.remove('glass-card','border-primary/10');
            } else {
                navbar.classList.remove('shadow-md','backdrop-blur-xl','bg-white/90');
                navbar.classList.add('glass-card','border-primary/10');
            }
        });
    </script>
</body>
</html>
