<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use App\Models\Pendaftaran;

class AntrianController extends Controller
{
    // menampilkan semua antrian
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $antrian = Antrian::with('pendaftaran.pasien', 'pendaftaran.layanan')
            ->whereDate('tanggal_antrian', $tanggal)
            ->orderBy('nomor_antrian')
            ->get();

        return view('antrian.index', compact('antrian', 'tanggal'));
    }

    // membuat nomor antrian
    public function store(Request $request)
    {
        // menghitung jumlah antrian hari ini
        $nomor = Antrian::whereDate('tanggal_antrian', today())->count() + 1;

        Antrian::create([
            'id_pendaftaran' => $request->id_pendaftaran,
            'nomor_antrian' => $nomor,
            'tanggal_antrian' => today(),
            'status' => 'menunggu'
        ]);

        return redirect()->route('antrian.index')
            ->with('success','Nomor antrian berhasil dibuat');
    }

    // memanggil pasien
    public function panggil(Request $request, $id)
    {
        $antrian = Antrian::with('pendaftaran')->findOrFail($id);

        $antrian->update([
            'status' => 'dipanggil'
        ]);
        if ($antrian->pendaftaran) {
            $antrian->pendaftaran->update([
                'status' => 'dipanggil',
            ]);
        }

        return redirect()->route('antrian.index', [
            'tanggal' => $request->input('tanggal', $antrian->tanggal_antrian),
        ]);
    }

    // menyelesaikan antrian
    public function selesai(Request $request, $id)
    {
        $antrian = Antrian::with('pendaftaran')->findOrFail($id);

        $antrian->update([
            'status' => 'selesai'
        ]);
        if ($antrian->pendaftaran) {
            $antrian->pendaftaran->update([
                'status' => 'selesai',
            ]);
        }

        return redirect()->route('antrian.index', [
            'tanggal' => $request->input('tanggal', $antrian->tanggal_antrian),
        ]);
    }

    // menghapus antrian
    public function destroy($id)
    {
        Antrian::destroy($id);

        return redirect()->route('antrian.index');
    }
}
