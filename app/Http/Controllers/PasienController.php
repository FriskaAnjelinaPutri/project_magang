<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pasien;
use App\Models\Pendaftaran;

class PasienController extends Controller
{

    // ==============================
    // DASHBOARD PASIEN
    // ==============================

    public function dashboard()
    {
        $user = Auth::user();

        // Ambil profil pasien sesuai user login
        $pasien = Pasien::where('id_user', $user->id)->first();
        if (!$pasien) {
            return redirect()->route('pasien.complete_profile');
        }

        // Riwayat pendaftaran pasien login + detail layanan & antrian
        $reservasi = Pendaftaran::with(['layanan', 'antrian'])
            ->where('id_pasien', $pasien->id_pasien)
            ->orderByDesc('tanggal_kunjungan')
            ->orderByDesc('created_at')
            ->get();

        // Statistik ringkas dashboard
        $totalReservasi = $reservasi->count();
        $antrianHariIni = $reservasi->filter(function ($item) {
            return optional($item->antrian)->tanggal_antrian === now()->toDateString();
        })->count();
        $riwayat = $reservasi->filter(function ($item) {
            return strtolower((string) $item->status) === 'selesai';
        })->count();

        return view('pasien.dashboard', compact(
            'pasien',
            'reservasi',
            'totalReservasi',
            'antrianHariIni',
            'riwayat'
        ));
    }


    // ==============================
    // LENGKAPI PROFIL PASIEN BARU
    // ==============================

    public function completeProfile()
    {
        $user = Auth::user();
        if ($user->role !== 'pasien') {
            return redirect('/');
        }
        
        $pasien = Pasien::where('id_user', $user->id)->first();
        if ($pasien) {
            return redirect()->route('reservasi.create');
        }

        return view('pasien.complete_profile', compact('user'));
    }

    public function storeProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string'
        ]);

        $nik = (string) $user->username;
        if ($nik === '') {
            return back()->withErrors([
                'NIK' => 'NIK tidak ditemukan pada akun. Silakan hubungi admin.',
            ]);
        }

        if (Pasien::where('NIK', $nik)->exists()) {
            return back()->withErrors([
                'NIK' => 'NIK sudah terdaftar.',
            ]);
        }

        Pasien::create([
            'id_user' => $user->id,
            'nama_pasien' => $request->nama_pasien,
            'jenis_kelamin' => $request->jenis_kelamin,
            'NIK' => $nik,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ]);

        return redirect()->route('reservasi.create')->with('success', 'Profil Anda berhasil dilengkapi. Silakan daftar antrian sekarang.');
    }


    // ==============================
    // MENAMPILKAN DATA PASIEN
    // ==============================

    public function index()
    {
        $pasien = Pasien::all();

        return view('pasien.index', compact('pasien'));
    }


    // ==============================
    // FORM TAMBAH PASIEN
    // ==============================

    public function create()
    {
        return view('pasien.create');
    }


    // ==============================
    // SIMPAN DATA PASIEN
    // ==============================

    public function store(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'NIK' => 'required|string|max:16|unique:pasien,NIK',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string'
        ]);

        // Auto-create user account to satisfy foreight-key constraints
        $user = \App\Models\User::create([
            'name' => $request->nama_pasien,
            'username' => $request->NIK,
            'email' => strtolower(str_replace(' ', '', $request->nama_pasien)) . rand(100,999) . '@klinik.com',
            'password' => bcrypt($request->NIK),
            'role' => 'pasien'
        ]);

        Pasien::create([
            'id_user' => $user->id,
            'nama_pasien' => $request->nama_pasien,
            'jenis_kelamin' => $request->jenis_kelamin,
            'NIK' => $request->NIK,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ]);

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil ditambahkan secara manual.');
    }


    // ==============================
    // FORM EDIT PASIEN
    // ==============================

    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);

        return view('pasien.edit', compact('pasien'));
    }


    // ==============================
    // UPDATE DATA PASIEN
    // ==============================

    public function update(Request $request, $id)
    {

        $request->validate([
            'nama_pasien' => 'required',
            'jenis_kelamin' => 'required',
            'NIK' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required'
        ]);

        $pasien = Pasien::findOrFail($id);

        $pasien->update([
            'nama_pasien' => $request->nama_pasien,
            'jenis_kelamin' => $request->jenis_kelamin,
            'NIK' => $request->NIK,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ]);

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil diupdate');
    }


    // ==============================
    // HAPUS DATA PASIEN
    // ==============================

    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);

        $pasien->delete();

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil dihapus');
    }

}
