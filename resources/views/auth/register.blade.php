<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi - Klinik Gigi Drg. Noviandri</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: '#a97142',
                        secondary: '#8a5b34',
                        accent: '#d4a373',
                        light: '#f3e9d8',
                        dark: '#4a403d',
                    }
                }
            }
        }
    </script>
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .custom-input:focus-within {
            box-shadow: 0 0 0 4px rgba(169, 113, 66, 0.15);
        }
        .hero-bg {
            background-image: url('https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="antialiased font-sans bg-gray-50 text-dark selection:bg-primary selection:text-white">

    <div class="flex min-h-screen">
        
        <!-- Left Create Account Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8 relative overflow-y-auto">
            
            <!-- Mobile Decorative Blob -->
            <div class="lg:hidden absolute top-0 left-0 -ml-20 -mt-20 w-64 h-64 bg-accent/10 rounded-full blur-3xl z-0"></div>
            
            <div class="w-full max-w-md relative z-10 animate-fade-in-up py-10" style="animation-delay: 0.1s;">
                
                <a href="{{ route('landing') ?? '/' }}" class="inline-flex items-center text-primary hover:text-secondary font-bold mb-8 transition-colors group">
                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center mr-3 group-hover:bg-primary group-hover:text-white transition-all">
                        <i class="fa-solid fa-arrow-left"></i>
                    </div> 
                    Kembali ke Beranda
                </a>

                <div class="mb-8">
                    <h2 class="text-4xl font-extrabold text-dark tracking-tight mb-2">Buat Akun Anda</h2>
                    <p class="text-dark/60 font-medium text-lg">Daftar sekarang untuk segera membuat reservasi.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-8 p-4 rounded-2xl bg-red-50 border border-red-100 flex items-start animate-fade-in-up">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0 mr-3 text-red-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-red-800">Ups, terjadi kesalahan!</h3>
                            <ul class="text-sm text-red-700 mt-1 font-medium list-disc ml-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div class="space-y-1.5">
                        <label for="name" class="block text-sm font-bold text-dark">Nama Lengkap</label>
                        <div class="relative custom-input group rounded-2xl border border-gray-200 bg-gray-50 hover:bg-white transition-all">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-user text-gray-400 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="block w-full pl-12 pr-4 py-3.5 bg-transparent border-none focus:ring-0 text-dark placeholder-gray-400 font-bold"
                                placeholder="Cth: Budi Santoso">
                        </div>
                    </div>

                    <!-- Username (NIK) -->
                    <div class="space-y-1.5">
                        <label for="username" class="block text-sm font-bold text-dark">Username (NIK)</label>
                        <div class="relative custom-input group rounded-2xl border border-gray-200 bg-gray-50 hover:bg-white transition-all">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-id-card text-gray-400 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input id="username" type="text" name="username" value="{{ old('username') }}" required maxlength="16"
                                class="block w-full pl-12 pr-4 py-3 bg-transparent border-none focus:ring-0 text-dark placeholder-gray-400 font-bold"
                                placeholder="16 Digit NIK KTP">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-sm font-bold text-dark">Password</label>
                        <div class="relative custom-input group rounded-2xl border border-gray-200 bg-gray-50 hover:bg-white transition-all">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input id="password" type="password" name="password" required
                                class="block w-full pl-12 pr-4 py-3.5 bg-transparent border-none focus:ring-0 text-dark placeholder-gray-400 font-bold"
                                placeholder="Minimal 8 karakter">
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-sm font-bold text-dark">Konfirmasi Password</label>
                        <div class="relative custom-input group rounded-2xl border border-gray-200 bg-gray-50 hover:bg-white transition-all">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="block w-full pl-12 pr-4 py-3.5 bg-transparent border-none focus:ring-0 text-dark placeholder-gray-400 font-bold"
                                placeholder="Ketik ulang password">
                        </div>
                    </div>

                    <button type="submit" class="w-full flex justify-center items-center py-4 px-4 mt-6 rounded-2xl text-lg font-extrabold text-white bg-dark hover:bg-primary shadow-xl shadow-dark/20 focus:outline-none focus:ring-4 focus:ring-primary/30 transition-all duration-300 transform hover:-translate-y-1">
                        Daftar Akun <i class="fa-solid fa-user-plus ml-3"></i>
                    </button>
                    
                </form>
                
                <p class="mt-8 text-center text-dark/70 font-medium">
                    Sudah memiliki akun? <a href="{{ route('login') }}" class="font-extrabold text-primary hover:text-secondary hover:underline transition-all">Masuk disini</a>
                </p>
                
            </div>
            
        </div>

        <!-- Right Banner - Hidden on Mobile -->
        <div class="hidden lg:flex lg:w-1/2 hero-bg relative items-center justify-center p-12 overflow-hidden rounded-l-[50px] shadow-2xl z-10">
            <!-- Dark Overlay -->
            <div class="absolute inset-0 bg-dark/85 backdrop-blur-sm z-0"></div>
            <!-- Decorative Blobs -->
            <div class="absolute top-0 right-0 -mr-32 -mt-32 w-96 h-96 bg-primary/40 rounded-full blur-3xl z-0"></div>
            <div class="absolute bottom-0 left-0 -ml-32 -mb-32 w-96 h-96 bg-accent/40 rounded-full blur-3xl z-0"></div>

            <div class="relative z-10 w-full max-w-lg text-white animate-fade-in-up">
                <h1 class="text-5xl font-extrabold mb-6 leading-tight">Mulai Perjalanan Senyum Sempurna Anda.</h1>
                <p class="text-xl text-white/80 font-medium leading-relaxed mb-8">
                    Hanya butuh waktu kurang dari 1 menit untuk mendaftar dan mulai mengatur jadwal konsultasi dengan dokter spesialis kami.
                </p>
                
                <div class="space-y-5 text-white/90 font-bold text-lg">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center border border-white/20"><i class="fa-solid fa-check text-primary"></i></div>
                        Prioritas antrian jelas
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center border border-white/20"><i class="fa-solid fa-check text-primary"></i></div>
                        Sistem transparan dan informatif
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
