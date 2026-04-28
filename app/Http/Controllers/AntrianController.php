<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use App\Models\Pendaftaran;

class AntrianController extends Controller
{
    private const STATUS_MENUNGGU = 'menunggu';
    private const STATUS_DIPANGGIL = 'dipanggil';
    private const STATUS_SELESAI = 'selesai';
    private const STATUS_DILEWATI = 'dilewati';

    private function getRedirectRoute()
    {
        return (auth()->check() && auth()->user()->role === 'dokter') ? 'dashboard.dokter' : 'antrian.index';
    }

    // menampilkan semua antrian
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());

        // Data guard: only allow one "dipanggil" per day.
        $dipanggil = Antrian::whereDate('tanggal_antrian', $tanggal)
            ->where('status', self::STATUS_DIPANGGIL)
            ->orderBy('nomor_antrian', 'asc')
            ->get(['id_antrian', 'nomor_antrian']);
        if ($dipanggil->count() > 1) {
            $keepId = $dipanggil->first()->id_antrian;
            Antrian::whereDate('tanggal_antrian', $tanggal)
                ->where('status', self::STATUS_DIPANGGIL)
                ->where('id_antrian', '!=', $keepId)
                ->update(['status' => self::STATUS_MENUNGGU]);
        }

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
            'status' => self::STATUS_MENUNGGU
        ]);

        return redirect()->route($this->getRedirectRoute())
            ->with('success','Nomor antrian berhasil dibuat');
    }

    // memanggil pasien
    public function panggil(Request $request, $id)
    {
        $antrian = Antrian::with('pendaftaran')->findOrFail($id);

        $tanggal = $antrian->tanggal_antrian;
        $sedangDipanggil = Antrian::whereDate('tanggal_antrian', $tanggal)
            ->where('status', self::STATUS_DIPANGGIL)
            ->where('id_antrian', '!=', $antrian->id_antrian)
            ->first();
        if ($sedangDipanggil) {
            return redirect()->route($this->getRedirectRoute(), [
                'tanggal' => $request->input('tanggal', $tanggal),
            ])->with('error', 'Masih ada antrian yang sedang dipanggil. Selesaikan atau lewati dulu sebelum memanggil nomor lain.');
        }

        $antrian->update([
            'status' => self::STATUS_DIPANGGIL
        ]);
        if ($antrian->pendaftaran) {
            $antrian->pendaftaran->update([
                'status' => self::STATUS_DIPANGGIL,
            ]);
        }

        return redirect()->route($this->getRedirectRoute(), [
            'tanggal' => $request->input('tanggal', $antrian->tanggal_antrian),
        ])->with('success', 'Pasien berhasil dipanggil.');
    }

    // menyelesaikan antrian
    public function selesai(Request $request, $id)
    {
        $antrian = Antrian::with('pendaftaran')->findOrFail($id);

        $statusSaatIni = strtolower(trim((string) $antrian->status));
        if ($statusSaatIni !== self::STATUS_DIPANGGIL) {
            return redirect()->route($this->getRedirectRoute(), [
                'tanggal' => $request->input('tanggal', $antrian->tanggal_antrian),
            ])->with('success', 'Antrian tidak dapat diselesaikan karena belum berstatus dipanggil.');
        }

        $antrian->update([
            'status' => self::STATUS_SELESAI
        ]);
        if ($antrian->pendaftaran) {
            $antrian->pendaftaran->update([
                'status' => self::STATUS_SELESAI,
            ]);
        }

        // Auto-advance ke antrian berikutnya (jika ada) pada tanggal yang sama.
        $antrianBerikutnya = Antrian::with('pendaftaran')
            ->whereDate('tanggal_antrian', $antrian->tanggal_antrian)
            ->whereIn('status', [self::STATUS_MENUNGGU, self::STATUS_DILEWATI])
            ->where('nomor_antrian', '>', $antrian->nomor_antrian)
            ->orderBy('nomor_antrian', 'asc')
            ->first();

        if ($antrianBerikutnya) {
            $antrianBerikutnya->update([
                'status' => self::STATUS_DIPANGGIL,
            ]);

            if ($antrianBerikutnya->pendaftaran) {
                $antrianBerikutnya->pendaftaran->update([
                    'status' => self::STATUS_DIPANGGIL,
                ]);
            }
        }

        return redirect()->route($this->getRedirectRoute(), [
            'tanggal' => $request->input('tanggal', $antrian->tanggal_antrian),
        ])->with('success', $antrianBerikutnya
            ? 'Antrian berhasil diselesaikan. Sistem otomatis memanggil nomor berikutnya.'
            : 'Antrian berhasil diselesaikan.');
    }

    // lewati antrian (pasien belum hadir / no-show)
    public function lewati(Request $request, $id)
    {
        $antrian = Antrian::with('pendaftaran')->findOrFail($id);

        $statusSaatIni = strtolower(trim((string) $antrian->status));
        if ($statusSaatIni !== self::STATUS_DIPANGGIL) {
            return redirect()->route($this->getRedirectRoute(), [
                'tanggal' => $request->input('tanggal', $antrian->tanggal_antrian),
            ])->with('success', 'Antrian tidak dapat dilewati karena belum berstatus dipanggil.');
        }

        $antrian->update([
            'status' => self::STATUS_DILEWATI,
        ]);

        // Kembalikan status pendaftaran jadi menunggu (karena pasien belum hadir).
        if ($antrian->pendaftaran) {
            $antrian->pendaftaran->update([
                'status' => self::STATUS_MENUNGGU,
            ]);
        }

        // Panggil nomor berikutnya yang menunggu/dilewati (jika ada).
        $antrianBerikutnya = Antrian::with('pendaftaran')
            ->whereDate('tanggal_antrian', $antrian->tanggal_antrian)
            ->whereIn('status', [self::STATUS_MENUNGGU, self::STATUS_DILEWATI])
            ->where('nomor_antrian', '>', $antrian->nomor_antrian)
            ->orderBy('nomor_antrian', 'asc')
            ->first();

        if ($antrianBerikutnya) {
            $antrianBerikutnya->update([
                'status' => self::STATUS_DIPANGGIL,
            ]);

            if ($antrianBerikutnya->pendaftaran) {
                $antrianBerikutnya->pendaftaran->update([
                    'status' => self::STATUS_DIPANGGIL,
                ]);
            }
        }

        return redirect()->route($this->getRedirectRoute(), [
            'tanggal' => $request->input('tanggal', $antrian->tanggal_antrian),
        ])->with('success', $antrianBerikutnya
            ? 'Antrian dilewati. Sistem otomatis memanggil nomor berikutnya.'
            : 'Antrian dilewati.');
    }

    // menghapus antrian
    public function destroy($id)
    {
        Antrian::destroy($id);

        return redirect()->route($this->getRedirectRoute());
    }
}
