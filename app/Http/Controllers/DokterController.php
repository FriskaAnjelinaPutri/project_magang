<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;

class DokterController extends Controller
{
    /**
     * Tampilkan dashboard dokter dengan tabel antrian hari ini.
     */
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());

        $antrian = Antrian::with('pendaftaran.pasien', 'pendaftaran.layanan')
            ->whereDate('tanggal_antrian', $tanggal)
            ->orderBy('nomor_antrian', 'asc')
            ->get();

        $stats = [
            'menunggu'  => $antrian->where('status', 'menunggu')->count()
                         + $antrian->where('status', 'dilewati')->count(),
            'dipanggil' => $antrian->where('status', 'dipanggil')->count(),
            'selesai'   => $antrian->where('status', 'selesai')->count(),
            'total'     => $antrian->count(),
        ];

        return view('dokter.dashboard', compact('antrian', 'tanggal', 'stats'));
    }
}
