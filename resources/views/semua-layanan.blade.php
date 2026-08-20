<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Layanan - Klinik Gigi Drg. Noviandri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px rgba(169, 113, 66, 0.1);
        }
    </style>
</head>
<body class="bg-light text-dark font-sans antialiased selection:bg-primary selection:text-white">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-card transition-all duration-300 border-b border-primary/10 shadow-md backdrop-blur-xl bg-white/90">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-accent rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/30">
                        <i class="fa-solid fa-tooth text-xl"></i>
                    </div>
                    <span class="font-bold text-xl md:text-2xl text-dark tracking-tight">Klinik <span class="text-primary">Drg. Noviandri</span></span>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('landing') }}" class="text-dark hover:text-primary font-semibold transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Services Section -->
    <section class="py-32 relative bg-[color-mix(in_srgb,#f3e9d8_30%,white)] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-primary font-bold tracking-widest uppercase mb-2">Lengkap & Terpercaya</h2>
                <h3 class="text-4xl md:text-5xl font-extrabold text-dark leading-tight">Semua Layanan Kami</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $serviceIcons = ['fa-tooth', 'fa-wand-magic-sparkles', 'fa-syringe', 'fa-stethoscope', 'fa-kit-medical'];
                @endphp
                @forelse($layanans as $index => $layanan)
                    <div class="bg-white border hover:border-transparent border-gray-100 p-8 rounded-[32px] hover:shadow-2xl hover:shadow-primary/20 transition-all duration-300 group transform hover:-translate-y-1">
                        <div class="w-20 h-20 bg-light rounded-3xl flex items-center justify-center text-primary text-4xl mb-8 group-hover:bg-gradient-to-br group-hover:from-primary group-hover:to-accent group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="fa-solid {{ $serviceIcons[$index % count($serviceIcons)] }}"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-dark mb-4">{{ $layanan->nama_layanan }}</h4>
                        <p class="text-dark/70 mb-8 leading-relaxed font-medium">
                            @if($layanan->parent_id)
                                Tindakan untuk {{ strtolower($layanan->nama_layanan) }} (Bagian dari {{ $layanan->parent->nama_layanan ?? 'perawatan utama' }}) dengan biaya Rp {{ number_format($layanan->harga, 0, ',', '.') }}.
                            @else
                                Penanganan untuk {{ strtolower($layanan->nama_layanan) }} dengan biaya mulai dari Rp {{ number_format($layanan->harga, 0, ',', '.') }}.
                            @endif
                        </p>
                    </div>
                @empty
                    <div class="col-span-full text-center text-dark/70 font-medium py-10">Belum ada layanan yang tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-10 border-t-[8px] border-primary text-center">
        <p class="text-white/70 font-medium">&copy; {{ date('Y') }} Klinik Gigi Drg. Noviandri. All rights reserved.</p>
    </footer>

</body>
</html>
