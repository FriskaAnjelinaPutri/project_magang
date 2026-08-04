@extends($layout)

@section('content')
<!-- Header Area -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Isi Rekam Medis</h1>
        <p class="text-sm text-gray-500 mt-1">Formulir pemeriksaan dan catatan medis pasien.</p>
    </div>
    <a href="{{ route('dashboard.dokter') }}" class="px-5 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold transition-colors shadow-sm">
        Kembali
    </a>
</div>

<div class="glass-panel rounded-3xl p-6 sm:p-8 max-w-4xl">
    <form action="{{ route('rekam-medis.store') }}" method="POST">
        @csrf
        
        <!-- Informasi Pasien -->
        <div class="mb-8 p-5 bg-orange-50/50 rounded-2xl border border-orange-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Informasi Pasien
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Pasien</label>
                    <div class="text-base font-bold text-gray-900">{{ $pendaftaran->pasien->nama_pasien ?? 'Pasien Umum' }}</div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Layanan</label>
                    <div class="text-base font-bold text-gray-900">{{ $pendaftaran->layanan->nama_layanan ?? '-' }}</div>
                </div>
            </div>
            
            <!-- Hidden inputs -->
            <input type="hidden" name="id_pasien" value="{{ $pendaftaran->id_pasien ?? '' }}">
            <input type="hidden" name="id_pendaftaran" value="{{ $pendaftaran->id_pendaftaran ?? '' }}">
            <input type="hidden" name="tanggal_periksa" value="{{ date('Y-m-d') }}">
        </div>

        <!-- Form Input Medis -->
        <div class="space-y-6">
            <!-- Keluhan -->
            <div>
                <label for="keluhan" class="block text-sm font-bold text-gray-700 mb-2">Keluhan / Anamnesa <span class="text-red-500">*</span></label>
                <textarea id="keluhan" name="keluhan" rows="3" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all bg-gray-50 focus:bg-white resize-none placeholder-gray-400 text-gray-700 font-medium"
                    placeholder="Tuliskan keluhan utama pasien..."></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Tindakan -->
                <div class="space-y-3">
                    <div>
                        <label for="tindakan" class="block text-sm font-bold text-gray-700 mb-2">Tindakan Medis <span class="text-red-500">*</span></label>
                        <textarea id="tindakan" name="tindakan" rows="3" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all bg-gray-50 focus:bg-white resize-none placeholder-gray-400 text-gray-700 font-medium"
                            placeholder="Tindakan yang diberikan..."></textarea>
                    </div>
                    <div>
                        <label for="biaya_tindakan" class="block text-sm font-bold text-gray-700 mb-2">Biaya Tindakan (Opsional)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">Rp</span>
                            <input type="number" id="biaya_tindakan" name="biaya_tindakan" value="0" min="0" step="1000"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all text-gray-800 font-semibold bg-gray-50 focus:bg-white">
                        </div>
                    </div>
                </div>

                <!-- Resep Obat -->
                <div class="space-y-3">
                    <div>
                        <label for="resep_obat" class="block text-sm font-bold text-gray-700 mb-2">Resep Obat <span class="text-red-500">*</span></label>
                        <textarea id="resep_obat" name="resep_obat" rows="3" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all bg-gray-50 focus:bg-white resize-none placeholder-gray-400 text-gray-700 font-medium"
                            placeholder="Daftar resep obat (Nama Obat, Dosis)..."></textarea>
                    </div>
                    <div>
                        <label for="biaya_obat" class="block text-sm font-bold text-gray-700 mb-2">Biaya Obat (Opsional)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">Rp</span>
                            <input type="number" id="biaya_obat" name="biaya_obat" value="0" min="0" step="1000"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all text-gray-800 font-semibold bg-gray-50 focus:bg-white">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="px-8 py-3 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold transition-all shadow-lg hover:shadow-orange-500/30 flex items-center transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Simpan Rekam Medis
            </button>
        </div>
    </form>
</div>
@endsection
