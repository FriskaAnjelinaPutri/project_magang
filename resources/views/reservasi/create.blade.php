@extends(Auth::user()->role === 'pasien' ? 'layouts.app' : 'layouts.admin')

@section('content')

@if(isset($user) && $user->role === 'pasien')

<!-- ============================ -->
<!-- TAMPILAN KHUSUS PASIEN -->
<!-- ============================ -->

<div class="max-w-xl mx-auto">

    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-primary to-accent rounded-2xl flex items-center justify-center text-white shadow-xl shadow-primary/30 mb-4">
            <i class="fa-solid fa-calendar-check text-3xl"></i>
        </div>
        <h2 class="font-extrabold text-3xl tracking-tight text-dark mb-2">Ambil Antrian</h2>
        <p class="text-dark/70 font-medium">Pilih layanan dan jadwalkan tanggal kunjungan Anda.</p>
    </div>

    <div class="glass-card rounded-[32px] p-8 sm:p-10 w-full shadow-2xl">

        @if (session('success'))
            <div class="mb-8 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 font-bold flex items-center shadow-sm">
                <i class="fa-solid fa-check-circle mr-3 text-xl"></i> {{ session('success') }}
            </div>
        @endif
        
        <form action="{{ route('reservasi.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Layanan Dipilih -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-dark">Pilih Pelayanan</label>
                <div class="relative group rounded-2xl bg-white border border-gray-200 focus-within:ring-4 focus-within:ring-primary/20 focus-within:border-primary transition-all">
                    <select name="id_layanan" class="block w-full px-4 py-3.5 bg-transparent border-none focus:ring-0 text-dark font-semibold outline-none appearance-none" required>
                        <option value="" disabled selected>-- Daftar Layanan Tersedia --</option>
                        @foreach($layanan as $ly)
                            <option value="{{ $ly->id_layanan }}">{{ $ly->nama_layanan }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary">
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <!-- Tanggal Kunjungan -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-dark">Tanggal Kunjungan</label>
                <div class="relative group rounded-2xl bg-white border border-gray-200 focus-within:ring-4 focus-within:ring-primary/20 focus-within:border-primary transition-all">
                    <input type="date" name="tanggal_kunjungan" class="block w-full px-4 py-3.5 bg-transparent border-none focus:ring-0 text-dark font-semibold outline-none" required min="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-4 rounded-xl text-lg font-extrabold text-white bg-gradient-to-r from-primary to-accent hover:from-secondary hover:to-primary shadow-xl shadow-primary/30 transition-all transform hover:-translate-y-1">
                    Reservasi Antrian Sekarang <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 flex flex-col sm:flex-row justify-center items-center gap-3">
        <a href="{{ route('dashboard.pasien') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-gray-200 text-dark font-bold hover:border-primary hover:text-primary transition-colors shadow-sm">
            <i class="fa-solid fa-clock-rotate-left"></i> Dashboard Riwayat
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-gray-200 text-dark/70 hover:text-red-500 hover:border-red-200 font-bold transition-colors shadow-sm" title="Keluar dari akun Anda">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </div>
    
</div>

@else

<!-- ============================ -->
<!-- TAMPILAN ADMIN/KASIR -->
<!-- ============================ -->

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 px-2 mt-4 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Form Reservasi</h1>
        <p class="text-sm text-gray-500 mt-1">Tambahkan reservasi pasien secara manual.</p>
    </div>
    <a href="{{ route('reservasi.index') }}" class="text-gray-500 hover:text-gray-700 font-medium transition-colors text-sm flex items-center gap-2">
        ← Kembali ke Tabel Reservasi
    </a>
</div>

<div class="px-2">
    <div class="glass-panel rounded-3xl p-6 sm:p-8 bg-white shadow-xl max-w-2xl">
        <form action="{{ route('reservasi.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Pasien</label>
                <select name="id_pasien" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-gray-800 font-medium" required>
                    <option value="" disabled selected>-- Pilih Pasien --</option>
                    @foreach($pasienList as $ps)
                        <option value="{{ $ps->id_pasien }}">{{ $ps->nama_pasien }} (NIK: {{ $ps->NIK }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Layanan</label>
                <select name="id_layanan" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-gray-800 font-medium" required>
                    <option value="" disabled selected>-- Pilih Layanan --</option>
                    @foreach($layanan as $ly)
                        <option value="{{ $ly->id_layanan }}">{{ $ly->nama_layanan }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Reservasi</label>
                <input type="date" name="tanggal_kunjungan" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all text-gray-800 font-medium" required min="{{ date('Y-m-d') }}">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full px-6 py-4 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-bold transition-all shadow-md">
                    Simpan Reservasi
                </button>
            </div>
        </form>
    </div>
</div>

@endif

@endsection

