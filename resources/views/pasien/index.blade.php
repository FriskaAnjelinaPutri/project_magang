@extends('layouts.admin')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 px-2 mt-4 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Data Pasien</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar semua pasien yang terdaftar di sistem.</p>
    </div>
    <a href="{{ route('pasien.create') }}" class="btn-gradient font-bold py-2.5 px-6 rounded-full shadow-lg transition-all text-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Pasien
    </a>
</div>

<div class="px-2">
    <!-- Form Pencarian -->
    <div class="glass-panel rounded-2xl p-4 mb-5">
        <form method="GET" action="{{ route('pasien.index') }}" class="flex flex-col sm:flex-row items-end gap-3 w-full">
            <div class="flex-1 w-full sm:w-auto">
                <label for="search" class="block text-sm font-bold text-gray-700 mb-1">Cari Nama Pasien</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input id="search" name="search" type="text" value="{{ $search ?? '' }}" placeholder="Masukkan nama pasien..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all text-gray-800 font-semibold">
                </div>
            </div>
            <div class="flex gap-2 w-full sm:w-auto mt-3 sm:mt-0">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold transition-colors w-full sm:w-auto text-center">
                    Cari
                </button>
                @if(isset($search) && $search != '')
                    <a href="{{ route('pasien.index') }}" class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition-colors w-full sm:w-auto text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="glass-panel rounded-3xl p-6 sm:p-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200/50">
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">No</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">L/P</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">NIK</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Lahir</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">No HP</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Alamat</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pasien ?? [] as $row)
                        <tr class="border-b border-gray-100/50 hover:bg-white/40 transition-colors">
                            <td class="py-4 px-4 text-sm font-bold text-orange-500 text-center">{{ $loop->iteration }}</td>
                            <td class="py-4 px-4 text-sm text-gray-900 font-semibold">{{ $row->nama_pasien }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600 font-medium">{{ $row->jenis_kelamin }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600">{{ $row->nik ?? '-' }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600 whitespace-nowrap">{{ $row->tanggal_lahir ? \Carbon\Carbon::parse($row->tanggal_lahir)->translatedFormat('d M Y') : '-' }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600">{{ $row->no_hp }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600 max-w-xs truncate">{{ $row->alamat }}</td>
                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('pasien.edit', $row->id_pasien ?? $row->id) }}" class="text-orange-500 hover:text-orange-700 text-sm font-bold mr-3 transition-colors">Edit</a>
                                <form action="{{ route('pasien.destroy', $row->id_pasien ?? $row->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold transition-colors" onclick="return confirm('Hapus data ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-sm font-medium text-gray-500">Belum ada data pasien.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
