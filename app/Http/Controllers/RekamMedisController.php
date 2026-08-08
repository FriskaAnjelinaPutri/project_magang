<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RekamMedis;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $tanggalFilter = $request->query('tanggal', now()->toDateString());
        
        $rekamMedis = RekamMedis::with(['pasien', 'pendaftaran.layanans'])
            ->whereDate('tanggal_periksa', $tanggalFilter)
            ->latest()
            ->get();
            
        // Determine layout based on user role (Admin or Dokter)
        $layout = auth()->user()->role === 'dokter' ? 'layouts.dokter' : 'layouts.admin';
        return view('rekam_medis.index', compact('rekamMedis', 'layout', 'tanggalFilter'));
    }

    public function create(Request $request)
    {
        $idPendaftaran = $request->query('id_pendaftaran');
        // Fetch pendaftaran data if provided to pre-fill the form
        $pendaftaran = null;
        if ($idPendaftaran) {
            $pendaftaran = \App\Models\Pendaftaran::with('pasien', 'layanans')->find($idPendaftaran);
        }
        
        $layanan = \App\Models\Layanan::all();
        $layout = auth()->user()->role === 'dokter' ? 'layouts.dokter' : 'layouts.admin';
        return view('rekam_medis.create', compact('pendaftaran', 'layout', 'layanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pasien' => 'required|exists:pasien,id_pasien',
            'id_pendaftaran' => 'nullable|exists:pendaftaran,id_pendaftaran',
            'id_layanan' => 'required|array|min:1',
            'id_layanan.*' => 'required|exists:layanan,id_layanan',
            'keluhan' => 'required|string',
            'tindakan' => 'required|string',
            'resep_obat' => 'nullable|string',
            'tanggal_periksa' => 'required|date',
        ]);

        $rekamMedis = RekamMedis::create($request->except(['id_layanan', 'biaya_obat']));

        if ($request->id_pendaftaran) {
            $pendaftaran = \App\Models\Pendaftaran::find($request->id_pendaftaran);
            if ($pendaftaran) {
                // Update daftar layanan final pilihan dokter
                $pendaftaran->layanans()->sync(array_unique($request->id_layanan));

                // Buat tagihan pembayaran (jika belum ada)
                $pembayaran = \App\Models\Pembayaran::where('id_pendaftaran', $request->id_pendaftaran)->first();
                if ($pembayaran) {
                    $pembayaran->total_bayar = $pendaftaran->layanans()->sum('harga');
                    $pembayaran->save();
                } else {
                    \App\Models\Pembayaran::create([
                        'id_pendaftaran' => $request->id_pendaftaran,
                        'total_bayar' => $pendaftaran->layanans()->sum('harga'),
                        'tanggal_pembayaran' => date('Y-m-d'),
                        'status' => 'belum lunas',
                        'metode_pembayaran' => 'cash',
                    ]);
                }

                // Otomatis selesaikan antrian pasien ini dan panggil pasien berikutnya
                $antrian = \App\Models\Antrian::where('id_pendaftaran', $request->id_pendaftaran)->first();
                if ($antrian && strtolower(trim((string)$antrian->status)) === 'dipanggil') {
                    $antrian->update(['status' => 'selesai']);
                    $pendaftaran->update(['status' => 'selesai']);
                    
                    // Cari dan panggil antrian berikutnya
                    $antrianBerikutnya = \App\Models\Antrian::with('pendaftaran')
                        ->whereDate('tanggal_antrian', $antrian->tanggal_antrian)
                        ->whereIn('status', ['menunggu', 'dilewati'])
                        ->orderBy('nomor_antrian', 'asc')
                        ->first();
                        
                    if ($antrianBerikutnya) {
                        $antrianBerikutnya->update(['status' => 'dipanggil']);
                        if ($antrianBerikutnya->pendaftaran) {
                            $antrianBerikutnya->pendaftaran->update(['status' => 'dipanggil']);
                        }
                    }
                }
            }
        }

        return redirect()->route('dashboard.dokter')->with('success', 'Rekam medis berhasil disimpan dan layanan final diperbarui.');
    }

    public function show($id)
    {
        $rekamMedis = RekamMedis::with(['pasien', 'pendaftaran'])->findOrFail($id);
        return response()->json($rekamMedis);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'keluhan' => 'required|string',
            'tindakan' => 'required|string',
            'resep_obat' => 'nullable|string',
            'tanggal_periksa' => 'required|date',
            'id_layanan' => 'sometimes|array|min:1',
            'id_layanan.*' => 'exists:layanan,id_layanan',
        ]);

        $rekamMedis = RekamMedis::findOrFail($id);
        
        $rekamMedis->update($request->except(['id_layanan', 'biaya_obat']));

        if ($rekamMedis->id_pendaftaran && $request->has('id_layanan')) {
            $pendaftaran = \App\Models\Pendaftaran::find($rekamMedis->id_pendaftaran);
            if ($pendaftaran) {
                $pendaftaran->layanans()->sync(array_unique($request->id_layanan));
                
                $pembayaran = \App\Models\Pembayaran::where('id_pendaftaran', $rekamMedis->id_pendaftaran)->first();
                if ($pembayaran) {
                    // Update total pembayaran sesuai dengan layanan saja (biaya obat diurus di kasir)
                    $pembayaran->total_bayar = $pendaftaran->layanans()->sum('harga');
                    $pembayaran->save();
                }
            }
        }
        
        
        return response()->json(['message' => 'Rekam medis berhasil diupdate']);
    }

    public function destroy($id)
    {
        $rekamMedis = RekamMedis::findOrFail($id);
        $rekamMedis->delete();
        
        return response()->json(['message' => 'Rekam medis berhasil dihapus']);
    }

    public function cetak(Request $request)
    {
        $tanggalFilter = $request->query('tanggal', now()->toDateString());
        
        $rekamMedis = RekamMedis::with(['pasien', 'pendaftaran.layanans'])
            ->whereDate('tanggal_periksa', $tanggalFilter)
            ->orderBy('tanggal_periksa', 'asc')
            ->get();
            
        return view('rekam_medis.cetak', compact('rekamMedis', 'tanggalFilter'));
    }
}
