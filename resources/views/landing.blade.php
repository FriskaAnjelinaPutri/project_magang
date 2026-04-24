<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Gigi Drg. Noviandri</title>
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
        .gradient-text {
            background: linear-gradient(135deg, #4a403d, #a97142);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-pattern {
            background-color: #f3e9d8;
            background-image: radial-gradient(#d4a373 1px, transparent 1px);
            background-size: 20px 20px;
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
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#beranda" class="text-dark hover:text-primary font-semibold transition-colors">Beranda</a>
                    <a href="#layanan" class="text-dark hover:text-primary font-semibold transition-colors">Layanan</a>
                    <a href="{{ route('antrian.monitor') }}" class="relative text-dark hover:text-primary font-semibold transition-colors flex items-center gap-1.5 group">
                        <span class="w-2 h-2 rounded-full bg-primary animate-ping absolute -top-1 -right-1"></span>
                        <i class="fa-solid fa-display text-sm"></i> Cek Antrian
                    </a>
                    <a href="#lokasi" class="text-dark hover:text-primary font-semibold transition-colors">Lokasi</a>

                    @auth
                        @php
                            $dashboardRoute = '/';
                            if(auth()->user()->role === 'admin') $dashboardRoute = '/dashboard/admin';
                            elseif(auth()->user()->role === 'kasir') $dashboardRoute = '/dashboard/kasir';
                            elseif(auth()->user()->role === 'pasien') $dashboardRoute = '/dashboard/pasien';
                        @endphp
                        <a href="{{ $dashboardRoute }}" class="bg-gradient-to-r from-primary to-accent hover:from-secondary hover:to-primary text-white px-7 py-2.5 rounded-full font-bold transition-all shadow-lg hover:shadow-primary/50 flex items-center gap-2 transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-house-chimney-medical"></i> Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="text-dark hover:text-red-500 font-bold transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-gradient-to-r from-primary to-accent hover:from-secondary hover:to-primary text-white px-7 py-2.5 rounded-full font-bold transition-all shadow-lg hover:shadow-primary/50 flex items-center gap-2 transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-right-to-bracket"></i> Login
                        </a>
                    @endauth
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button class="text-dark hover:text-primary focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden hero-pattern">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-primary/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-accent/20 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center px-4 py-2 rounded-full glass-card text-primary font-bold text-sm mb-8 border border-primary/20 shadow-sm animate-fade-in-up">
                    <i class="fa-solid fa-award text-accent mr-2 text-lg"></i> Pelayanan Gigi Terbaik di Kota Anda
                </div>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight tracking-tight text-dark">
                    Senyum Sehat Anda Adalah <br class="hidden md:block"/> <span class="gradient-text">Prioritas Kami</span>
                </h1>
                <p class="text-lg md:text-xl text-dark/80 mb-10 leading-relaxed max-w-2xl mx-auto font-medium">
                    Perawatan gigi profesional dengan teknologi modern dan dokter berpengalaman. Dapatkan gigi sehat, bersih, dan senyum menawan yang Anda impikan.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('reservasi.create') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-primary to-accent hover:from-secondary hover:to-primary text-white rounded-full font-bold text-lg transition-all shadow-xl hover:shadow-primary/40 transform hover:-translate-y-1 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-ticket text-xl"></i> Ambil Antrian Sekarang
                    </a>
                    <a href="#layanan" class="w-full sm:w-auto px-8 py-4 glass-card text-dark hover:text-primary rounded-full font-bold text-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-3 border border-white">
                        Pelajari Layanan
                    </a>
                </div>
            </div>

            <!-- Floating Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-20 relative z-20">
                <div class="bg-white/80 backdrop-blur-lg p-6 rounded-3xl text-center transform transition duration-500 hover:-translate-y-2 border border-white shadow-xl shadow-primary/5">
                    <div class="w-16 h-16 mx-auto bg-primary/10 rounded-2xl flex items-center justify-center text-3xl text-primary mb-4">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="text-3xl font-extrabold text-dark mb-1">{{ $stats['pasien'] }}</div>
                    <div class="text-sm font-semibold text-primary">Total Pasien</div>
                </div>
                <div class="bg-white/80 backdrop-blur-lg p-6 rounded-3xl text-center transform transition duration-500 hover:-translate-y-2 border border-white shadow-xl shadow-primary/5">
                    <div class="w-16 h-16 mx-auto bg-primary/10 rounded-2xl flex items-center justify-center text-3xl text-primary mb-4">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div class="text-3xl font-extrabold text-dark mb-1">{{ $stats['layanan'] }}</div>
                    <div class="text-sm font-semibold text-primary">Total Layanan</div>
                </div>
                <div class="bg-white/80 backdrop-blur-lg p-6 rounded-3xl text-center transform transition duration-500 hover:-translate-y-2 border border-white shadow-xl shadow-primary/5">
                    <div class="w-16 h-16 mx-auto bg-primary/10 rounded-2xl flex items-center justify-center text-3xl text-primary mb-4">
                        <i class="fa-solid fa-notes-medical"></i>
                    </div>
                    <div class="text-3xl font-extrabold text-dark mb-1">{{ $stats['pendaftaran'] }}</div>
                    <div class="text-sm font-semibold text-primary">Total Pendaftaran</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-24 relative bg-[color-mix(in_srgb,#f3e9d8_30%,white)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16">
                <div class="max-w-2xl">
                    <h2 class="text-primary font-bold tracking-widest uppercase mb-2">Layanan Kami</h2>
                    <h3 class="text-4xl md:text-5xl font-extrabold text-dark leading-tight">Perawatan Komprehensif Untuk Anda</h3>
                </div>
                <div class="mt-6 md:mt-0">
                    <a href="#" class="inline-flex items-center font-bold text-primary hover:text-secondary transition-colors text-lg">
                        Lihat Semua Layanan <i class="fa-solid fa-arrow-right-long ml-2"></i>
                    </a>
                </div>
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
                        <p class="text-dark/70 mb-8 leading-relaxed font-medium">Layanan profesional {{ $layanan->nama_layanan }} dengan perawatan optimal dan harga terjangkau (Rp {{ number_format($layanan->harga, 0, ',', '.') }}).</p>
                    </div>
                @empty
                    <div class="col-span-full text-center text-dark/70 font-medium py-10">Belum ada layanan yang tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>


    <!-- CTA Section -->
    <section class="py-24 relative overflow-hidden bg-dark">
        <!-- Abstract Shapes -->
        <div class="absolute inset-0 opacity-10">
            <svg class="absolute w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="a" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M0 40h40V0H0z" fill="none"/><path d="M20 0l20 20-20 20L0 20z" fill="#f3e9d8"/></pattern></defs><rect width="100%" height="100%" fill="url(#a)"/>
            </svg>
        </div>
        <div class="absolute -right-32 -top-32 w-96 h-96 bg-primary/40 rounded-full blur-3xl"></div>
        <div class="absolute -left-32 -bottom-32 w-96 h-96 bg-accent/30 rounded-full blur-3xl"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-gradient-to-br from-primary to-secondary rounded-[40px] p-10 md:p-16 shadow-2xl text-center border border-white/10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-2xl"></div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">Waktunya Membuat Senyum Anda Bersinar</h2>
                <p class="text-white/90 text-xl mb-10 max-w-2xl mx-auto font-medium">Bebaskan diri dari rasa sakit gigi. Ambil langkah pertama menuju kesehatan mulut yang sempurna hari ini.</p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ route('reservasi.create') }}" class="px-10 py-5 bg-white text-primary hover:bg-light rounded-full font-extrabold text-xl transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1 flex items-center gap-3 w-full sm:w-auto justify-center">
                        <i class="fa-solid fa-calendar-check text-2xl"></i> Reservasi Jadwal
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section id="lokasi" class="py-24 bg-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-primary font-bold tracking-widest uppercase mb-2">Temukan Kami</h2>
                <h3 class="text-4xl md:text-5xl font-extrabold text-dark">Kunjungi Klinik Kami</h3>
            </div>

            <div class="bg-white rounded-[40px] shadow-xl overflow-hidden flex flex-col lg:flex-row border border-gray-100">
                <div class="w-full lg:w-5/12 p-10 lg:p-14 flex flex-col justify-center">
                    <h4 class="text-3xl font-extrabold text-dark mb-8">Informasi Kontak</h4>

                    <div class="space-y-8">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary text-2xl shrink-0">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <h5 class="text-xl font-bold text-dark mb-1">Alamat</h5>
                                <p class="text-dark/70 font-medium text-lg">Padang Panjang Tim.<br>Kota Padang Panjang, Sumbar</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary text-2xl shrink-0">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <h5 class="text-xl font-bold text-dark mb-1">Kontak Pendaftaran</h5>
                                <p class="text-dark/70 font-medium text-lg">08126794403</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary text-2xl shrink-0">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div>
                                <h5 class="text-xl font-bold text-dark mb-1">Jam Operasional</h5>
                                <p class="text-dark/70 font-medium text-lg">Senin - Sabtu: 13:00 - 18:00</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-7/12 min-h-[400px] lg:min-h-full relative overflow-hidden p-2 bg-light">
                    <iframe
                        src="https://maps.google.com/maps?q=-0.4654805,100.4045178&t=&z=17&ie=UTF8&iwloc=&output=embed"
                        class="w-full h-full min-h-[400px] rounded-[32px] shadow-sm"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-20 pb-10 border-t-[8px] border-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white">
                            <i class="fa-solid fa-tooth text-xl"></i>
                        </div>
                        <span class="font-bold text-3xl">Klinik <span class="text-primary">Drg. Noviandri</span></span>
                    </div>
                    <p class="text-white/70 text-lg mb-8 max-w-md leading-relaxed">
                        Kami berkomitmen untuk memberikan layanan perawatan gigi yang aman, nyaman, dan berkualitas dengan teknologi terkini.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-12 h-12 rounded-full bg-white/5 hover:bg-primary flex items-center justify-center transition-all text-xl hover:-translate-y-1">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full bg-white/5 hover:bg-primary flex items-center justify-center transition-all text-xl hover:-translate-y-1">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full bg-white/5 hover:bg-primary flex items-center justify-center transition-all text-xl hover:-translate-y-1">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h5 class="text-xl font-bold mb-6 text-white border-b-2 border-primary inline-block pb-2">Tautan Cepat</h5>
                    <ul class="space-y-4 text-white/70 font-medium">
                        <li><a href="#beranda" class="hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Beranda</a></li>
                        <li><a href="#layanan" class="hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Layanan</a></li>
                        <li><a href="{{ route('antrian.monitor') }}" class="hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Cek Antrian</a></li>
                        <li><a href="#lokasi" class="hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Lokasi</a></li>
                        <li><a href="{{ route('reservasi.create') }}" class="hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Reservasi</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-xl font-bold mb-6 text-white border-b-2 border-primary inline-block pb-2">Jam Buka</h5>
                    <ul class="space-y-4 text-white/70 font-medium">
                        <li class="flex justify-between border-b border-white/10 pb-2"><span>Senin - Sabtu</span> <span>13:00 - 18:00</span></li>
                        <li class="flex justify-between text-primary font-bold"><span>Minggu / Libur</span> <span>Tutup</span></li>
                    </ul>
                </div>
            </div>

        </div>
    </footer>

    <script>
        // Navbar Scrolled State
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('shadow-md', 'backdrop-blur-xl', 'bg-white/90');
                navbar.classList.remove('glass-card', 'border-primary/10');
            } else {
                navbar.classList.remove('shadow-md', 'backdrop-blur-xl', 'bg-white/90');
                navbar.classList.add('glass-card', 'border-primary/10');
            }
        });
    </script>
</body>
</html>
