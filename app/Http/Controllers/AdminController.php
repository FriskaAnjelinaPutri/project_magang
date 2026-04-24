<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Layanan;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
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

        // Table Data
        $recent_pendaftaran = Pendaftaran::with('pasien', 'layanan')->latest('created_at')->take(5)->get();

        // Chart Data (Last 7 Days)
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

        return view('admin.dashboard', compact('stats', 'recent_pendaftaran', 'labels', 'data'));
    }
}
