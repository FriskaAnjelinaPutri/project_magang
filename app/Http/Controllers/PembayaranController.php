<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;

class PembayaranController extends Controller
{
    // menampilkan semua pembayaran
    public function index(Request $request)
    {
        $tanggalFilter = $request->input('tanggal', now()->toDateString());

        $pembayaran = Pembayaran::with('pendaftaran.pasien', 'pendaftaran.antrian')
            ->whereDate('tanggal_pembayaran', $tanggalFilter)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('pembayaran.index', compact('pembayaran', 'tanggalFilter'));
    }

    // menampilkan form pembayaran
    public function create(Request $request)
    {
        $tanggalFilter = $request->input('tanggal', now()->toDateString());

        $pendaftaran = Pendaftaran::with(['layanan', 'pasien', 'antrian'])
            ->select('pendaftaran.*')
            ->leftJoin('antrian', 'pendaftaran.id_pendaftaran', '=', 'antrian.id_pendaftaran')
            ->whereDate('pendaftaran.tanggal_kunjungan', $tanggalFilter)
            ->whereIn('pendaftaran.status', ['menunggu', 'dipanggil', 'selesai'])
            ->whereDoesntHave('pembayaran', function ($query) {
                $query->where('status', 'lunas');
            })
            ->orderBy('antrian.updated_at', 'asc')
            ->get();

        return view('pembayaran.create', compact('pendaftaran', 'tanggalFilter'));
    }

    // menyimpan pembayaran
    public function store(Request $request)
    {
        $request->validate([
            'id_pendaftaran' => 'required|exists:pendaftaran,id_pendaftaran',
            'total_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer',
            'bukti_transfer' => 'required_if:metode_pembayaran,transfer|nullable|image|max:2048',
        ]);

        $pendaftaran = Pendaftaran::with('layanan')->findOrFail($request->id_pendaftaran);
        $hargaLayanan = (float) ($pendaftaran->layanan->harga ?? 0);
        $totalBayar = (float) $request->total_bayar;

        // Lunas hanya jika jumlah yang dibayar >= harga layanan (bukan dari metode cash/transfer)
        $status = $totalBayar >= $hargaLayanan ? 'lunas' : 'belum lunas';

        $bukti = null;
        if ($request->hasFile('bukti_transfer')) {
            $bukti = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
        }

        Pembayaran::create([
            'id_pendaftaran' => $request->id_pendaftaran,
            'total_bayar' => $totalBayar,
            'tanggal_pembayaran' => now(),
            'status' => $status,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_transfer' => $bukti,
        ]);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil disimpan.');
    }

    // edit pembayaran
    public function edit($id)
    {
        $pembayaran = Pembayaran::with('pendaftaran.layanan', 'pendaftaran.antrian', 'pendaftaran.pasien')->findOrFail($id);

        return view('pembayaran.edit', compact('pembayaran'));
    }

    // update pembayaran — hanya status (dari tabel atau halaman edit ringkas)
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:lunas,belum lunas',
            'total_bayar' => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'nullable|in:cash,transfer',
        ]);

        $pembayaran = Pembayaran::with('pendaftaran.layanan')->findOrFail($id);

        $pembayaran->status = $request->status;
        
        if ($request->has('metode_pembayaran') && $request->metode_pembayaran) {
            $pembayaran->metode_pembayaran = $request->metode_pembayaran;
        }

        if ($request->has('total_bayar') && $request->total_bayar !== null) {
            $pembayaran->total_bayar = $request->total_bayar;
        } else if ($request->status === 'lunas' && $pembayaran->total_bayar == 0) {
            $hargaLayanan = (float) (optional($pembayaran->pendaftaran->layanan)->harga ?? 0);
            if ($hargaLayanan > 0) {
                $pembayaran->total_bayar = $hargaLayanan;
            }
        }

        if ($request->status === 'lunas') {
            if (empty($pembayaran->tanggal_pembayaran)) {
                $pembayaran->tanggal_pembayaran = now();
            }
        }

        $pembayaran->save();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Status dan nominal pembayaran berhasil diperbarui.');
    }

    // menampilkan detail pembayaran
    public function show($id)
    {
        $pembayaran = Pembayaran::with('pendaftaran.layanan', 'pendaftaran.antrian', 'pendaftaran.pasien')->findOrFail($id);
        return view('pembayaran.show', compact('pembayaran'));
    }

    // hapus pembayaran
    public function destroy($id)
    {
        Pembayaran::destroy($id);

        return redirect()->route('pembayaran.index')
            ->with('success','Data pembayaran berhasil dihapus');
    }
}
