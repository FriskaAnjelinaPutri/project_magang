@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="glass-card rounded-[28px] p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 mb-8">
            <div>
                <p class="text-primary font-bold uppercase tracking-widest text-xs">Dashboard Pasien</p>
                <h1 class="text-2xl md:text-3xl font-extrabold text-dark mt-1">
                    Halo, {{ $pasien->nama_pasien }}
                </h1>
                <p class="text-dark/60 font-medium mt-2">
                    Pantau status antrian dan riwayat pendaftaran Anda di sini.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('pendaftaran.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-primary to-accent hover:from-secondary hover:to-primary text-white font-bold shadow-lg shadow-primary/30 transition-all">
                    <i class="fa-solid fa-calendar-plus"></i>
                    Daftar Antrian Baru
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-white border border-gray-200 text-dark/70 hover:text-red-500 hover:border-red-200 font-bold transition-colors">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <p class="text-dark/60 text-sm font-semibold">Total Pendaftaran</p>
                <p class="text-3xl font-extrabold text-dark mt-2">{{ $totalReservasi }}</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <p class="text-dark/60 text-sm font-semibold">Antrian Hari Ini</p>
                <p class="text-3xl font-extrabold text-primary mt-2">{{ $antrianHariIni }}</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <p class="text-dark/60 text-sm font-semibold">Riwayat Selesai</p>
                <p class="text-3xl font-extrabold text-green-600 mt-2">{{ $riwayat }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-lg md:text-xl font-extrabold text-dark">Riwayat Pendaftaran Anda</h2>
            </div>

            @if($reservasi->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-primary/5 text-dark/60 uppercase tracking-wider text-xs font-bold">
                                <th class="px-5 py-3 text-left">Tanggal</th>
                                <th class="px-5 py-3 text-left">Layanan</th>
                                <th class="px-5 py-3 text-center">No. Antrian</th>
                                <th class="px-5 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-center">Cetak</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($reservasi as $item)
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-5 py-4 font-semibold text-dark">
                                        {{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-5 py-4 text-dark/80">
                                        {{ $item->layanan->nama_layanan ?? '-' }}
                                    </td>
                                    <td class="px-5 py-4 text-center font-bold text-dark">
                                        {{ $item->antrian->nomor_antrian ?? '-' }}
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @php
                                            $status = strtolower((string) $item->status);
                                        @endphp
                                        @if($status === 'menunggu')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">Menunggu</span>
                                        @elseif($status === 'dipanggil' || $status === 'diperiksa' || $status === 'diproses')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">Diproses</span>
                                        @elseif($status === 'selesai')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">Selesai</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-bold">{{ ucfirst($item->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @if($item->antrian)
                                            <a href="{{ route('pendaftaran.cetak', $item->antrian->id_antrian) }}"
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors"
                                               title="Cetak Tiket">
                                                <i class="fa-solid fa-print"></i>
                                            </a>
                                        @else
                                            <span class="text-dark/40">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-10 text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-primary/10 text-primary flex items-center justify-center mb-4">
                        <i class="fa-solid fa-calendar-xmark text-2xl"></i>
                    </div>
                    <p class="text-dark font-bold">Belum ada riwayat pendaftaran</p>
                    <p class="text-dark/60 mt-1">Silakan ambil antrian pertama Anda.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
