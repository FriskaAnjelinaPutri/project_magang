@extends('layouts.admin')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 px-2 mt-4 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Reservasi</h1>
        <p class="text-sm text-gray-500 mt-1">Monitoring seluruh data reservasi dan antrean pasien.</p>
    </div>
    <a href="{{ route('reservasi.create') }}" class="btn-gradient font-bold py-2.5 px-6 rounded-full shadow-lg transition-all text-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Reservasi
    </a>
</div>
@if(session('success'))
    <div class="px-2 mb-6">
        <div class="px-4 py-3 rounded-2xl bg-green-50 border border-green-200 text-green-800 text-sm font-bold">
            {{ session('success') }}
        </div>
    </div>
@endif

<div class="px-2">
    <div class="glass-panel rounded-2xl p-4 mb-5">
        <form method="GET" action="{{ route('reservasi.index') }}" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="w-full sm:w-auto">
                <label for="tanggal" class="block text-sm font-bold text-gray-700 mb-1">Filter Tanggal</label>
                <input id="tanggal" name="tanggal" type="date" value="{{ $tanggalFilter ?? now()->toDateString() }}"
                    class="w-full sm:w-56 px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all text-gray-800 font-semibold">
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold transition-colors">
                Tampilkan
            </button>
        </form>
    </div>

    <div class="glass-panel rounded-3xl p-6 sm:p-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200/50">
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">No</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Layanan</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendaftaran ?? [] as $row)
                        @php
                            $statusRaw = strtolower(trim((string) ($row->status ?? 'menunggu')));
                        @endphp
                        <tr class="border-b border-gray-100/50 hover:bg-white/40 transition-colors">
                            <td class="py-4 px-4 text-sm font-bold text-orange-500 text-center">{{ $loop->iteration }}</td>
                            <td class="py-4 px-4 text-sm text-gray-900 font-semibold">{{ $row->pasien->nama_pasien ?? 'Unknown' }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600 font-medium">{{ $row->layanan->nama_layanan ?? '-' }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600">{{ $row->tanggal_kunjungan ?? $row->created_at->format('d M Y') }}</td>
                            <td class="py-4 px-4 min-w-[180px]">
                                <form method="POST" action="{{ route('reservasi.update', $row->id_pendaftaran ?? $row->id) }}" class="inline-block w-full max-w-[220px]">
                                    @csrf
                                    @method('PUT')
                                    <label class="sr-only">Ubah status reservasi</label>
                                    <select name="status" onchange="this.form.submit()"
                                        class="w-full text-sm font-bold rounded-xl border border-gray-200 bg-white py-2 px-3 focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 cursor-pointer">
                                        <option value="menunggu" {{ $statusRaw === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="diperiksa" {{ $statusRaw === 'diperiksa' ? 'selected' : '' }}>Diperiksa</option>
                                        <option value="selesai" {{ $statusRaw === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="batal" {{ $statusRaw === 'batal' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-sm font-medium text-gray-500">Belum ada data reservasi terbaru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

