<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;

class PembayaranController extends Controller
{
    // menampilkan semua pembayaran
    public function index()
    {
        $pembayaran = Pembayaran::with('pendaftaran')->get();

        return view('pembayaran.index', compact('pembayaran'));
    }

    // menampilkan form pembayaran
    public function create()
    {
        $pendaftaran = Pendaftaran::with(['layanan', 'pasien'])
            ->orderByDesc('id_pendaftaran')
            ->get();

        return view('pembayaran.create', compact('pendaftaran'));
    }

    // menyimpan pembayaran
    public function store(Request $request)
    {
        $request->validate([
            'id_pendaftaran' => 'required|exists:pendaftaran,id_pendaftaran',
            'total_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer',
            'bukti_transfer' => 'nullable|image|max:2048',
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
        $pembayaran = Pembayaran::with('pendaftaran.layanan')->findOrFail($id);

        return view('pembayaran.edit', compact('pembayaran'));
    }

    // update pembayaran — hanya status (dari tabel atau halaman edit ringkas)
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:lunas,belum lunas',
        ]);

        $pembayaran = Pembayaran::with('pendaftaran.layanan')->findOrFail($id);

        $hargaLayanan = (float) (optional($pembayaran->pendaftaran->layanan)->harga ?? 0);
        $pembayaran->status = $request->status;
        $totalDiselaraskan = false;

        if ($request->status === 'lunas') {
            if ($hargaLayanan > 0) {
                $pembayaran->total_bayar = $hargaLayanan;
                $totalDiselaraskan = true;
            }
            if (empty($pembayaran->tanggal_pembayaran)) {
                $pembayaran->tanggal_pembayaran = now();
            }
        }

        $pembayaran->save();

        $msg = $request->status === 'lunas' && $totalDiselaraskan
            ? 'Status lunas. Total bayar disesuaikan dengan harga layanan.'
            : 'Status pembayaran berhasil diperbarui.';

        return redirect()->route('pembayaran.index')
            ->with('success', $msg);
    }

    // hapus pembayaran
    public function destroy($id)
    {
        Pembayaran::destroy($id);

        return redirect()->route('pembayaran.index')
            ->with('success','Data pembayaran berhasil dihapus');
    }
}
