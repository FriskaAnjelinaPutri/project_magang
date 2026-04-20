@extends(auth()->check() && auth()->user()->role === 'kasir' ? 'layouts.kasir' : 'layouts.admin')

@section('content')
<div class="flex items-center mb-8 px-2 gap-4">
    <a href="{{ route('pembayaran.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-orange-500 hover:shadow-md transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Ubah Status Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1">Hanya status yang dapat diubah. Data lain bersifat informasi.</p>
    </div>
</div>

<div class="px-2">
    <div class="glass-panel rounded-3xl p-8 max-w-2xl">
        <div class="space-y-4 mb-8">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">ID Pembayaran</p>
                <p class="text-lg font-bold text-green-600">{{ $pembayaran->id_pembayaran }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">ID Pendaftaran</p>
                <p class="text-lg font-bold text-orange-500">{{ $pembayaran->id_pendaftaran }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Tagihan</p>
                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Metode</p>
                <p class="text-sm font-semibold text-gray-800">{{ ucfirst($pembayaran->metode_pembayaran ?? '-') }}</p>
            </div>
        </div>

        <form action="{{ route('pembayaran.update', $pembayaran->id_pembayaran ?? $pembayaran->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Status Pembayaran</label>
                <select name="status" required class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-500/20 transition-all font-semibold text-gray-800 bg-white">
                    <option value="belum lunas" {{ old('status', strtolower(trim($pembayaran->status ?? ''))) === 'belum lunas' ? 'selected' : '' }}>Belum lunas</option>
                    <option value="lunas" {{ old('status', strtolower(trim($pembayaran->status ?? ''))) === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
                <p class="text-xs text-gray-500 font-medium mt-2">
                    Jika Anda menyimpan sebagai <strong>Lunas</strong>, kolom total bayar akan otomatis diset ke harga layanan
                    @if(optional($pembayaran->pendaftaran->layanan)->harga)
                        (Rp {{ number_format($pembayaran->pendaftaran->layanan->harga, 0, ',', '.') }}).
                    @else
                        (sesuai master layanan jika tersedia).
                    @endif
                </p>
                @error('status') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="pt-8 border-t border-gray-100 flex justify-end gap-3 mt-8">
                <a href="{{ route('pembayaran.index') }}" class="px-6 py-3 rounded-full text-gray-500 font-bold hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="btn-gradient px-8 py-3 rounded-full font-bold shadow-lg shadow-orange-500/30">
                    Simpan Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
