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
        <div class="bg-orange-50/50 border border-orange-100 rounded-2xl p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-ticket text-orange-400"></i> No. Antrian</p>
                    <p class="text-3xl font-black text-orange-500 mt-1">{{ optional(optional($pembayaran->pendaftaran)->antrian)->nomor_antrian ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-user text-orange-400"></i> Pasien</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">{{ optional(optional($pembayaran->pendaftaran)->pasien)->nama_pasien ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-stethoscope text-orange-400"></i> Layanan</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ optional($pembayaran->pendaftaran)->layanans ? $pembayaran->pendaftaran->layanans->map(function($l) { return $l->nama_layanan . (($l->pivot->jumlah ?? 1) > 1 ? ' (x'.$l->pivot->jumlah.')' : ''); })->implode(', ') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-pills text-orange-400"></i> Resep Obat</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">
                        @if(optional(optional($pembayaran->pendaftaran)->rekamMedis)->resep_obat)
                            <div class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100 mt-2">
                                {!! nl2br(e($pembayaran->pendaftaran->rekamMedis->resep_obat)) !!}
                            </div>
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-orange-200/60">
                <div class="flex flex-col gap-2 mb-4">
                    <div class="flex justify-between items-center text-sm font-semibold text-gray-700">
                        <span>Biaya Layanan</span>
                        @php
                            $totalHargaLayanan = 0;
                            if(optional($pembayaran->pendaftaran)->layanans){
                                foreach($pembayaran->pendaftaran->layanans as $lay){
                                    $totalHargaLayanan += $lay->harga * ($lay->pivot->jumlah ?? 1);
                                }
                            }
                        @endphp
                        <span>Rp {{ number_format($totalHargaLayanan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col gap-1 text-sm font-semibold text-gray-700">
                        <div class="flex justify-between items-center mt-1">
                            <span>Biaya Tindakan (Dari Rekam Medis)</span>
                            <span>Rp {{ number_format(optional(optional($pembayaran->pendaftaran)->rekamMedis)->biaya_tindakan ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-dashed border-orange-300">
                    <p class="text-sm font-bold text-gray-600 uppercase tracking-wider">Total Tagihan Final</p>
                    @php
                        $hargaLayanan = (float) $totalHargaLayanan;
                        $biayaTindakan = (float) (optional(optional($pembayaran->pendaftaran)->rekamMedis)->biaya_tindakan ?? 0);
                        $biayaObat = (float) (optional(optional($pembayaran->pendaftaran)->rekamMedis)->biaya_obat ?? 0);
                        $total = $hargaLayanan + $biayaTindakan + $biayaObat;
                    @endphp
                    <p class="text-4xl font-black text-orange-600" id="total_tagihan_display">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('pembayaran.update', $pembayaran->id_pembayaran ?? $pembayaran->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Biaya Obat (Rp)</label>
                    <input type="number" id="biaya_obat" name="biaya_obat" value="{{ old('biaya_obat', optional(optional($pembayaran->pendaftaran)->rekamMedis)->biaya_obat ?? 0) }}" min="0" class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-500/20 transition-all font-semibold text-gray-800 bg-white" oninput="updateTotalBayar()">
                    @error('biaya_obat') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Total Bayar (Rp)</label>
                    <input type="number" id="total_bayar" name="total_bayar" value="{{ old('total_bayar', $pembayaran->total_bayar > 0 ? $pembayaran->total_bayar : $total) }}" required min="0" class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-500/20 transition-all font-semibold text-gray-800 bg-white">
                    @error('total_bayar') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Metode Pembayaran</label>
                    <select name="metode_pembayaran" required class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all font-semibold text-gray-800 bg-white">
                        <option value="cash" {{ old('metode_pembayaran', strtolower(trim($pembayaran->metode_pembayaran ?? 'cash'))) === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="transfer" {{ old('metode_pembayaran', strtolower(trim($pembayaran->metode_pembayaran ?? ''))) === 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                    @error('metode_pembayaran') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status Pembayaran</label>
                    <select name="status" required class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-500/20 transition-all font-semibold text-gray-800 bg-white">
                        <option value="lunas" {{ strtolower(trim($pembayaran->status)) === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum lunas" {{ strtolower(trim($pembayaran->status)) !== 'lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-100 flex justify-end gap-3 mt-8">
                <a href="{{ route('pembayaran.index') }}" class="px-6 py-3 rounded-full text-gray-500 font-bold hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-full font-bold shadow-lg shadow-orange-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-check-circle"></i> Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const hargaLayanan = {{ (float) $totalHargaLayanan }};
    const biayaTindakan = {{ (float) (optional(optional($pembayaran->pendaftaran)->rekamMedis)->biaya_tindakan ?? 0) }};
    
    function updateTotalBayar() {
        const biayaObat = parseFloat(document.getElementById('biaya_obat').value) || 0;
        const total = hargaLayanan + biayaTindakan + biayaObat;
        
        // Update input field
        document.getElementById('total_bayar').value = total;
        
        // Update display with format
        document.getElementById('total_tagihan_display').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endsection
