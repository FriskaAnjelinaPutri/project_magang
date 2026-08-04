@extends($layout)

@section('content')
<!-- Header Area -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Data Rekam Medis</h1>
        <p class="text-sm text-gray-500 mt-1">Riwayat pemeriksaan dan catatan medis pasien.</p>
    </div>
</div>

<div class="glass-panel rounded-2xl p-4 mb-5">
    <form method="GET" action="{{ route('rekam-medis.index') }}" class="flex flex-col sm:flex-row items-end gap-3 w-full">
        <div class="flex-1 flex flex-col sm:flex-row items-end gap-3">
            <div class="w-full sm:w-auto">
                <label for="tanggal" class="block text-sm font-bold text-gray-700 mb-1">Filter Tanggal</label>
                <input id="tanggal" name="tanggal" type="date" value="{{ $tanggalFilter ?? now()->toDateString() }}"
                    class="w-full sm:w-56 px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all text-gray-800 font-semibold">
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold transition-colors">
                Tampilkan
            </button>
        </div>
        <a href="{{ route('rekam-medis.cetak', ['tanggal' => $tanggalFilter ?? now()->toDateString()]) }}" target="_blank" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold transition-all shadow-md hover:shadow-lg flex items-center gap-2 transform hover:-translate-y-0.5 w-full sm:w-auto mt-3 sm:mt-0 justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Laporan (PDF)
        </a>
    </form>
</div>

<!-- Session Messages -->
@if (session('success'))
<div class="mb-6 p-4 bg-green-50/80 border border-green-200 text-green-700 rounded-2xl flex items-center shadow-sm">
    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <span class="font-bold">{{ session('success') }}</span>
</div>
@endif

<!-- Tabel Rekam Medis -->
<div class="glass-panel rounded-3xl p-4 sm:p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900">Daftar Rekam Medis</h2>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-gray-200/70 bg-white/70">
        <table class="w-full text-left border-separate border-spacing-0">
            <thead>
                <tr class="bg-gray-50/90">
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Tanggal</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Nama Pasien</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Layanan</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Keluhan</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Tindakan</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Resep Obat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rekamMedis as $rm)
                    <tr class="odd:bg-white even:bg-gray-50/50 hover:bg-orange-50/40 transition-colors">
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <div class="text-sm text-gray-900 font-bold">
                                {{ \Carbon\Carbon::parse($rm->tanggal_periksa)->translatedFormat('d M Y') }}
                            </div>
                        </td>
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <div class="text-sm text-gray-900 font-bold">
                                {{ $rm->pasien->nama_pasien ?? 'Unknown' }}
                            </div>
                        </td>
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $rm->pendaftaran->layanan->nama_layanan ?? '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <div class="text-sm text-gray-800">
                                {{ $rm->keluhan }}
                            </div>
                        </td>
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <div class="text-sm text-gray-800">
                                {{ $rm->tindakan }}
                            </div>
                        </td>
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <div class="text-sm text-gray-800">
                                {{ $rm->resep_obat }}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-base font-bold text-gray-700">Belum Ada Data Rekam Medis</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
