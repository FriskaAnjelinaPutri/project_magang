@extends(auth()->check() && auth()->user()->role === 'kasir' ? 'layouts.kasir' : 'layouts.admin')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 px-2 mt-4 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Data Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1">Manajemen transaksi dan pembayaran klinik.</p>
    </div>
    <a href="{{ route('pembayaran.create') }}" class="btn-gradient font-bold py-2.5 px-6 rounded-full shadow-lg transition-all text-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Catat Pembayaran
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
        <form method="GET" action="{{ route('pembayaran.index') }}" class="flex flex-col sm:flex-row items-end gap-3">
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
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pasien</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Bayar</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembayaran ?? [] as $row)
                        @php
                            $statusRaw = strtolower(trim((string)($row->status ?? '')));
                            $isLunas = $statusRaw === 'lunas';
                        @endphp
                        <tr class="border-b border-gray-100/50 hover:bg-white/40 transition-colors">
                            <td class="py-4 px-4 text-sm font-bold text-green-600 text-center">{{ $loop->iteration }}</td>
                            <td class="py-4 px-4 text-sm text-gray-900 font-semibold">{{ optional(optional($row->pendaftaran)->pasien)->nama_pasien ?? '-' }}</td>
                            <td class="py-4 px-4 text-sm text-gray-900 font-semibold">Rp {{ number_format($row->total_bayar, 0, ',', '.') }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600">{{ $row->tanggal_pembayaran ?? $row->created_at->format('d M Y') }}</td>
                            <td class="py-4 px-4 min-w-[180px]">
                                @if($isLunas)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-800 shadow-sm">
                                        LUNAS
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('pembayaran.update', $row->id_pembayaran ?? $row->id) }}" class="inline-block w-full max-w-[200px]">
                                        @csrf
                                        @method('PUT')
                                        <label class="sr-only">Ubah status pembayaran</label>
                                        <select name="status" onchange="this.form.submit()" class="w-full text-sm font-bold rounded-xl border border-gray-200 bg-white py-2 px-3 focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 cursor-pointer">
                                            <option value="belum lunas" {{ $statusRaw === 'belum lunas' ? 'selected' : '' }}>Belum lunas</option>
                                            <option value="lunas">Lunas</option>
                                        </select>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-sm font-medium text-gray-500">Belum ada riwayat pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
