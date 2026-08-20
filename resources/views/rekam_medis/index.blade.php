@extends($layout)

@section('content')
<!-- Header Area -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Data Rekam Medis</h1>
        <p class="text-sm text-gray-500 mt-1">Riwayat pemeriksaan dan catatan medis pasien.</p>
    </div>
</div>

<div class="glass-panel rounded-2xl p-4 mb-5">
    <form method="GET" action="{{ route('rekam-medis.index') }}" class="flex flex-col sm:flex-row items-end gap-3 w-full">
        <div class="flex-1 flex flex-col sm:flex-row items-end gap-3">
            <div class="w-full sm:w-auto">
                <label for="tanggal" class="block text-sm font-bold text-gray-700 mb-1">Filter Tanggal</label>
                <input id="tanggal" name="tanggal" type="date" value="{{ $tanggalFilter ?? now()->toDateString() }}"
                    class="w-full sm:w-56 px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all text-gray-800 font-semibold">
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold transition-colors">
                Tampilkan
            </button>
        </div>
        <a href="{{ route('rekam-medis.cetak', ['tanggal' => $tanggalFilter ?? now()->toDateString()]) }}" target="_blank" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold transition-all shadow-md hover:shadow-lg flex items-center gap-2 transform hover:-translate-y-0.5 w-full sm:w-auto mt-3 sm:mt-0 justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Laporan (PDF)
        </a>
    </form>
</div>

<!-- Session Messages -->
@if (session('success'))
<div class="mb-6 p-4 bg-green-50/80 border border-green-200 text-green-700 rounded-2xl flex items-center shadow-sm">
    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <span class="font-bold">{{ session('success') }}</span>
</div>
@endif

<!-- Tabel Rekam Medis -->
<div class="glass-panel rounded-3xl p-4 sm:p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900">Daftar Rekam Medis</h2>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-gray-200/70 bg-white/70">
        <table class="w-full text-left border-separate border-spacing-0">
            <thead>
                <tr class="bg-gray-50/90">
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Tanggal</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Nama Pasien</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Layanan</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Keluhan</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Tindakan</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Resep Obat</th>
                    @if(auth()->user()->role === 'dokter')
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80 text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($rekamMedis as $rm)
                    <tr class="odd:bg-white even:bg-gray-50/50 hover:bg-orange-50/40 transition-colors">
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <div class="text-sm text-gray-900 font-bold">
                                {{ \Carbon\Carbon::parse($rm->tanggal_periksa)->translatedFormat('d M Y') }}
                            </div>
                        </td>
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <div class="text-sm text-gray-900 font-bold">
                                {{ $rm->pasien->nama_pasien ?? 'Unknown' }}
                            </div>
                        </td>
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ optional($rm->pendaftaran)->layanans ? $rm->pendaftaran->layanans->pluck('nama_layanan')->implode(', ') : '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <div class="text-sm text-gray-800">
                                {{ $rm->keluhan }}
                            </div>
                        </td>
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <div class="text-sm text-gray-800">
                                {{ $rm->tindakan }}
                            </div>
                        </td>
                        <td class="py-4 px-4 border-b border-gray-100/80">
                            <div class="text-sm text-gray-800">
                                @if($rm->resep_obat)
                                    <div class="text-sm text-gray-700 bg-gray-50 p-2 rounded border border-gray-100">
                                        {!! nl2br(e($rm->resep_obat)) !!}
                                    </div>
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        @if(auth()->user()->role === 'dokter')
                        <td class="py-4 px-4 border-b border-gray-100/80 text-center">
                            <button onclick="bukaModalRujukan({{ $rm->id_rekam_medis }})" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-orange-100 hover:bg-orange-200 text-orange-700 text-xs font-bold transition-colors shadow-sm">
                                📝 Rujukan
                            </button>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'dokter' ? '7' : '6' }}" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-base font-bold text-gray-700">Belum Ada Data Rekam Medis</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(auth()->user()->role === 'dokter')
<!-- Modal Surat Rujukan -->
<div id="modal-rujukan" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="tutupModalRujukan()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <form id="form-rujukan" method="POST" action="">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-8">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-2xl leading-6 font-extrabold text-gray-900 mb-6" id="modal-title">Buat Surat Rujukan</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                <div>
                                    <p class="text-sm text-gray-500 font-semibold mb-1">Nama Pasien</p>
                                    <p class="font-bold text-gray-900" id="rujukan_nama_pasien">-</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-semibold mb-1">Keluhan/Anamnese</p>
                                    <p class="font-bold text-gray-900" id="rujukan_keluhan">-</p>
                                </div>
                            </div>

                            <div class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Yth. Dokter Gigi <span class="text-red-500">*</span></label>
                                        <input type="text" name="rujukan_dokter" id="rujukan_dokter" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 text-gray-800 font-medium" placeholder="Contoh: drg. Budi, Sp.Ort">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Di RSU <span class="text-red-500">*</span></label>
                                        <input type="text" name="rujukan_rs" id="rujukan_rs" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 text-gray-800 font-medium" placeholder="Contoh: RSUD Padang Panjang">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Diagnosa Sementara</label>
                                    <textarea name="rujukan_diagnosa_sementara" id="rujukan_diagnosa_sementara" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 text-gray-800 font-medium"></textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kasus</label>
                                    <textarea name="rujukan_kasus" id="rujukan_kasus" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 text-gray-800 font-medium"></textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Terapi/Obat yang telah diberikan</label>
                                    <textarea name="rujukan_terapi" id="rujukan_terapi" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 text-gray-800 font-medium"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse rounded-b-3xl">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-orange-600 text-base font-bold text-white hover:bg-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-500/20 sm:ml-3 sm:w-auto sm:text-sm transition-all">
                        Simpan Rujukan
                    </button>
                    <button type="button" onclick="tutupModalRujukan()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const rekamMedisData = @json($rekamMedis);

    function bukaModalRujukan(id) {
        const modal = document.getElementById('modal-rujukan');
        const form = document.getElementById('form-rujukan');
        const rm = rekamMedisData.find(item => item.id_rekam_medis === id);
        
        if (rm) {
            form.action = `/rekam-medis/${id}/rujukan`;
            
            document.getElementById('rujukan_nama_pasien').textContent = rm.pasien?.nama_pasien || '-';
            document.getElementById('rujukan_keluhan').textContent = rm.keluhan || '-';
            
            document.getElementById('rujukan_dokter').value = rm.rujukan_dokter || '';
            document.getElementById('rujukan_rs').value = rm.rujukan_rs || '';
            document.getElementById('rujukan_diagnosa_sementara').value = rm.rujukan_diagnosa_sementara || '';
            document.getElementById('rujukan_kasus').value = rm.rujukan_kasus || '';
            document.getElementById('rujukan_terapi').value = rm.rujukan_terapi || '';
            
            modal.classList.remove('hidden');
        }
    }

    function tutupModalRujukan() {
        const modal = document.getElementById('modal-rujukan');
        modal.classList.add('hidden');
    }
</script>
@endif
@endsection
