@extends('layouts.admin')

@section('content')
<!-- Header Area -->
<div class="flex justify-between items-center mb-8 px-2 mt-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Overview</h1>
        <p class="text-sm text-gray-500 mt-1">Sistem manajemen layanan pasien Klinik Sehat.</p>
    </div>
</div>

<!-- Cards Area -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 px-2">
    <!-- Card 1: Pasien -->
    <div class="glass-card rounded-2xl p-5 flex items-center space-x-4">
        <div class="p-4 bg-orange-50/80 rounded-2xl text-orange-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Pasien</p>
            <h3 class="text-3xl font-black text-gray-800">{{ $stats['pasien'] }}</h3>
        </div>
    </div>

    <!-- Card 2: Pendaftaran -->
    <div class="glass-card rounded-2xl p-5 flex items-center space-x-4">
        <div class="p-4 bg-orange-50/80 rounded-2xl text-orange-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Pendaftaran</p>
            <h3 class="text-3xl font-black text-gray-800">{{ $stats['pendaftaran'] }}</h3>
        </div>
    </div>

    <!-- Card 3: Layanan -->
    <div class="glass-card rounded-2xl p-5 flex items-center space-x-4">
        <div class="p-4 bg-purple-50/80 rounded-2xl text-purple-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Layanan</p>
            <h3 class="text-3xl font-black text-gray-800">{{ $stats['layanan'] }}</h3>
        </div>
    </div>

    <!-- Card 4: Pembayaran -->
    <div class="glass-card rounded-2xl p-5 flex items-center space-x-4">
        <div class="p-4 bg-green-50/80 rounded-2xl text-green-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Pembayaran</p>
            <h3 class="text-3xl font-black text-gray-800">{{ $stats['pembayaran'] }}</h3>
        </div>
    </div>
</div>

<!-- Quick Actions & Charts Area -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-10 px-2 flex-grow">
    <!-- Chart Column (Span 2) -->
    <div class="xl:col-span-2 glass-panel rounded-3xl p-6 md:p-8 flex flex-col shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Grafik Pendaftaran Harian</h2>
                <p class="text-sm text-gray-500 mt-1">Grafik reservasi pasien 7 hari terakhir.</p>
            </div>
            {{-- <div class="flex items-center gap-2 text-sm text-orange-500 font-bold bg-orange-50 px-3 py-1 rounded-full">
                <i class="fa-solid fa-arrow-trend-up"></i> Live
            </div> --}}
        </div>
        <div class="relative w-full flex-1 min-h-[250px] lg:min-h-[350px]">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <!-- Quick Actions Column (Span 1) -->
    <div class="flex flex-col gap-6">
        <div class="glass-panel rounded-3xl p-6 md:p-8 shadow-sm h-full">
            <h2 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-2">Aksi Cermat</h2>
            <div class="grid grid-cols-2 gap-4">

                <a href="{{ route('reservasi.index') }}" class="flex flex-col items-center justify-center p-5 rounded-2xl bg-orange-50/70 hover:bg-orange-100 border border-orange-100/50 text-orange-600 transition-all hover:shadow-md transform hover:-translate-y-1">
                    <svg class="w-8 h-8 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span class="text-sm font-bold text-center">Kelola<br>Antrian</span>
                </a>

                <a href="{{ route('pasien.index') }}" class="flex flex-col items-center justify-center p-5 rounded-2xl bg-blue-50/70 hover:bg-blue-100 border border-blue-100/50 text-blue-600 transition-all hover:shadow-md transform hover:-translate-y-1">
                    <svg class="w-8 h-8 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span class="text-sm font-bold text-center">Data<br>Pasien</span>
                </a>

                <a href="{{ route('layanan.index') }}" class="flex flex-col items-center justify-center p-5 rounded-2xl bg-purple-50/70 hover:bg-purple-100 border border-purple-100/50 text-purple-600 transition-all hover:shadow-md transform hover:-translate-y-1">
                    <svg class="w-8 h-8 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414V13z"></path></svg>
                    <span class="text-sm font-bold text-center">Daftar<br>Layanan</span>
                </a>

                <a href="{{ route('pembayaran.index') }}" class="flex flex-col items-center justify-center p-5 rounded-2xl bg-green-50/70 hover:bg-green-100 border border-green-100/50 text-green-600 transition-all hover:shadow-md transform hover:-translate-y-1">
                    <svg class="w-8 h-8 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="text-sm font-bold text-center">Daftar<br>Pembayaran</span>
                </a>

            </div>
        </div>
    </div>
</div>

    <!-- Manajemen Antrian Hari Ini -->
    <div class="px-2 mb-10">
        <div class="glass-panel rounded-3xl p-8">

            {{-- HEADER --}}
            <div class="mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fa-solid fa-bell-concierge text-orange-500 mr-2"></i>
                        Manajemen Antrian Hari Ini
                    </h2>

                    {{-- <p class="text-sm text-gray-500 mt-1">
                        Panggil, lewati, atau selesaikan antrian pasien.
                    </p> --}}
                </div>

                {{-- STATUS BADGE --}}
                <div class="flex flex-wrap items-center gap-3 mt-4">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                        <i class="fa-solid fa-user-clock mr-1.5"></i>
                        Belum Datang: {{ $antrian_stats['belum_datang'] ?? 0 }}
                    </span>

                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                        <i class="fa-solid fa-clock mr-1.5"></i>
                        Menunggu: {{ $antrian_stats['menunggu'] ?? 0 }}
                    </span>

                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                        <i class="fa-solid fa-microphone mr-1.5"></i>
                        Dipanggil: {{ $antrian_stats['dipanggil'] ?? 0 }}
                    </span>

                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                        <i class="fa-solid fa-forward-step mr-1.5"></i>
                        Dilewati: {{ $antrian_stats['dilewati'] ?? 0 }}
                    </span>

                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                        <i class="fa-solid fa-check mr-1.5"></i>
                        Selesai: {{ $antrian_stats['selesai'] ?? 0 }}
                    </span>
                </div>
            </div>

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
            <div class="mb-5 p-4 bg-green-50/80 border border-green-200 text-green-700 rounded-2xl flex items-center shadow-sm">
                <i class="fa-solid fa-circle-check text-xl mr-3"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            @endif

            {{-- ALERT ERROR --}}
            @if (session('error'))
            <div class="mb-5 p-4 bg-red-50/80 border border-red-200 text-red-700 rounded-2xl flex items-center shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
            @endif

            {{-- TABLE --}}
            <div class="overflow-x-auto rounded-2xl border border-gray-200/70 bg-white/70">
                <table class="w-full text-left border-separate border-spacing-0">

                    {{-- TABLE HEADER --}}
                    <thead>
                        <tr class="bg-gray-50/90">
                            <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center border-b border-gray-200/80">
                                No. Antrian
                            </th>

                            <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">
                                Pasien
                            </th>

                            <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">
                                Layanan
                            </th>

                            <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center border-b border-gray-200/80">
                                Status
                            </th>

                            <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center border-b border-gray-200/80">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    {{-- TABLE BODY --}}
                    <tbody>
                        @forelse ($antrian_hari_ini ?? [] as $row)

                            @php
                                $statusAntrian = strtolower(trim((string) $row->status));
                            @endphp

                            <tr class="odd:bg-white even:bg-gray-50/50 hover:bg-orange-50/40 transition-colors {{ $statusAntrian === 'dipanggil' ? 'ring-2 ring-blue-300 ring-inset bg-blue-50/30' : '' }}">

                                {{-- NOMOR ANTRIAN --}}
                                <td class="py-4 px-4 text-2xl font-black text-orange-500 text-center border-b border-gray-100/80">
                                    {{ (int) $row->nomor_antrian }}
                                </td>

                                {{-- PASIEN --}}
                                <td class="py-4 px-4 border-b border-gray-100/80">
                                    <div class="text-sm text-gray-900 font-bold">
                                        {{ $row->pendaftaran->pasien->nama_pasien ?? 'Unknown' }}
                                    </div>
                                </td>

                                {{-- LAYANAN --}}
                                <td class="py-4 px-4 border-b border-gray-100/80">
                                    <div class="text-sm text-gray-600 font-medium">
                                        {{ optional($row->pendaftaran)->layanans ? $row->pendaftaran->layanans->pluck('nama_layanan')->implode(', ') : '-' }}
                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td class="py-4 px-4 text-center whitespace-nowrap border-b border-gray-100/80">

                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold capitalize

                                        {{ $statusAntrian === 'belum_datang' ? 'bg-red-100 text-red-800 border border-red-200' : '' }}

                                        {{ $statusAntrian === 'menunggu' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}

                                        {{ $statusAntrian === 'dipanggil' ? 'bg-blue-100 text-blue-700 border border-blue-200 animate-pulse' : '' }}

                                        {{ $statusAntrian === 'selesai' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}

                                        {{ $statusAntrian === 'dilewati' ? 'bg-gray-100 text-gray-600 border border-gray-200' : '' }}
                                    ">

                                        @if($statusAntrian === 'belum_datang')
                                            <i class="fa-solid fa-user-clock mr-1.5"></i>

                                        @elseif($statusAntrian === 'menunggu')
                                            <i class="fa-solid fa-clock mr-1.5"></i>

                                        @elseif($statusAntrian === 'dipanggil')
                                            <i class="fa-solid fa-microphone mr-1.5"></i>

                                        @elseif($statusAntrian === 'selesai')
                                            <i class="fa-solid fa-check mr-1.5"></i>

                                        @elseif($statusAntrian === 'dilewati')
                                            <i class="fa-solid fa-forward-step mr-1.5"></i>
                                        @endif

                                        {{ str_replace('_', ' ', $row->status) }}
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                <td class="py-4 px-4 text-center whitespace-nowrap border-b border-gray-100/80">
                                    <div class="flex items-center justify-center gap-2">

                                        {{-- HADIR --}}
                                        @if($statusAntrian === 'belum_datang')
                                            <form action="{{ route('antrian.hadir', $row->id_antrian) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')

                                                <input type="hidden" name="tanggal" value="{{ now()->toDateString() }}">

                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">

                                                    <i class="fa-solid fa-clipboard-user mr-1.5"></i>
                                                    Lapor Hadir
                                                </button>
                                            </form>
                                        @endif

                                        {{-- PANGGIL --}}
                                        @if(in_array($statusAntrian, ['menunggu', 'dilewati']))
                                            <form action="{{ route('antrian.panggil', $row->id_antrian) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')

                                                <input type="hidden" name="tanggal" value="{{ now()->toDateString() }}">

                                                @if($statusAntrian === 'dilewati')

                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">

                                                        <i class="fa-solid fa-bullhorn mr-1.5"></i>
                                                        Panggil Ulang
                                                    </button>

                                                @else

                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">

                                                        <i class="fa-solid fa-bullhorn mr-1.5"></i>
                                                        Panggil
                                                    </button>

                                                @endif
                                            </form>
                                        @endif

                                        {{-- DIPANGGIL --}}
                                        @if($statusAntrian === 'dipanggil')

                                            {{-- SELESAI --}}
                                            <form action="{{ route('antrian.selesai', $row->id_antrian) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')

                                                <input type="hidden" name="tanggal" value="{{ now()->toDateString() }}">

                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-green-500 hover:bg-green-600 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">

                                                    <i class="fa-solid fa-check-double mr-1.5"></i>
                                                    Selesai
                                                </button>
                                            </form>

                                            {{-- LEWATI --}}
                                            <form action="{{ route('antrian.lewati', $row->id_antrian) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')

                                                <input type="hidden" name="tanggal" value="{{ now()->toDateString() }}">

                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-400 hover:bg-gray-500 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">

                                                    <i class="fa-solid fa-forward-step mr-1.5"></i>
                                                    Lewati
                                                </button>
                                            </form>
                                        @endif

                                        {{-- DONE --}}
                                        @if($statusAntrian === 'selesai')
                                            <span class="text-green-500 text-sm font-bold">
                                                <i class="fa-solid fa-circle-check"></i>
                                                Done
                                            </span>
                                        @endif

                                    </div>
                                </td>
                            </tr>

                        @empty

                            {{-- EMPTY --}}
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-500">

                                    <div class="flex flex-col items-center justify-center">

                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-3">
                                            <i class="fa-solid fa-clipboard-list text-2xl"></i>
                                        </div>

                                        <p class="text-base font-bold text-gray-700">
                                            Tidak Ada Antrian Hari Ini
                                        </p>

                                        <p class="text-sm mt-1">
                                            Belum ada pasien yang masuk antrian hari ini.
                                        </p>

                                    </div>
                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER --}}
            @if(($antrian_hari_ini ?? collect())->isNotEmpty())
                <div class="mt-4 flex justify-end">
                    <a href="{{ route('antrian.index') }}"
                        class="text-sm text-orange-500 hover:text-orange-700 font-bold bg-white/50 px-4 py-2 rounded-xl transition-all hover:bg-white">

                        Kelola Semua Antrian &rarr;
                    </a>
                </div>
            @endif

        </div>
    </div>

<!-- Table Area -->
<div class="px-2">
    <div class="glass-panel rounded-3xl p-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Pasien Terakhir Mendaftar</h2>
                <p class="text-sm text-gray-500 mt-1">Daftar reservasi terbaru masuk ke sistem.</p>
            </div>
            <a href="{{ route('reservasi.index') }}" class="text-sm text-orange-500 hover:text-orange-700 font-bold bg-white/50 px-4 py-2 rounded-xl transition-all hover:bg-white text-center">Lihat Semua Data &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200/50">
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">No</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recent_pendaftaran ?? [] as $pendaftaran)
                        <tr class="border-b border-gray-100/50 hover:bg-white/40 transition-colors">
                            <td class="py-4 px-4 text-sm font-bold text-orange-500 text-center">{{ $loop->iteration }}</td>
                            <td class="py-4 px-4 text-sm text-gray-900 font-semibold">{{ $pendaftaran->pasien->nama_pasien ?? 'Unknown' }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600">{{ $pendaftaran->tanggal_kunjungan ?? $pendaftaran->created_at->format('d M Y') }}</td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold capitalize
                                    {{ $pendaftaran->status === 'menunggu' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $pendaftaran->status === 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $pendaftaran->status === 'dipanggil' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $pendaftaran->status === 'batal' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ !in_array($pendaftaran->status, ['menunggu','selesai','dipanggil','batal']) ? 'bg-gray-100 text-gray-800' : '' }}
                                ">
                                    {{ $pendaftaran->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-sm text-gray-500 font-medium">Belum ada data pendaftaran terbaru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('trendChart').getContext('2d');

        // Buat gradien lembut untuk shadow area grafik
        const gradientFill = ctx.createLinearGradient(0, 0, 0, 400);
        gradientFill.addColorStop(0, 'rgba(234, 148, 29, 0.5)'); // Core Orange Text Gradient
        gradientFill.addColorStop(1, 'rgba(234, 148, 29, 0.0)'); // Transparan

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Reservasi Pasien',
                    data: {!! json_encode($data) !!},
                    borderColor: '#EA941D', // Orange
                    backgroundColor: gradientFill,
                    borderWidth: 4,
                    pointBackgroundColor: '#FFFFFF',
                    pointBorderColor: '#EA941D',
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4 // membuat garis smooth curve (gelombang)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#4A403D',
                        titleFont: { family: 'Inter', size: 13 },
                        bodyFont: { family: 'Inter', size: 15, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Pasien Mendaftar';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.04)',
                            drawBorder: false,
                            borderDash: [5, 5]
                        },
                        ticks: { stepSize: 1, font: { family: 'Inter' }, color: '#9CA3AF' }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: 'Inter', weight: 'bold' }, color: '#6B7280' }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
