@extends('layouts.admin')

@section('content')
<div class="flex items-center mb-8 px-2 mt-4 gap-4">
    <a href="{{ route('pasien.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-orange-500 hover:shadow-md transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Data Pasien</h1>
        <p class="text-sm text-gray-500 mt-1">Lakukan perubahan pada data demografi profil pasien.</p>
    </div>
</div>

<div class="px-2">
    <div class="glass-panel rounded-3xl p-8 max-w-4xl">
        <form action="{{ route('pasien.update', $pasien->id_pasien ?? $pasien->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Pasien -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-dark mb-1.5">Nama Lengkap Pasien</label>
                    <div class="relative group rounded-2xl border border-gray-200 bg-gray-50 hover:bg-white transition-all focus-within:ring-4 focus-within:ring-primary/20 focus-within:border-primary">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input type="text" name="nama_pasien" required
                            class="block w-full pl-12 pr-4 py-3.5 bg-transparent border-none focus:ring-0 text-dark placeholder-gray-400 font-bold"
                            placeholder="Cth: Budi Santoso" value="{{ old('nama_pasien', $pasien->nama_pasien) }}">
                    </div>
                    @error('nama_pasien') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- NIK KTP -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-1.5">Nomor Induk Kependudukan (NIK)</label>
                    <div class="relative group rounded-2xl border border-gray-200 bg-gray-50 hover:bg-white transition-all focus-within:ring-4 focus-within:ring-primary/20 focus-within:border-primary">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-id-card text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input type="text" name="NIK" required maxlength="16"
                            class="block w-full pl-12 pr-4 py-3.5 bg-transparent border-none focus:ring-0 text-dark placeholder-gray-400 font-bold"
                            placeholder="16 Digit NIK KTP" value="{{ old('NIK', $pasien->NIK) }}">
                    </div>
                    @error('NIK') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-1.5">Jenis Kelamin</label>
                    <div class="flex gap-4 items-center h-full pb-2">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="jenis_kelamin" value="L" class="w-4 h-4 text-primary focus:ring-primary border-gray-300" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'L' ? 'checked' : '' }} required>
                            <span class="text-dark font-medium text-sm group-hover:text-primary transition-colors">Laki-laki</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="jenis_kelamin" value="P" class="w-4 h-4 text-primary focus:ring-primary border-gray-300" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'P' ? 'checked' : '' }} required>
                            <span class="text-dark font-medium text-sm group-hover:text-primary transition-colors">Perempuan</span>
                        </label>
                    </div>
                    @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Nomor HP -->
                <div>
                    <label class="block text-sm font-bold text-dark mb-1.5">No HP / WhatsApp</label>
                    <div class="relative group rounded-2xl border border-gray-200 bg-gray-50 hover:bg-white transition-all focus-within:ring-4 focus-within:ring-primary/20 focus-within:border-primary">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-phone text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input type="text" name="no_hp" required
                            class="block w-full pl-12 pr-4 py-3.5 bg-transparent border-none focus:ring-0 text-dark placeholder-gray-400 font-bold"
                            placeholder="Cth: 0812..." value="{{ old('no_hp', $pasien->no_hp) }}">
                    </div>
                    @error('no_hp') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>
                
                <!-- Alamat (Span 2) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-dark mb-1.5">Alamat Lengkap</label>
                    <div class="relative group rounded-2xl border border-gray-200 bg-gray-50 hover:bg-white transition-all overflow-hidden focus-within:ring-4 focus-within:ring-primary/20 focus-within:border-primary">
                        <textarea name="alamat" required rows="3"
                            class="block w-full p-4 bg-transparent border-none focus:ring-0 text-dark placeholder-gray-400 font-bold resize-none"
                            placeholder="Jalan, RT/RW, Kota">{{ old('alamat', $pasien->alamat) }}</textarea>
                    </div>
                    @error('alamat') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="pt-8 border-t border-gray-100 flex justify-end gap-3 mt-8">
                <a href="{{ route('pasien.index') }}" class="px-6 py-3 rounded-full text-gray-500 font-bold hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="btn-gradient px-8 py-3 rounded-full font-bold shadow-lg shadow-orange-500/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Perbarui Data
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection
