@extends('layouts.admin')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 px-2 mt-4 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Antrian</h1>
        <p class="text-sm text-gray-500 mt-1">Monitor dan kelola pemanggilan pasien hari ini.</p>
    </div>
</div>

<div class="px-2">
    <div class="glass-panel rounded-2xl p-4 mb-5">
        <form method="GET" action="{{ route('antrian.index') }}" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="w-full sm:w-auto">
                <label for="tanggal" class="block text-sm font-bold text-gray-700 mb-1">Filter Tanggal</label>
                <input id="tanggal" name="tanggal" type="date" value="{{ $tanggal ?? now()->toDateString() }}"
                    class="w-full sm:w-56 px-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all text-gray-800 font-semibold">
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold transition-colors">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Peringatan Sukses -->
    @if (session('success'))
    <div class="mb-6 p-4 bg-green-50/80 border border-green-200 text-green-700 rounded-2xl flex items-center shadow-sm">
        <i class="fa-solid fa-circle-check text-xl mr-3"></i>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif
    @if (session('error'))
    <div class="mb-6 p-4 bg-red-50/80 border border-red-200 text-red-700 rounded-2xl flex items-center shadow-sm">
        <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
        <span class="font-bold">{{ session('error') }}</span>
    </div>
    @endif

    <div class="glass-panel rounded-3xl p-4 sm:p-6">
        <div class="overflow-x-auto rounded-2xl border border-gray-200/70 bg-white/70">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/90">
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center border-b border-gray-200/80">No</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Informasi Pendaftaran</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Tanggal</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center border-b border-gray-200/80">Status</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center border-b border-gray-200/80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($antrian ?? [] as $row)
                        @php
                            $statusAntrian = strtolower(trim((string) $row->status));
                        @endphp
                        <tr class="odd:bg-white even:bg-gray-50/50 hover:bg-orange-50/40 transition-colors">
                            <td class="py-4 px-4 text-2xl font-black text-orange-500 text-center border-b border-gray-100/80">
                                {{ $row->nomor_antrian > 0 ? (int) $row->nomor_antrian : '-' }}
                            </td>
                            <td class="py-4 px-4 border-b border-gray-100/80">
                                <div class="text-sm text-gray-900 font-bold mb-1">
                                    {{ $row->pendaftaran->pasien->nama_pasien ?? 'Unknown' }}
                                </div>
                                <div class="text-xs text-gray-500 font-medium">
                                    {{ $row->pendaftaran->layanan->nama_layanan ?? '-' }}
                                </div>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-600 font-medium whitespace-nowrap border-b border-gray-100/80">
                                {{ \Carbon\Carbon::parse($row->tanggal_antrian)->format('d M Y') }}
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap border-b border-gray-100/80">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold capitalize
                                        {{ $statusAntrian === 'belum_datang' ? 'bg-red-100 text-red-800 border border-red-200' : '' }}
                                        {{ $statusAntrian === 'menunggu' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}
                                        {{ $statusAntrian === 'dipanggil' ? 'bg-blue-100 text-blue-700 border border-blue-200 animate-pulse' : '' }}
                                        {{ $statusAntrian === 'selesai' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}
                                        {{ $statusAntrian === 'dilewati' ? 'bg-gray-100 text-gray-600 border border-gray-200' : '' }}
                                        {{ !in_array($statusAntrian, ['belum_datang','menunggu','dipanggil','selesai','dilewati']) ? 'bg-gray-100 text-gray-600' : '' }}
                                    ">
                                        @if($statusAntrian === 'belum_datang')
                                            <i class="fa-solid fa-user-clock mr-1.5"></i>
                                        @elseif($statusAntrian === 'menunggu')
                                            <i class="fa-solid fa-clock mr-1.5"></i>
                                        @elseif($statusAntrian === 'dipanggil')
                                            <i class="fa-solid fa-microphone mr-1.5"></i>
                                        @elseif($statusAntrian === 'selesai')
                                            <i class="fa-solid fa-check mr-1.5"></i>
                                        @elseif($statusAntrian === 'dilewati')
                                            <i class="fa-solid fa-forward-step mr-1.5"></i>
                                        @endif
                                        {{ str_replace('_', ' ', $row->status) }}
                                    </span>


                                </div>
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap border-b border-gray-100/80">
                                <div class="flex items-center justify-center gap-2">
                                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'dokter')
                                        {{-- Tombol Hadir: tampil jika status belum_datang --}}
                                        @if($statusAntrian === 'belum_datang')
                                            <form action="{{ route('antrian.hadir', $row->id_antrian) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="tanggal" value="{{ $tanggal ?? now()->toDateString() }}">
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5" title="Tandai pasien sudah hadir">
                                                    <i class="fa-solid fa-clipboard-user mr-1.5"></i> Lapor Hadir
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tombol Panggil: tampil jika status menunggu atau dilewati --}}
                                        @if(in_array($statusAntrian, ['menunggu', 'dilewati']))
                                            <form action="{{ route('antrian.panggil', $row->id_antrian) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="tanggal" value="{{ $tanggal ?? now()->toDateString() }}">
                                                @if($statusAntrian === 'dilewati')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5" title="Panggil ulang pasien ini">
                                                        <i class="fa-solid fa-bullhorn mr-1.5"></i> Panggil Ulang
                                                    </button>
                                                @else
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5" title="Panggil pasien ini">
                                                        <i class="fa-solid fa-bullhorn mr-1.5"></i> Panggil
                                                    </button>
                                                @endif
                                            </form>
                                        @endif

                                        {{-- Tombol Selesai & Lewati: tampil jika status dipanggil --}}
                                        @if($statusAntrian === 'dipanggil')
                                            <form action="{{ route('antrian.selesai', $row->id_antrian) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="tanggal" value="{{ $tanggal ?? now()->toDateString() }}">
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-green-500 hover:bg-green-600 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5" title="Selesaikan antrian ini">
                                                    <i class="fa-solid fa-check-double mr-1.5"></i> Selesai
                                                </button>
                                            </form>
                                            <form action="{{ route('antrian.lewati', $row->id_antrian) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="tanggal" value="{{ $tanggal ?? now()->toDateString() }}">
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-400 hover:bg-gray-500 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5" title="Lewati antrian ini">
                                                    <i class="fa-solid fa-forward-step mr-1.5"></i> Lewati
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Jika selesai, tampilkan tanda check --}}
                                        @if($statusAntrian === 'selesai')
                                            <span class="text-green-500 text-sm font-bold"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 flex-col flex items-center justify-center text-center text-gray-500 w-full mt-4">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-3">
                                    <i class="fa-solid fa-clipboard-list text-2xl"></i>
                                </div>
                                <p class="text-base font-bold text-gray-700">Tidak Ada Antrian Aktif</p>
                                <p class="text-sm mt-1">Belum ada pasien yang masuk antrian hari ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
