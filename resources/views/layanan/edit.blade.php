@extends('layouts.admin')

@section('content')
<div class="flex items-center mb-8 px-2 mt-4 gap-4">
    <a href="{{ route('layanan.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-orange-500 hover:shadow-md transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Layanan</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui profil harga atau nama layanan yang sudah terdaftar.</p>
    </div>
</div>

<div class="px-2">
    <div class="glass-panel rounded-3xl p-8 max-w-3xl">
        <form action="{{ route('layanan.update', $layanan->id_layanan) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Nama Layanan -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Layanan Medis</label>
                    <input type="text" name="nama_layanan" required 
                        class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all font-semibold text-gray-800"
                        placeholder="Contoh: Pembersihan Karang Gigi (Scaling)" value="{{ old('nama_layanan', $layanan->nama_layanan) }}">
                    @error('nama_layanan') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Tambah Harga -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Biaya Layanan)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <span class="text-orange-500 font-bold">Rp</span>
                        </div>
                        <input type="number" name="harga" required 
                            class="w-full pl-12 pr-5 py-3.5 rounded-2xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all font-semibold text-gray-800"
                            placeholder="150000" min="0" value="{{ old('harga', $layanan->harga) }}">
                        @error('harga') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-100 flex justify-end gap-3 mt-8">
                    <a href="{{ route('layanan.index') }}" class="px-6 py-3 rounded-full text-gray-500 font-bold hover:bg-gray-50 transition-colors">Batal</a>
                    <button type="submit" class="btn-gradient px-8 py-3 rounded-full font-bold shadow-lg shadow-orange-500/30 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Perbarui Layanan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
