<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Tiket Antrian - Klinik Gigi Drg. Noviandri</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .ticket-container { box-shadow: none !important; border: 2px dashed #a97142; page-break-inside: avoid; }
        }
        .bg-pattern {
            background-image: radial-gradient(#a97142 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.05;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-6 text-gray-800 relative">
    
    <div class="fixed inset-0 bg-pattern pointer-events-none z-0"></div>

    <!-- Alert / Pesan -->
    @if(session('success'))
        <div class="no-print mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 font-bold flex items-center shadow-lg relative z-10 w-full max-w-sm">
            <i class="fa-solid fa-check-circle mr-3 text-xl"></i> {{ session('success') }}
        </div>
    @endif

    <!-- TIKET ANTRIAN -->
    <div class="ticket-container bg-white w-full max-w-sm rounded-3xl shadow-2xl relative z-10 overflow-hidden transform transition-all hover:-translate-y-1">
        
        <!-- Header Tiket -->
        <div class="bg-[#a97142] text-white text-center py-6 px-6 rounded-t-3xl relative">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-tooth text-2xl"></i>
            </div>
            <h1 class="text-xl font-extrabold tracking-tight">Klinik Gigi Drg. Noviandri</h1>
            <p class="text-sm font-medium opacity-80 mt-1">Solusi Senyum Sempurna Anda</p>
        </div>

        <!-- Badan Tiket -->
        <div class="p-8 text-center bg-gradient-to-b from-white to-orange-50/30">
            <p class="text-gray-500 font-bold text-sm tracking-widest uppercase mb-2">Nomor Antrean Anda</p>
            <h2 class="text-7xl font-extrabold text-[#4a403d] mb-4 tracking-tighter">{{ (int) $antrian->nomor_antrian }}</h2>
            
            <div class="w-16 h-1 bg-[#d4a373] mx-auto rounded-full mb-6 relative"></div>

            <div class="space-y-4 text-left">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-gray-500 font-medium text-sm">Nama Pasien</span>
                    <span class="font-bold text-[#4a403d]">{{ $antrian->pendaftaran->pasien->nama_pasien ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-gray-500 font-medium text-sm">Poli / Layanan</span>
                    <span class="font-bold text-[#4a403d] text-right">{{ $antrian->pendaftaran->layanan->nama_layanan ?? 'Umum' }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <span class="text-gray-500 font-medium text-sm">Tgl. Kunjungan</span>
                    <span class="font-bold text-[#4a403d]">{{ \Carbon\Carbon::parse($antrian->tanggal_antrian)->format('d M Y') }}</span>
                </div>
                <!-- Status intentionally hidden -->
            </div>
        </div>

        <!-- Footer Tiket -->
        <div class="bg-gray-50 text-center py-5 px-6 border-t border-dashed border-gray-300">
            <p class="text-xs text-gray-500 font-medium leading-relaxed">
                Harap datang 15 menit sebelum nomor antrean Anda dipanggil.<br>Terima Kasih.
            </p>
        </div>

    </div>

    <!-- Tombol Aksi (Hanya Tampil di Layar, Sembunyi Saat Print) -->
    <div class="no-print mt-8 flex flex-col sm:flex-row gap-4 relative z-10 w-full max-w-sm">
        <button onclick="window.print()" class="flex-1 bg-[#4a403d] hover:bg-[#a97142] text-white py-4 px-6 rounded-2xl font-bold transition-colors flex justify-center items-center gap-2 shadow-lg hover:shadow-xl">
            <i class="fa-solid fa-print"></i> Cetak Tiket
        </button>
        <a href="{{ route('dashboard.pasien') }}" class="flex-1 bg-white text-[#4a403d] hover:bg-gray-50 py-4 px-6 rounded-2xl font-bold transition-all flex justify-center items-center gap-2 shadow-sm border border-gray-200">
            <i class="fa-solid fa-house"></i> Dashboard
        </a>
    </div>

</body>
</html>

