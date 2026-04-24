@extends('layouts.admin')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 px-2 mt-4 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Data Layanan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola daftar layanan medis yang disediakan klinik.</p>
    </div>
    <a href="{{ route('layanan.create') }}" class="btn-gradient font-bold py-2.5 px-6 rounded-full shadow-lg transition-all text-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Layanan
    </a>
</div>

<div class="px-2">
    <div class="glass-panel rounded-3xl p-6 sm:p-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200/50">
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">No</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Layanan</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($layanan ?? [] as $row)
                        <tr class="border-b border-gray-100/50 hover:bg-white/40 transition-colors">
                            <td class="py-4 px-4 text-sm font-bold text-purple-600 text-center">{{ $loop->iteration }}</td>
                            <td class="py-4 px-4 text-sm text-gray-900 font-semibold">{{ $row->nama_layanan }}</td>
                            <td class="py-4 px-4 text-sm text-gray-700 font-medium">Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('layanan.edit', $row->id_layanan ?? $row->id) }}" class="text-orange-500 hover:text-orange-700 text-sm font-bold mr-3 transition-colors">Edit</a>
                                <form action="{{ route('layanan.destroy', $row->id_layanan ?? $row->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold transition-colors" onclick="return confirm('Hapus data ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-sm font-medium text-gray-500">Belum ada data layanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
