<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RekamMedis;

class RekamMedisController extends Controller
{
    public function index()
    {
        $rekamMedis = RekamMedis::with(['pasien', 'pendaftaran.layanan'])->latest()->get();
        // Determine layout based on user role (Admin or Dokter)
        $layout = auth()->user()->role === 'dokter' ? 'layouts.dokter' : 'layouts.admin';
        return view('rekam_medis.index', compact('rekamMedis', 'layout'));
    }

    public function create(Request $request)
    {
        $idPendaftaran = $request->query('id_pendaftaran');
        // Fetch pendaftaran data if provided to pre-fill the form
        $pendaftaran = null;
        if ($idPendaftaran) {
            $pendaftaran = \App\Models\Pendaftaran::with('pasien', 'layanan')->find($idPendaftaran);
        }
        
        $layout = auth()->user()->role === 'dokter' ? 'layouts.dokter' : 'layouts.admin';
        return view('rekam_medis.create', compact('pendaftaran', 'layout'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pasien' => 'required|exists:pasien,id_pasien',
            'id_pendaftaran' => 'nullable|exists:pendaftaran,id_pendaftaran',
            'keluhan' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'biaya_tindakan' => 'nullable|numeric|min:0',
            'resep_obat' => 'nullable|string',
            'biaya_obat' => 'nullable|numeric|min:0',
            'tanggal_periksa' => 'required|date',
        ]);

        $rekamMedis = RekamMedis::create($request->all());

        if ($request->id_pendaftaran) {
            $pembayaran = \App\Models\Pembayaran::where('id_pendaftaran', $request->id_pendaftaran)->first();
            if ($pembayaran) {
                $pembayaran->total_bayar += ($request->biaya_tindakan ?? 0) + ($request->biaya_obat ?? 0);
                $pembayaran->save();
            }
        }

        return redirect()->route('dashboard.dokter')->with('success', 'Rekam medis berhasil disimpan.');
    }

    public function show($id)
    {
        $rekamMedis = RekamMedis::with(['pasien', 'pendaftaran'])->findOrFail($id);
        return response()->json($rekamMedis);
    }

    public function update(Request $request, $id)
    {
        $rekamMedis = RekamMedis::findOrFail($id);
        
        $oldBiayaTindakan = $rekamMedis->biaya_tindakan ?? 0;
        $oldBiayaObat = $rekamMedis->biaya_obat ?? 0;

        $rekamMedis->update($request->all());

        if ($rekamMedis->id_pendaftaran) {
            $pembayaran = \App\Models\Pembayaran::where('id_pendaftaran', $rekamMedis->id_pendaftaran)->first();
            if ($pembayaran) {
                $pembayaran->total_bayar -= ($oldBiayaTindakan + $oldBiayaObat);
                $pembayaran->total_bayar += ($rekamMedis->biaya_tindakan ?? 0) + ($rekamMedis->biaya_obat ?? 0);
                $pembayaran->save();
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

    public function cetak()
    {
        $rekamMedis = RekamMedis::with(['pasien', 'pendaftaran.layanan'])
            ->orderBy('tanggal_periksa', 'asc')
            ->get();
            
        return view('rekam_medis.cetak', compact('rekamMedis'));
    }
}
