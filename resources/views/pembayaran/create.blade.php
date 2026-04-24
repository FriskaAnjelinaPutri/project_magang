@extends(auth()->check() && auth()->user()->role === 'kasir' ? 'layouts.kasir' : 'layouts.admin')

@section('content')
<div class="flex items-center mb-8 px-2 mt-4 gap-4">
    <a href="{{ route('pembayaran.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-orange-500 hover:shadow-md transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Catat Pembayaran Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Masukkan rincian setoran biaya dari pasien yang telah dilayani.</p>
    </div>
</div>

<div class="px-2">
    <div class="glass-panel rounded-3xl p-8 max-w-4xl">
        <form method="GET" action="{{ route('pembayaran.create') }}" class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-end gap-3">
                <div>
                    <label for="tanggal_filter" class="block text-sm font-bold text-gray-700 mb-2">Filter Tanggal Kunjungan</label>
                    <input id="tanggal_filter" type="date" name="tanggal" value="{{ $tanggalFilter ?? now()->toDateString() }}"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all text-gray-800 font-semibold">
                </div>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold transition-colors">
                    Tampilkan Daftar
                </button>
            </div>
        </form>

        <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ID Pendaftaran -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pendaftaran &amp; Layanan</label>
                    <select id="id_pendaftaran" name="id_pendaftaran" required class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all font-semibold text-gray-800 bg-white cursor-pointer">
                        <option value="" disabled {{ old('id_pendaftaran') ? '' : 'selected' }} data-harga="0">-- Pilih nama pasien &amp; layanan --</option>
                        @foreach($pendaftaran ?? [] as $daftar)
                            @php
                                $harga = (float) (optional($daftar->layanan)->harga ?? 0);
                            @endphp
                            <option value="{{ $daftar->id_pendaftaran ?? $daftar->id }}"
                                data-harga="{{ $harga }}"
                                {{ (string) old('id_pendaftaran') === (string) ($daftar->id_pendaftaran ?? $daftar->id) ? 'selected' : '' }}>
                                {{ optional($daftar->pasien)->nama_pasien ?? 'Pasien' }} — {{ optional($daftar->layanan)->nama_layanan ?? 'Layanan' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_pendaftaran') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                    @if(($pendaftaran ?? collect())->isEmpty())
                        <p class="text-xs text-amber-700 mt-2 font-semibold bg-amber-50 border border-amber-100 rounded-xl px-3 py-2">
                            Tidak ada pasien yang perlu pembayaran pada tanggal ini.
                        </p>
                    @endif
                    <p id="harga-layanan-hint" class="text-xs font-semibold text-orange-600 mt-2 hidden"></p>
                </div>

                <!-- Total Bayar -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah yang dibayar (Rp)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <span class="text-orange-500 font-bold">Rp</span>
                        </div>
                        <input type="number" id="total_bayar" name="total_bayar" required step="1" min="0"
                            class="w-full pl-12 pr-5 py-3.5 rounded-2xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all font-semibold text-gray-800"
                            placeholder="0" value="{{ old('total_bayar') }}">
                    </div>
                    <p class="text-xs text-gray-500 font-medium mt-1.5">
                        <strong>Cash &amp; transfer sama:</strong> tulis berapa rupiah yang dibayar pada transaksi ini 
                    </p>
                    <p id="hint-transfer-total" class="text-xs text-orange-800 font-semibold mt-2 hidden bg-orange-50 border border-orange-100 rounded-xl px-3 py-2">
                        <strong>Transfer:</strong> isi nominal yang sama dengan yang tertera di bukti transfer
                    </p>
                    @error('total_bayar') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Metode Pembayaran -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Metode Setoran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" required class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all font-semibold text-gray-800 bg-white">
                        <option value="cash" {{ old('metode_pembayaran', 'cash') == 'cash' ? 'selected' : '' }}>Cash Tunai</option>
                        <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    </select>
                    @error('metode_pembayaran') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Bukti Transfer (Opsional unless Transfer) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti Transfer (Opsional jika Cash)</label>
                    <div id="dropzone-bukti" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl hover:border-orange-500 transition-colors bg-gray-50">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex flex-col text-sm text-gray-600 items-center justify-center mt-2">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 px-3 py-1 shadow-sm border border-orange-200">
                                    <span>Pilih File Gambar</span>
                                    <input id="file-upload" name="bukti_transfer" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1 mt-2 font-medium">Atau seret dan lepas gambar ke area ini</p>
                                <p id="file-upload-name" class="text-xs font-semibold text-green-700 mt-2 hidden"></p>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">PNG, JPG hingga 2MB (disarankan untuk transfer)</p>
                        </div>
                    </div>
                    @error('bukti_transfer') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="pt-8 border-t border-gray-100 flex justify-end gap-3 mt-8">
                <button type="reset" class="px-6 py-3 rounded-full text-gray-500 font-bold hover:bg-gray-50 transition-colors">Reset</button>
                <button type="submit" class="btn-gradient px-8 py-3 rounded-full font-bold shadow-lg shadow-orange-500/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Validasi Pembayaran
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var sel = document.getElementById('id_pendaftaran');
    var total = document.getElementById('total_bayar');
    var hint = document.getElementById('harga-layanan-hint');
    var metode = document.getElementById('metode_pembayaran');
    var hintTransfer = document.getElementById('hint-transfer-total');
    var fileInput = document.getElementById('file-upload');
    var fileName = document.getElementById('file-upload-name');
    var dropzone = document.getElementById('dropzone-bukti');
    if (!sel || !total || !hint) return;

    function formatRp(n) {
        return new Intl.NumberFormat('id-ID').format(n);
    }

    function toggleTransferHint() {
        if (!metode || !hintTransfer) return;
        if (metode.value === 'transfer') {
            hintTransfer.classList.remove('hidden');
            if (fileInput) {
                fileInput.required = true;
            }
        } else {
            hintTransfer.classList.add('hidden');
            if (fileInput) {
                fileInput.required = false;
            }
        }
    }

    function showSelectedFile() {
        if (!fileInput || !fileName) return;
        if (fileInput.files && fileInput.files[0]) {
            fileName.textContent = 'File terpilih: ' + fileInput.files[0].name;
            fileName.classList.remove('hidden');
        } else {
            fileName.textContent = '';
            fileName.classList.add('hidden');
        }
    }

    function applyHarga() {
        var opt = sel.options[sel.selectedIndex];
        if (!opt || !opt.value) {
            hint.classList.add('hidden');
            return;
        }
        var harga = parseFloat(opt.getAttribute('data-harga') || '0');
        if (harga > 0) {
            total.value = Math.floor(harga);
            hint.textContent = 'Harga layanan untuk pendaftaran ini: Rp ' + formatRp(harga) + '. Anda bisa menyesuaikan jika bayar sebagian.';
            hint.classList.remove('hidden');
        } else {
            hint.textContent = 'Harga layanan belum terdeteksi. Isi jumlah bayar secara manual.';
            hint.classList.remove('hidden');
        }
    }

    sel.addEventListener('change', applyHarga);
    if (metode) {
        metode.addEventListener('change', toggleTransferHint);
        toggleTransferHint();
    }

    if (fileInput) {
        fileInput.addEventListener('change', showSelectedFile);
    }

    if (dropzone && fileInput) {
        ['dragenter', 'dragover'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-orange-500', 'bg-orange-50');
            });
        });

        ['dragleave', 'drop'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-orange-500', 'bg-orange-50');
            });
        });

        dropzone.addEventListener('drop', function (e) {
            var files = e.dataTransfer ? e.dataTransfer.files : null;
            if (files && files.length > 0) {
                fileInput.files = files;
                showSelectedFile();
            }
        });
    }

    showSelectedFile();
    // Isi otomatis hanya jika kolom jumlah masih kosong (hindari timpa nilai saat validasi gagal / old input)
    if (sel.value && (total.value === '' || total.value === '0')) {
        applyHarga();
    }
})();
</script>
@endpush
