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
                <div class="col-span-1 sm:col-span-2 mt-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Layanan Final (Centang yang benar-benar dikerjakan)<span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 gap-4">
                        @php
                            $selectedLayanan = optional($pendaftaran)->layanans ? $pendaftaran->layanans->pluck('id_layanan')->toArray() : [];
                        @endphp
                        @foreach($layanan as $l)
                            <div class="border rounded-xl p-3 {{ in_array($l->id_layanan, $selectedLayanan) || $l->children->whereIn('id_layanan', $selectedLayanan)->count() > 0 ? 'bg-orange-50 border-orange-200' : 'bg-white border-gray-200' }} transition-colors">
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center cursor-pointer flex-1">
                                        <!-- Jika punya anak, parent tidak disubmit valuenya, agar harga patokan hanya dari anak -->
                                        <input type="checkbox" 
                                            {!! $l->children->count() == 0 ? 'name="id_layanan[]"' : '' !!} 
                                            value="{{ $l->id_layanan }}" 
                                            class="w-5 h-5 text-orange-500 border-gray-300 rounded focus:ring-orange-500 parent-checkbox" 
                                            data-id="{{ $l->id_layanan }}"
                                            {{ in_array($l->id_layanan, $selectedLayanan) || $l->children->whereIn('id_layanan', $selectedLayanan)->count() > 0 ? 'checked' : '' }}>
                                        <span class="ml-3 text-sm font-bold text-gray-800">{{ $l->nama_layanan }} {{ $l->children->count() == 0 ? '(Rp ' . number_format($l->harga, 0, ',', '.') . ')' : '' }}</span>
                                    </label>
                                    @if($l->children->count() == 0)
                                        @php
                                            $qtyParent = 1;
                                            if(optional($pendaftaran)->layanans && $pendaftaran->layanans->contains('id_layanan', $l->id_layanan)){
                                                $qtyParent = $pendaftaran->layanans->where('id_layanan', $l->id_layanan)->first()->pivot->jumlah ?? 1;
                                            }
                                        @endphp
                                        <div class="flex items-center gap-2 ml-4">
                                            <span class="text-xs font-bold text-gray-500 uppercase">Qty</span>
                                            <input type="number" name="jumlah[{{ $l->id_layanan }}]" value="{{ $qtyParent }}" min="1" class="w-16 h-8 px-2 text-sm border-gray-300 rounded-lg focus:ring-orange-500 qty-input" {{ in_array($l->id_layanan, $selectedLayanan) ? '' : 'disabled' }}>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($l->children->count() > 0)
                                    <div class="mt-3 pl-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 child-container" id="children-{{ $l->id_layanan }}" style="{{ in_array($l->id_layanan, $selectedLayanan) || $l->children->whereIn('id_layanan', $selectedLayanan)->count() > 0 ? '' : 'display: none;' }}">
                                        @foreach($l->children as $child)
                                            @php
                                                $qtyChild = 1;
                                                if(optional($pendaftaran)->layanans && $pendaftaran->layanans->contains('id_layanan', $child->id_layanan)){
                                                    $qtyChild = $pendaftaran->layanans->where('id_layanan', $child->id_layanan)->first()->pivot->jumlah ?? 1;
                                                }
                                            @endphp
                                            <div class="flex items-center justify-between p-2 border rounded-lg hover:bg-orange-100 transition-colors {{ in_array($child->id_layanan, $selectedLayanan) ? 'bg-orange-100 border-orange-300' : 'bg-white border-gray-100' }} child-label">
                                                <label class="flex items-center cursor-pointer flex-1">
                                                    <input type="checkbox" name="id_layanan[]" value="{{ $child->id_layanan }}" class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500 child-checkbox" {{ in_array($child->id_layanan, $selectedLayanan) ? 'checked' : '' }}>
                                                    <span class="ml-2 text-xs font-semibold text-gray-700">{{ $child->nama_layanan }} (Rp {{ number_format($child->harga, 0, ',', '.') }})</span>
                                                </label>
                                                <div class="flex items-center gap-1 ml-2">
                                                    <span class="text-[10px] font-bold text-gray-500 uppercase">Qty</span>
                                                    <input type="number" name="jumlah[{{ $child->id_layanan }}]" value="{{ $qtyChild }}" min="1" class="w-12 h-6 px-1 text-xs border-gray-300 rounded focus:ring-orange-500 qty-input" {{ in_array($child->id_layanan, $selectedLayanan) ? '' : 'disabled' }}>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @error('id_layanan')
                        <p class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</p>
                    @enderror
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
                <label for="keluhan" class="block text-sm font-bold text-gray-700 mb-2">Keluhan<span class="text-red-500">*</span></label>
                <textarea id="keluhan" name="keluhan" rows="3" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all bg-gray-50 focus:bg-white resize-none placeholder-gray-400 text-gray-700 font-medium"
                    placeholder="Tuliskan keluhan utama pasien..."></textarea>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Tindakan -->
                <div class="space-y-3">
                    <div>
                        <label for="tindakan" class="block text-sm font-bold text-gray-700 mb-2">Tindakan Medis <span class="text-red-500">*</span></label>
                        <textarea id="tindakan" name="tindakan" rows="3" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all bg-gray-50 focus:bg-white resize-none placeholder-gray-400 text-gray-700 font-medium"
                            placeholder="Tindakan yang diberikan..."></textarea>
                    </div>
                </div>

                <!-- Resep Obat Dinamis -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-bold text-gray-700">Resep Obat (Pilih Obat)</label>
                        <button type="button" id="btn-tambah-obat" class="px-3 py-1.5 rounded-lg bg-green-500 hover:bg-green-600 text-white text-xs font-bold shadow-sm transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Obat
                        </button>
                    </div>
                    
                    <!-- Container untuk baris obat -->
                    <div id="obat-container" class="space-y-2">
                        <!-- Baris obat kosong sebagai template (disembunyikan) -->
                        <div class="obat-row flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl hidden" id="obat-template">
                            <div class="flex-1">
                                <select name="nama_obat_list[]" class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 obat-select" disabled>
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach($obatList as $obat)
                                        <option value="{{ $obat['nama_obat'] }}" data-harga="{{ $obat['harga'] }}">{{ $obat['nama_obat'] }} (Rp {{ number_format($obat['harga'], 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-20">
                                <input type="number" name="jumlah_obat[]" placeholder="Pcs" min="1" value="1" class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500" disabled>
                            </div>
                            <div class="w-48">
                                <input type="text" name="dosis_obat[]" placeholder="Dosis (Cth: 1x sehari untuk 5 hari)" class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500" disabled>
                            </div>
                            <button type="button" class="btn-hapus-obat p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Resep Obat Catatan -->
                <div class="space-y-3">
                    <div>
                        <label for="resep_obat" class="block text-sm font-bold text-gray-700 mb-2">Catatan Tambahan Resep (Opsional)</label>
                        <textarea id="resep_obat" name="resep_obat" rows="2"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all bg-gray-50 focus:bg-white resize-none placeholder-gray-400 text-gray-700 font-medium"
                            placeholder="Catatan tambahan resep obat..."></textarea>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const parentCheckboxes = document.querySelectorAll('.parent-checkbox');
        
        parentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const parentId = this.getAttribute('data-id');
                const childContainer = document.getElementById('children-' + parentId);
                
                // Toggle quantity input for parent (if it has no children)
                const parentDiv = this.closest('.border.rounded-xl');
                const parentQtyInput = parentDiv.querySelector('.qty-input');
                if (parentQtyInput && !childContainer) {
                    parentQtyInput.disabled = !this.checked;
                }

                if (childContainer) {
                    if (this.checked) {
                        childContainer.style.display = 'grid';
                    } else {
                        childContainer.style.display = 'none';
                        // Uncheck all children when parent is unchecked
                        const childCheckboxes = childContainer.querySelectorAll('.child-checkbox');
                        childCheckboxes.forEach(child => {
                            child.checked = false;
                            const label = child.closest('.child-label');
                            label.classList.remove('bg-orange-100', 'border-orange-300');
                            label.classList.add('bg-white', 'border-gray-100');
                            
                            // disable child qty input
                            const childQtyInput = label.querySelector('.qty-input');
                            if(childQtyInput) childQtyInput.disabled = true;
                        });
                    }
                }
                
                // Toggle parent styling
                if (this.checked) {
                    parentDiv.classList.add('bg-orange-50', 'border-orange-200');
                    parentDiv.classList.remove('bg-white', 'border-gray-200');
                } else {
                    parentDiv.classList.remove('bg-orange-50', 'border-orange-200');
                    parentDiv.classList.add('bg-white', 'border-gray-200');
                }
            });
        });
        
        // Add styling toggle for children
        const childCheckboxes = document.querySelectorAll('.child-checkbox');
        childCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const label = this.closest('.child-label');
                const qtyInput = label.querySelector('.qty-input');
                
                if (this.checked) {
                    label.classList.add('bg-orange-100', 'border-orange-300');
                    label.classList.remove('bg-white', 'border-gray-100');
                    if(qtyInput) qtyInput.disabled = false;
                } else {
                    label.classList.remove('bg-orange-100', 'border-orange-300');
                    label.classList.add('bg-white', 'border-gray-100');
                    if(qtyInput) qtyInput.disabled = true;
                }
            });
        });

        // Script untuk Tambah Obat Dinamis
        const btnTambahObat = document.getElementById('btn-tambah-obat');
        const obatContainer = document.getElementById('obat-container');
        const obatTemplate = document.getElementById('obat-template');

        btnTambahObat.addEventListener('click', function() {
            // Clone template
            const newRow = obatTemplate.cloneNode(true);
            newRow.id = ''; // Remove id
            newRow.classList.remove('hidden'); // Show it
            
            // Enable inputs
            const inputs = newRow.querySelectorAll('select, input');
            inputs.forEach(input => {
                input.disabled = false;
                if(input.tagName === 'SELECT') {
                    input.required = true;
                }
            });

            // Add delete event
            const btnHapus = newRow.querySelector('.btn-hapus-obat');
            btnHapus.addEventListener('click', function() {
                newRow.remove();
            });

            obatContainer.appendChild(newRow);
        });
    });
</script>

@endsection
