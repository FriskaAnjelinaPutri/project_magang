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
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center border-b border-gray-200/80">No. Antrian</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Informasi Pendaftaran</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200/80">Tanggal</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right border-b border-gray-200/80">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($antrian ?? [] as $row)
                        @php
                            $statusAntrian = strtolower(trim((string) $row->status));
                        @endphp
                        <tr class="odd:bg-white even:bg-gray-50/50 hover:bg-orange-50/40 transition-colors">
                            <td class="py-4 px-4 text-2xl font-black text-orange-500 text-center border-b border-gray-100/80">
                                {{ (int) $row->nomor_antrian }}
                            </td>
                            <td class="py-4 px-4 border-b border-gray-100/80">
                                <div class="text-sm text-gray-900 font-bold mb-1">
                                    {{ $row->pendaftaran->pasien->nama_pasien ?? 'Unknown' }}
                                </div>
                                <div class="text-xs text-gray-500 font-medium">
                                    Poli: <span class="text-gray-700">{{ $row->pendaftaran->layanan->nama_layanan ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-600 font-medium whitespace-nowrap border-b border-gray-100/80">
                                {{ \Carbon\Carbon::parse($row->tanggal_antrian)->format('d M Y') }}
                            </td>
                            <td class="py-4 px-4 text-right whitespace-nowrap border-b border-gray-100/80">
                                <div class="flex items-center justify-end gap-2">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold capitalize
                                        {{ $statusAntrian === 'menunggu' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}
                                        {{ $statusAntrian === 'dipanggil' ? 'bg-blue-100 text-blue-700 border border-blue-200 animate-pulse' : '' }}
                                        {{ $statusAntrian === 'selesai' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}
                                        {{ $statusAntrian === 'dilewati' ? 'bg-gray-100 text-gray-600 border border-gray-200' : '' }}
                                        {{ !in_array($statusAntrian, ['menunggu','dipanggil','selesai','dilewati']) ? 'bg-gray-100 text-gray-600' : '' }}
                                    ">
                                        @if($statusAntrian === 'menunggu')
                                            <i class="fa-solid fa-clock mr-1.5"></i>
                                        @elseif($statusAntrian === 'dipanggil')
                                            <i class="fa-solid fa-microphone mr-1.5"></i>
                                        @elseif($statusAntrian === 'selesai')
                                            <i class="fa-solid fa-check mr-1.5"></i>
                                        @elseif($statusAntrian === 'dilewati')
                                            <i class="fa-solid fa-forward-step mr-1.5"></i>
                                        @endif
                                        {{ $row->status }}
                                    </span>

                                    @if($statusAntrian === 'menunggu' || $statusAntrian === 'selesai' || $statusAntrian === 'dilewati')
                                        <form action="{{ route('antrian.panggil', $row->id_antrian) }}" method="POST" class="inline m-0">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="tanggal" value="{{ $tanggal ?? now()->toDateString() }}">
                                            <button type="submit" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-bold transition-colors border border-blue-200">
                                                <i class="fa-solid fa-bullhorn"></i> Panggil
                                            </button>
                                        </form>
                                    @endif

                                    @if($statusAntrian === 'dipanggil')
                                        <form action="{{ route('antrian.lewati', $row->id_antrian) }}" method="POST" class="inline m-0">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="tanggal" value="{{ $tanggal ?? now()->toDateString() }}">
                                            <button type="submit" class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-lg text-sm font-bold transition-colors border border-yellow-100" onclick="return confirm('Pasien belum hadir. Lewati nomor ini dan panggil berikutnya?')">
                                                Lewati
                                            </button>
                                        </form>

                                        <form action="{{ route('antrian.selesai', $row->id_antrian) }}" method="POST" class="inline m-0">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="tanggal" value="{{ $tanggal ?? now()->toDateString() }}">
                                            <button type="submit" class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1.5 rounded-lg text-sm font-bold transition-colors border border-green-200">
                                                Selesai
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('antrian.destroy', $row->id_antrian) }}" method="POST" class="inline m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-sm font-bold transition-colors border border-red-100" onclick="return confirm('Apakah Anda yakin ingin menghapus antrian ini?')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 flex-col flex items-center justify-center text-center text-gray-500 w-full mt-4">
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
