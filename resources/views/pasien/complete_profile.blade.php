<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lengkapi Profil - Klinik Gigi Drg. Noviandri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'], },
                    colors: { primary: '#a97142', secondary: '#8a5b34', accent: '#d4a373', light: '#f3e9d8', dark: '#4a403d', }
                }
            }
        }
    </script>
    <style>
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 10px 40px rgba(169, 113, 66, 0.15);
        }
        .custom-input { transition: all 0.3s ease; }
        .custom-input:focus-within { box-shadow: 0 0 0 4px rgba(169, 113, 66, 0.15); border-color: #a97142; }
    </style>
</head>
<body class="antialiased min-h-screen bg-light text-dark font-sans flex items-center justify-center py-10 px-6 relative overflow-x-hidden">
    
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-primary/20 rounded-full blur-3xl z-0"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-accent/20 rounded-full blur-3xl z-0"></div>

    <div class="w-full max-w-2xl relative z-10 animate-fade-in-up">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-primary to-accent rounded-2xl flex items-center justify-center text-white shadow-xl shadow-primary/30 mb-4">
                <i class="fa-solid fa-tooth text-3xl"></i>
            </div>
            <h1 class="font-extrabold text-3xl tracking-tight text-dark mb-2">Satu Langkah Lagi!</h1>
            <p class="text-dark/70 font-medium">Bantu kami melengkapi data rekam medis Anda sebelum mengambil antrian.</p>
        </div>

        <div class="glass-card rounded-[32px] p-8 sm:p-10 w-full shadow-2xl">
            
            @if ($errors->any())
                <div class="mb-8 p-4 rounded-2xl bg-red-50 border border-red-100 flex items-start">
                    <i class="fa-solid fa-circle-exclamation text-red-500 mt-1 mr-3 shrink-0"></i>
                    <div>
                        <h3 class="text-sm font-bold text-red-800">Ups, mohon periksa kembali!</h3>
                        <ul class="text-sm text-red-700 mt-1 font-medium list-disc ml-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('pasien.store_profile') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="space-y-2 col-span-1 md:col-span-2">
                        <label for="nama_pasien" class="block text-sm font-bold text-dark">Nama Lengkap Sesuai KTP</label>
                        <div class="relative custom-input group rounded-2xl bg-white border border-gray-200">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-user text-gray-400 group-focus-within:text-primary"></i>
                            </div>
                            <input id="nama_pasien" type="text" name="nama_pasien" value="{{ old('nama_pasien', $user->name) }}" required autofocus
                                class="block w-full pl-12 pr-4 py-3.5 bg-transparent border-none focus:ring-0 text-dark font-semibold"
                                placeholder="Nama Lengkap">
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="space-y-2 col-span-1 md:col-span-2">
                        <label class="block text-sm font-bold text-dark mb-3">Jenis Kelamin</label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required class="w-5 h-5 text-primary focus:ring-primary border-gray-300">
                                <span class="text-dark font-semibold group-hover:text-primary transition-colors">Laki-laki</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required class="w-5 h-5 text-primary focus:ring-primary border-gray-300">
                                <span class="text-dark font-semibold group-hover:text-primary transition-colors">Perempuan</span>
                            </label>
                        </div>
                    </div>

                    <!-- NIK -->
                    <div class="space-y-2">
                        <label for="NIK" class="block text-sm font-bold text-dark">Nomor Induk Kependudukan (NIK)</label>
                        <div class="relative custom-input group rounded-2xl bg-white border border-gray-200">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-id-card text-gray-400 group-focus-within:text-primary"></i>
                            </div>
                            <input id="NIK" type="text" name="NIK" value="{{ old('NIK') }}" required maxlength="16"
                                class="block w-full pl-12 pr-4 py-3.5 bg-transparent border-none focus:ring-0 text-dark font-semibold"
                                placeholder="16 Digit NIK KTP">
                        </div>
                    </div>

                    <!-- No HP -->
                    <div class="space-y-2">
                        <label for="no_hp" class="block text-sm font-bold text-dark">Nomor HP / WhatsApp</label>
                        <div class="relative custom-input group rounded-2xl bg-white border border-gray-200">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-phone text-gray-400 group-focus-within:text-primary"></i>
                            </div>
                            <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}" required
                                class="block w-full pl-12 pr-4 py-3.5 bg-transparent border-none focus:ring-0 text-dark font-semibold"
                                placeholder="Cth: 08123456789">
                        </div>
                    </div>



                    <!-- Alamat -->
                    <div class="space-y-2 col-span-1 md:col-span-2">
                        <label for="alamat" class="block text-sm font-bold text-dark">Alamat Lengkap</label>
                        <div class="relative custom-input group rounded-2xl bg-white border border-gray-200 overflow-hidden">
                            <textarea id="alamat" name="alamat" rows="3" required
                                class="block w-full p-4 bg-transparent border-none focus:ring-0 text-dark font-semibold resize-none"
                                placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-4 rounded-xl text-lg font-extrabold text-white bg-gradient-to-r from-primary to-accent hover:from-secondary hover:to-primary shadow-xl shadow-primary/30 transition-all transform hover:-translate-y-1">
                        Simpan & Lanjut ke Antrian <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</body>
</html>
