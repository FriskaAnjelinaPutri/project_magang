<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{
    private const STATUS_MENUNGGU = 'menunggu';
    private const STATUS_DIPANGGIL = 'dipanggil';
    private const STATUS_SELESAI = 'selesai';
    private const STATUS_DILEWATI = 'dilewati';

    private function getRedirectRoute()
    {
        $user = Auth::user();

        if ($user) {
            if ($user->role === 'dokter') return 'dashboard.dokter';
            if ($user->role === 'admin') return 'dashboard.admin';
        }

        return 'antrian.index';
    }

    // menampilkan semua antrian
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());

        // data yang statusnya "dipanggil" per hari
        $dipanggil = Antrian::whereDate('tanggal_antrian', $tanggal)
            ->where('status', self::STATUS_DIPANGGIL)
            ->orderBy('updated_at', 'asc')
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
            ->orderByRaw("FIELD(status, 'dipanggil', 'menunggu', 'dilewati', 'belum_datang', 'selesai')")
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('antrian.index', compact('antrian', 'tanggal'));
    }

    // membuat nomor antrian
    public function store(Request $request)
    {
        // Hitung berdasarkan nomor urut kedatangan hari ini
        $nomor = Antrian::whereDate('tanggal_antrian', today())
                    ->where('nomor_antrian', '>', 0)
                    ->max('nomor_antrian') + 1;

        Antrian::create([
            'id_pendaftaran' => $request->id_pendaftaran,
            'nomor_antrian' => $nomor,
            'tanggal_antrian' => today(),
            'status' => self::STATUS_MENUNGGU
        ]);

        return redirect()->route($this->getRedirectRoute())
            ->with('success','Nomor antrian berhasil dibuat');
    }

    // menandai pasien hadir
    public function hadir(Request $request, $id)
    {
        $antrian = Antrian::with('pendaftaran.layanan')->findOrFail($id);

        $statusSaatIni = strtolower(trim((string) $antrian->status));
        if ($statusSaatIni !== 'belum_datang') {
            return redirect()->back()->with('error', 'Status antrian tidak valid untuk ditandai hadir.');
        }

        // Berikan nomor antrian baru (berdasarkan urutan kedatangan hari itu)
        $antrianKe = Antrian::whereDate('tanggal_antrian', $antrian->tanggal_antrian)
            ->where('nomor_antrian', '>', 0)
            ->max('nomor_antrian') + 1;

        $antrian->update([
            'status' => self::STATUS_MENUNGGU,
            'nomor_antrian' => $antrianKe
        ]);

        if ($antrian->pendaftaran && $antrian->pendaftaran->layanan) {
            $pembayaran = Pembayaran::where('id_pendaftaran', $antrian->pendaftaran->id_pendaftaran)->first();
            if (!$pembayaran) {
                Pembayaran::create([
                    'id_pendaftaran' => $antrian->pendaftaran->id_pendaftaran,
                    'total_bayar' => $antrian->pendaftaran->layanan->harga,
                    'tanggal_pembayaran' => $antrian->tanggal_antrian,
                    'status' => 'belum lunas',
                    'metode_pembayaran' => 'cash',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pasien telah melapor datang dan masuk antrian menunggu.');
    }

    // memanggil pasien
    public function panggil(Request $request, $id)
    {
        $antrian = Antrian::with('pendaftaran.layanan')->findOrFail($id);

        $tanggal = $antrian->tanggal_antrian;
        $sedangDipanggil = Antrian::whereDate('tanggal_antrian', $tanggal)
            ->where('status', self::STATUS_DIPANGGIL)
            ->where('id_antrian', '!=', $antrian->id_antrian)
            ->first();
        if ($sedangDipanggil) {
            return redirect()->back()->with('error', 'Masih ada antrian yang sedang dipanggil.');
        }

        $antrian->update([
            'status' => self::STATUS_DIPANGGIL
        ]);
        if ($antrian->pendaftaran) {
            $antrian->pendaftaran->update([
                'status' => self::STATUS_DIPANGGIL,
            ]);

            // Cek apakah pembayaran sudah dihapus (karena dilewati), jika ya, buat ulang
            $pembayaran = Pembayaran::where('id_pendaftaran', $antrian->pendaftaran->id_pendaftaran)->first();
            if (!$pembayaran && $antrian->pendaftaran->layanan) {
                Pembayaran::create([
                    'id_pendaftaran' => $antrian->pendaftaran->id_pendaftaran,
                    'total_bayar' => $antrian->pendaftaran->layanan->harga,
                    'tanggal_pembayaran' => $antrian->tanggal_antrian,
                    'status' => 'belum lunas',
                    'metode_pembayaran' => 'cash',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pasien berhasil dipanggil.');
    }

    // menyelesaikan antrian
    public function selesai(Request $request, $id)
    {
        $antrian = Antrian::with('pendaftaran')->findOrFail($id);

        $statusSaatIni = strtolower(trim((string) $antrian->status));
        if ($statusSaatIni !== self::STATUS_DIPANGGIL) {
            return redirect()->back()->with('error', 'Antrian tidak dapat diselesaikan karena belum berstatus dipanggil.');
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
            ->orderBy('updated_at', 'asc')
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

        return redirect()->back()->with('success', 'Antrian berhasil diselesaikan.');
    }

    // lewati antrian (pasien belum hadir / no-show)
    public function lewati(Request $request, $id)
    {
        $antrian = Antrian::with('pendaftaran')->findOrFail($id);

        $statusSaatIni = strtolower(trim((string) $antrian->status));
        if ($statusSaatIni !== self::STATUS_DIPANGGIL) {
            return redirect()->back()->with('error', 'Antrian tidak dapat dilewati karena belum berstatus dipanggil.');
        }

        $antrian->update([
            'status' => self::STATUS_DILEWATI,
        ]);

        // Kembalikan status pendaftaran jadi menunggu (karena pasien belum hadir).
        if ($antrian->pendaftaran) {
            $antrian->pendaftaran->update([
                'status' => self::STATUS_MENUNGGU,
            ]);

            // Hapus pembayaran terkait agar tidak muncul di tabel pembayaran admin/kasir
            Pembayaran::where('id_pendaftaran', $antrian->pendaftaran->id_pendaftaran)->delete();
        }

        // Panggil nomor berikutnya yang menunggu/dilewati (jika ada).
        $antrianBerikutnya = Antrian::with('pendaftaran')
            ->whereDate('tanggal_antrian', $antrian->tanggal_antrian)
            ->whereIn('status', [self::STATUS_MENUNGGU, self::STATUS_DILEWATI])
            ->orderBy('updated_at', 'asc')
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

        return redirect()->back()->with('success', $antrianBerikutnya ? 'Antrian dilewati.' : 'Antrian dilewati.');
    }

    // menghapus antrian
    public function destroy($id)
    {
        Antrian::destroy($id);

        return redirect()->back()->with('success', 'Antrian berhasil dihapus.');
    }
}
