<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Pasien;

class PendaftaranController extends Controller
{
    // menampilkan semua data pendaftaran
    public function index()
    {
        $pendaftaran = Pendaftaran::with('pasien')->get();
        return view('pendaftaran.index', compact('pendaftaran'));
    }

    // menampilkan form pendaftaran
    public function create()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if ($user->role === 'pasien') {
            // Cek profil
            $pasien = \App\Models\Pasien::where('id_user', $user->id)->first();
            if (!$pasien) {
                return redirect()->route('pasien.complete_profile');
            }
        }

        $pasienList = \App\Models\Pasien::all();
        $layanan = \App\Models\Layanan::all();

        return view('pendaftaran.create', compact('pasienList', 'layanan', 'user'));
    }

    // menyimpan data pendaftaran
    public function store(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $id_pasien = $request->id_pasien;

        if ($user->role === 'pasien') {
            $pasien = \App\Models\Pasien::where('id_user', $user->id)->first();
            $id_pasien = $pasien->id_pasien;
        }

        $request->validate([
            'id_layanan' => 'required',
            'tanggal_kunjungan' => 'required|date',
        ]);

        $pendaftaran = Pendaftaran::create([
            'id_pasien' => $id_pasien,
            'id_layanan' => $request->id_layanan,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'status' => 'menunggu'
        ]);

        $antrianKe = \App\Models\Antrian::where('tanggal_antrian', $request->tanggal_kunjungan)->count() + 1;
        
        $antrian = \App\Models\Antrian::create([
            'id_pendaftaran' => $pendaftaran->id_pendaftaran,
            'nomor_antrian' => $antrianKe,
            'tanggal_antrian' => $request->tanggal_kunjungan,
            'status' => 'menunggu'
        ]);

        if ($user->role === 'pasien') {
            return redirect()->route('pendaftaran.cetak', $antrian->id_antrian)
                ->with('success', 'Antrian berhasil diambil!');
        }

        return redirect()->route('pendaftaran.index')
            ->with('success','Pendaftaran berhasil dibuat');
    }

    // mencetak antrian
    public function cetak($id)
    {
        $antrian = \App\Models\Antrian::with(['pendaftaran.pasien', 'pendaftaran.layanan'])->findOrFail($id);
        
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'pasien') {
            $pasien = \App\Models\Pasien::where('id_user', $user->id)->first();
            if (!$pasien || $antrian->pendaftaran->id_pasien !== $pasien->id_pasien) {
                abort(403, 'Unauthorized access.');
            }
        }

        return view('pendaftaran.cetak', compact('antrian'));
    }

    // menampilkan form edit
    public function edit($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pasienList = \App\Models\Pasien::all();
        $layanan = \App\Models\Layanan::all();

        return view('pendaftaran.edit', compact('pendaftaran','pasienList','layanan'));
    }

    // update data pendaftaran
    public function update(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        // Mendukung update inline dari tabel (status saja) maupun form edit lengkap.
        if ($request->has('status') && !$request->has('id_pasien') && !$request->has('id_layanan') && !$request->has('tanggal_kunjungan')) {
            $request->validate([
                'status' => 'required|in:menunggu,dipanggil,diproses,diperiksa,selesai,batal',
            ]);

            $pendaftaran->update([
                'status' => strtolower((string) $request->status),
            ]);
        } else {
            $request->validate([
                'id_pasien' => 'required|exists:pasien,id_pasien',
                'id_layanan' => 'required|exists:layanan,id_layanan',
                'tanggal_kunjungan' => 'required|date',
                'status' => 'required|in:menunggu,dipanggil,diproses,diperiksa,selesai,batal',
            ]);

            $pendaftaran->update([
                'id_pasien' => $request->id_pasien,
                'id_layanan' => $request->id_layanan,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'status' => strtolower((string) $request->status),
            ]);
        }

        return redirect()->route('pendaftaran.index')
            ->with('success','Data pendaftaran berhasil diupdate');
    }

    // hapus pendaftaran
    public function destroy($id)
    {
        Pendaftaran::destroy($id);

        return redirect()->route('pendaftaran.index')
            ->with('success','Data pendaftaran berhasil dihapus');
    }
}
