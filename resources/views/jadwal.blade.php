<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pasien - Klinik Gigi Drg. Noviandri</title>
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
<body class="bg-light text-dark font-sans antialiased">
    <!-- Navbar -->
    <nav class="w-full relative z-50 bg-white shadow-md border-b border-primary/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('landing') }}" class="flex-shrink-0 flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-accent rounded-xl flex items-center justify-center text-white shadow-lg group-hover:shadow-primary/50 transition-all">
                        <i class="fa-solid fa-arrow-left text-xl"></i>
                    </div>
                    <span class="font-bold text-xl text-dark tracking-tight group-hover:text-primary transition-colors">Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-primary font-bold tracking-widest uppercase mb-2">Jadwal Pasien</h2>
                <h3 class="text-4xl md:text-5xl font-extrabold text-dark">Daftar Antrian Hari Ini</h3>
            </div>
            
            <div class="bg-white rounded-[32px] shadow-xl border border-gray-100 overflow-hidden mb-8 glass-card">
                <!-- Filter Form -->
                <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                    <form action="{{ route('jadwal') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end justify-center">
                        <div class="w-full sm:w-auto">
                            <label for="tanggal" class="block text-sm font-semibold text-dark mb-2">Pilih Tanggal:</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}" class="w-full sm:w-64 px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-primary hover:bg-secondary text-white px-8 py-3 rounded-xl font-bold transition-colors shadow-lg shadow-primary/30 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-filter"></i> Filter
                        </button>
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-primary/5 text-dark">
                                <th class="px-6 py-5 font-bold border-b border-gray-100 text-center w-20">No</th>
                                <th class="px-6 py-5 font-bold border-b border-gray-100">Nama Pasien</th>
                                <th class="px-6 py-5 font-bold border-b border-gray-100">Layanan</th>
                                <th class="px-6 py-5 font-bold border-b border-gray-100 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendaftarans as $index => $antrian)
                            <tr class="hover:bg-primary/5 transition-colors group">
                                <td class="px-6 py-4 border-b border-gray-50 text-center font-semibold text-dark/70 group-hover:text-primary">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 border-b border-gray-50">
                                    <div class="font-bold text-dark">{{ $antrian->pasien?->nama_pasien ?? '-' }}</div>
                                    <div class="text-sm text-dark/50">NIK {{ $antrian->pasien?->NIK ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-50">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-light text-primary text-sm font-semibold">
                                        <i class="fa-solid fa-stethoscope text-xs"></i> {{ $antrian->layanan->nama_layanan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-50 text-center">
                                    @if($antrian->status == 'Menunggu')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold uppercase tracking-wide">
                                            <i class="fa-solid fa-clock"></i> {{ $antrian->status }}
                                        </span>
                                    @elseif($antrian->status == 'Diproses' || $antrian->status == 'Diperiksa')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wide">
                                            <i class="fa-solid fa-spinner animate-spin"></i> {{ $antrian->status }}
                                        </span>
                                    @elseif($antrian->status == 'Selesai')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase tracking-wide">
                                            <i class="fa-solid fa-check"></i> {{ $antrian->status }}
                                        </span>
                                    @elseif($antrian->status == 'Batal')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold uppercase tracking-wide">
                                            <i class="fa-solid fa-xmark"></i> {{ $antrian->status }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-bold uppercase tracking-wide">
                                            {{ $antrian->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 text-primary mb-4">
                                        <i class="fa-solid fa-calendar-xmark text-2xl"></i>
                                    </div>
                                    <h4 class="text-xl font-bold text-dark mb-2">Tidak Ada Antrian</h4>
                                    <p class="text-dark/60 font-medium">Belum ada pasien yang terdaftar pada tanggal ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if(count($pendaftarans) > 0)
            <div class="text-center">
                <p class="text-dark/70 font-medium"><i class="fa-solid fa-circle-info text-primary mr-2"></i> Total <span class="font-bold text-dark">{{ count($pendaftarans) }}</span> pasien terdaftar pada <span class="font-bold text-dark">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span></p>
            </div>
            @endif
        </div>
    </div>
</body>
</html>
