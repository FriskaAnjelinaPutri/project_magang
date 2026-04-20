@extends('layouts.admin')

@section('content')
<div class="flex items-center mb-8 px-2 mt-4 gap-4">
    <a href="{{ route('pasien.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-orange-500 hover:shadow-md transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pendaftaran Pasien Offline</h1>
        <p class="text-sm text-gray-500 mt-1">Tambahkan data demografi pasien yang mendaftar secara manual di klinik.</p>
    </div>
</div>

<div class="px-2">
    <div class="glass-panel rounded-3xl p-8 max-w-4xl">
        <form action="{{ route('pasien.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Pasien -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap Pasien</label>
                    <input type="text" name="nama_pasien" required 
                        class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all font-semibold text-gray-800"
                        placeholder="Contoh: Budi Santoso" value="{{ old('nama_pasien') }}">
                    @error('nama_pasien') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- NIK KTP -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Induk Kependudukan (NIK)</label>
                    <input type="number" name="NIK" required 
                        class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all font-semibold text-gray-800"
                        placeholder="Ketik 16 Digit NIK" minlength="16" maxlength="16" value="{{ old('NIK') }}">
                    @error('NIK') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin</label>
                    <div class="grid grid-cols-2 gap-4 h-[52px]">
                        <label class="flex items-center justify-center gap-2 border border-gray-200 rounded-2xl cursor-pointer hover:border-orange-500 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50 transition-all px-4">
                            <input type="radio" name="jenis_kelamin" value="L" class="w-4 h-4 text-orange-500 focus:ring-orange-500 border-gray-300" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                            <span class="font-bold text-sm text-gray-700 whitespace-nowrap">Laki-Laki</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 border border-gray-200 rounded-2xl cursor-pointer hover:border-orange-500 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50 transition-all px-4">
                            <input type="radio" name="jenis_kelamin" value="P" class="w-4 h-4 text-orange-500 focus:ring-orange-500 border-gray-300" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required>
                            <span class="font-bold text-sm text-gray-700 whitespace-nowrap">Perempuan</span>
                        </label>
                    </div>
                    @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Nomor HP -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor HP Aktif</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-bold">+62</span>
                        </div>
                        <input type="tel" name="no_hp" required 
                            class="w-full pl-12 pr-5 py-3.5 rounded-2xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all font-semibold text-gray-800"
                            placeholder="81234567890" value="{{ old('no_hp') }}">
                    </div>
                    @error('no_hp') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>
                
                <!-- Alamat (Span 2) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" required rows="3"
                        class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all font-semibold text-gray-800"
                        placeholder="Nama Jalan, Kelurahan, Kecamatan, Kota / Kabupaten..." >{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="pt-8 border-t border-gray-100 flex justify-end gap-3 mt-8">
                <button type="reset" class="px-6 py-3 rounded-full text-gray-500 font-bold hover:bg-gray-50 transition-colors">Bersihkan</button>
                <button type="submit" class="btn-gradient px-8 py-3 rounded-full font-bold shadow-lg shadow-orange-500/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Simpan Data Pasien
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection
