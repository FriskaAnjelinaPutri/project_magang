@extends('layouts.dokter')

@section('content')
<!-- Header Area -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Antrian Pasien Hari Ini</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar pasien yang terjadwal dan menunggu pemeriksaan.</p>
    </div>

    <div class="flex items-center gap-4 glass-card px-4 py-2 rounded-full hidden sm:flex">
        <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center text-white shadow-md text-sm font-bold">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div>
            <p class="text-sm font-bold text-gray-800 leading-none">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    <!-- Total Antrian -->
    <div class="glass-card rounded-2xl p-5 flex items-center space-x-4">
        <div class="p-3 bg-orange-50/80 rounded-2xl text-orange-500">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</p>
            <h3 class="text-2xl font-black text-gray-800">{{ $stats['total'] }}</h3>
        </div>
    </div>

    <!-- Menunggu -->
    <div class="glass-card rounded-2xl p-5 flex items-center space-x-4">
        <div class="p-3 bg-yellow-50/80 rounded-2xl text-yellow-600">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menunggu</p>
            <h3 class="text-2xl font-black text-yellow-600">{{ $stats['menunggu'] }}</h3>
        </div>
    </div>

    <!-- Dipanggil -->
    <div class="glass-card rounded-2xl p-5 flex items-center space-x-4">
        <div class="p-3 bg-blue-50/80 rounded-2xl text-blue-600">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072M12 12h.01M8.464 8.464a5 5 0 000 7.072M18.364 5.636a9 9 0 010 12.728M5.636 5.636a9 9 0 000 12.728"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dipanggil</p>
            <h3 class="text-2xl font-black text-blue-600">{{ $stats['dipanggil'] }}</h3>
        </div>
    </div>

    <!-- Selesai -->
    <div class="glass-card rounded-2xl p-5 flex items-center space-x-4">
        <div class="p-3 bg-green-50/80 rounded-2xl text-green-600">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Selesai</p>
            <h3 class="text-2xl font-black text-green-600">{{ $stats['selesai'] }}</h3>
        </div>
    </div>
</div>

<!-- Filter Tanggal -->
<div class="glass-panel rounded-2xl p-4 mb-6">
    <form method="GET" action="{{ route('dashboard.dokter') }}" class="flex flex-col sm:flex-row items-end gap-3">
        <div class="w-full sm:w-auto">
            <label for="tanggal" class="block text-sm font-bold text-gray-700 mb-1">Filter Tanggal</label>
            <input id="tanggal" name="tanggal" type="date" value="{{ $tanggal }}"
                class="w-full sm:w-56 px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all text-gray-800 font-semibold">
        </div>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold transition-colors shadow-md hover:shadow-lg">
            Tampilkan
        </button>
    </form>
</div>

<!-- Sedang Dipanggil Banner -->
@php
    $sedangDipanggil = $antrian->firstWhere('status', 'dipanggil');
@endphp
@if($sedangDipanggil)
<div class="mb-6 glass-panel rounded-2xl p-5 border-l-4 border-l-blue-500 animate-pulse">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-blue-100 rounded-xl text-blue-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 12h.01M8.464 8.464a5 5 0 000 7.072"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-1">Sedang Dipanggil</p>
            <p class="text-2xl font-black text-gray-900">
                No. {{ (int) $sedangDipanggil->nomor_antrian }}
                <span class="text-base font-semibold text-gray-600 ml-2">—</span>
                <span class="text-lg font-bold text-gray-700 ml-2">{{ $sedangDipanggil->pendaftaran->pasien->nama_pasien ?? 'Unknown' }}</span>
            </p>
            <p class="text-sm text-gray-500 mt-1">Layanan: <span class="font-semibold text-gray-700">{{ $sedangDipanggil->pendaftaran->layanan->nama_layanan ?? '-' }}</span></p>
        </div>
    </div>
</div>
@endif

<!-- Session Messages -->
@if (session('success'))
<div class="mb-6 p-4 bg-green-50/80 border border-green-200 text-green-700 rounded-2xl flex items-center shadow-sm">
    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <span class="font-bold">{{ session('success') }}</span>
</div>
@endif
@if (session('error'))
<div class="mb-6 p-4 bg-red-50/80 border border-red-200 text-red-700 rounded-2xl flex items-center shadow-sm">
    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <span class="font-bold">{{ session('error') }}</span>
</div>
@endif

<!-- Tabel Antrian -->
<div class="glass-panel rounded-3xl p-4 sm:p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900">Daftar Antrian</h2>
        <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
            {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
        </span>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-gray-200/70 bg-white/70">
        <table class="w-full text-left border-separate border-spacing-0">
            <thead>
                <tr class="bg-gray-50/90">
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center border-b border-gray-200/80">No. Antrian</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Nama Pasien</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Layanan</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center border-b border-gray-200/80">Status / Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($antrian as $row)
                    @php
                        $statusAntrian = strtolower(trim((string) $row->status));
                    @endphp
                    <tr class="odd:bg-white even:bg-gray-50/50 hover:bg-orange-50/40 transition-colors
                        {{ $statusAntrian === 'dipanggil' ? 'ring-2 ring-blue-200 bg-blue-50/30' : '' }}">
                        <td class="py-4 px-4 text-2xl font-black text-center border-b border-gray-100/80
                            {{ $statusAntrian === 'dipanggil' ? 'text-blue-600' : 'text-orange-500' }}">
                            {{ (int) $row->nomor_antrian }}
                        </td>
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <div class="text-sm text-gray-900 font-bold">
                                {{ $row->pendaftaran->pasien->nama_pasien ?? 'Unknown' }}
                            </div>
                        </td>
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <div class="text-sm text-gray-700 font-medium">
                                {{ $row->pendaftaran->layanan->nama_layanan ?? '-' }}
                            </div>
                        </td>
                        <td class="py-4 px-4 text-center border-b border-gray-100/80">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold capitalize
                                {{ $statusAntrian === 'menunggu' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}
                                {{ $statusAntrian === 'dipanggil' ? 'bg-blue-100 text-blue-700 border border-blue-200 animate-pulse' : '' }}
                                {{ $statusAntrian === 'selesai' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}
                                {{ $statusAntrian === 'dilewati' ? 'bg-gray-100 text-gray-600 border border-gray-200' : '' }}
                                {{ !in_array($statusAntrian, ['menunggu','dipanggil','selesai','dilewati']) ? 'bg-gray-100 text-gray-600' : '' }}
                            ">
                                @if($statusAntrian === 'menunggu')
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @elseif($statusAntrian === 'dipanggil')
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 12h.01"></path></svg>
                                @elseif($statusAntrian === 'selesai')
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                @elseif($statusAntrian === 'dilewati')
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                @endif
                                {{ $row->status }}
                            </span>

                            @if($statusAntrian === 'dipanggil')
                                <div class="mt-3 flex flex-col sm:flex-row justify-center items-center gap-2">
                                    <form action="{{ route('antrian.lewati', $row->id_antrian) }}" method="POST" class="inline m-0">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                        <button type="submit" class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors border border-yellow-200 w-full sm:w-auto" onclick="return confirm('Pasien belum hadir. Lewati nomor ini dan panggil berikutnya?')">
                                            Lewati
                                        </button>
                                    </form>

                                    <form action="{{ route('antrian.selesai', $row->id_antrian) }}" method="POST" class="inline m-0">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                        <button type="submit" class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors border border-green-200 w-full sm:w-auto">
                                            Selesai
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                </div>
                                <p class="text-base font-bold text-gray-700">Tidak Ada Antrian</p>
                                <p class="text-sm text-gray-500 mt-1">Belum ada pasien yang masuk antrian pada tanggal ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    // Auto-refresh halaman setiap 30 detik untuk update antrian real-time
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
@endpush
@endsection
