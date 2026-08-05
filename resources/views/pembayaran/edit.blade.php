@extends('layouts.admin')

@section('content')
<div class="flex items-center mb-8 px-2 gap-4">
    <a href="{{ route('pembayaran.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-orange-500 hover:shadow-md transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Proses Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui status dan nominal pembayaran.</p>
    </div>
</div>

<div class="px-2">
    <div class="glass-panel rounded-3xl p-8 max-w-2xl">
        <div class="space-y-4 mb-8">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">No. Antrian</p>
                <p class="text-2xl font-black text-orange-500">{{ optional(optional($pembayaran->pendaftaran)->antrian)->nomor_antrian ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pasien</p>
                <p class="text-lg font-bold text-gray-900">{{ optional(optional($pembayaran->pendaftaran)->pasien)->nama_pasien ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tagihan Layanan</p>
                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format(optional($pembayaran->pendaftaran->layanan)->harga ?? 0, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Biaya Tindakan</p>
                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format(optional($pembayaran->pendaftaran->rekamMedis)->biaya_tindakan ?? 0, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Biaya Obat</p>
                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format(optional($pembayaran->pendaftaran->rekamMedis)->biaya_obat ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="pt-4 border-t border-gray-100">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Tagihan</p>
                @php
                    $harga = (float) (optional($pembayaran->pendaftaran->layanan)->harga ?? 0);
                    $tindakan = (float) (optional($pembayaran->pendaftaran->rekamMedis)->biaya_tindakan ?? 0);
                    $obat = (float) (optional($pembayaran->pendaftaran->rekamMedis)->biaya_obat ?? 0);
                    $total = $harga + $tindakan + $obat;
                @endphp
                <p class="text-2xl font-black text-orange-500">Rp {{ number_format($total, 0, ',', '.') }}</p>
            </div>
        </div>

        <form action="{{ route('pembayaran.update', $pembayaran->id_pembayaran ?? $pembayaran->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Total Bayar (Rp)</label>
                <input type="number" name="total_bayar" value="{{ old('total_bayar', $pembayaran->total_bayar) }}" required min="0" class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-500/20 transition-all font-semibold text-gray-800 bg-white">
                @error('total_bayar') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Metode Pembayaran</label>
                <select name="metode_pembayaran" required class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-500/20 transition-all font-semibold text-gray-800 bg-white">
                    <option value="cash" {{ old('metode_pembayaran', strtolower(trim($pembayaran->metode_pembayaran ?? 'cash'))) === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="transfer" {{ old('metode_pembayaran', strtolower(trim($pembayaran->metode_pembayaran ?? ''))) === 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
                @error('metode_pembayaran') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Status Pembayaran</label>
                <select name="status" required class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-500/20 transition-all font-semibold text-gray-800 bg-white">
                    <option value="belum lunas" {{ old('status', strtolower(trim($pembayaran->status ?? ''))) === 'belum lunas' ? 'selected' : '' }}>Belum lunas</option>
                    <option value="lunas" {{ old('status', strtolower(trim($pembayaran->status ?? ''))) === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="pt-8 border-t border-gray-100 flex justify-end gap-3 mt-8">
                <a href="{{ route('pembayaran.index') }}" class="px-6 py-3 rounded-full text-gray-500 font-bold hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="btn-gradient px-8 py-3 rounded-full font-bold shadow-lg shadow-orange-500/30">
                    Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
