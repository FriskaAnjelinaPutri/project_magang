@extends(auth()->check() && auth()->user()->role === 'kasir' ? 'layouts.kasir' : 'layouts.admin')

@section('content')
<div class="flex items-center mb-8 px-2 gap-4">
    <a href="{{ route('pembayaran.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-orange-500 hover:shadow-md transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Detail Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1">Informasi lengkap transaksi pasien.</p>
    </div>
</div>

<div class="px-2">
    <div class="glass-panel rounded-3xl p-8 max-w-3xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">No. Antrian</p>
                    <p class="text-3xl font-black text-orange-500">{{ optional(optional($pembayaran->pendaftaran)->antrian)->nomor_antrian ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pasien</p>
                    <p class="text-xl font-bold text-gray-900">{{ optional(optional($pembayaran->pendaftaran)->pasien)->nama_pasien ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Layanan</p>
                    <p class="text-lg font-semibold text-gray-800">{{ optional(optional($pembayaran->pendaftaran)->layanan)->nama_layanan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Transaksi</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $pembayaran->tanggal_pembayaran ? \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->translatedFormat('d F Y') : '-' }}</p>
                </div>
            </div>

            <div class="space-y-6 bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Status Pembayaran</p>
                    @if(strtolower(trim($pembayaran->status ?? '')) === 'lunas')
                        <span class="inline-flex items-center px-4 py-2 mt-1 rounded-full text-sm font-bold bg-green-100 text-green-800 shadow-sm">
                            LUNAS
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 mt-1 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 shadow-sm">
                            BELUM LUNAS
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Tagihan</p>
                    <p class="text-3xl font-black text-gray-900">Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Metode Pembayaran</p>
                    <p class="text-lg font-semibold text-gray-800 capitalize">{{ $pembayaran->metode_pembayaran ?? '-' }}</p>
                </div>
                
                @if($pembayaran->bukti_transfer)
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Bukti Transfer</p>
                    <a href="{{ asset('storage/' . $pembayaran->bukti_transfer) }}" target="_blank" class="block overflow-hidden rounded-xl border border-gray-200">
                        <img src="{{ asset('storage/' . $pembayaran->bukti_transfer) }}" alt="Bukti Transfer" class="w-full object-cover max-h-48 hover:scale-105 transition-transform duration-300">
                    </a>
                </div>
                @endif
            </div>
        </div>

        <div class="pt-8 border-t border-gray-100 flex justify-end gap-3 mt-8">
            <a href="{{ route('pembayaran.index') }}" class="px-6 py-3 rounded-full text-gray-600 font-bold bg-gray-100 hover:bg-gray-200 transition-colors">Kembali</a>
            @if(strtolower(trim($pembayaran->status ?? '')) !== 'lunas')
            <a href="{{ route('pembayaran.edit', $pembayaran->id_pembayaran ?? $pembayaran->id) }}" class="btn-gradient px-8 py-3 rounded-full font-bold shadow-lg shadow-orange-500/30">
                Proses Pembayaran
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
