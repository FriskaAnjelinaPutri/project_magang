<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Layanan;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\Antrian;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'pasien' => Pasien::count(),
            'layanan' => Layanan::count(),
            'pendaftaran' => Pendaftaran::count(),
            'pembayaran' => Pembayaran::count(),
        ];

        // Tabel pendaftaran
        $recent_pendaftaran = Pendaftaran::with('pasien', 'layanans')
        ->latest('created_at')
        ->take(5)
        ->get();

        // Antrian hari ini 
        $antrian_hari_ini = Antrian::with('pendaftaran.pasien', 'pendaftaran.layanans')
            ->whereDate('tanggal_antrian', today())
            ->orderByRaw("nomor_antrian = 0, nomor_antrian ASC")
            ->get();

        $antrian_stats = [
            'belum_datang' => $antrian_hari_ini->where('status', 'belum_datang')->count(),
            'menunggu'  => $antrian_hari_ini->where('status', 'menunggu')->count(),
            'dipanggil' => $antrian_hari_ini->where('status', 'dipanggil')->count(),
            'dilewati'  => $antrian_hari_ini->where('status', 'dilewati')->count(),
            'selesai'   => $antrian_hari_ini->where('status', 'selesai')->count(),
            'total'     => $antrian_hari_ini->count(),
        ];

        // mengambil data untuk grafik kunjungan 7 hari terakhir
        $chartData = Pendaftaran::select(DB::raw('DATE(tanggal_kunjungan) as date'), DB::raw('count(*) as count'))
            ->where('tanggal_kunjungan', '>=', Carbon::now()->subDays(6)->toDateString())
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartMap = $chartData->pluck('count', 'date')->toArray();
        $labels = [];
        $data = [];

        // Ensure 7 sequential days
        for ($i = 6; $i >= 0; $i--) {
            $dateString = Carbon::now()->subDays($i)->toDateString();
            $labels[] = Carbon::now()->subDays($i)->translatedFormat('d M');
            $data[] = $chartMap[$dateString] ?? 0;
        }

        return view('admin.dashboard', compact('stats', 'recent_pendaftaran', 'antrian_hari_ini', 'antrian_stats', 'labels', 'data'));
    }
}
