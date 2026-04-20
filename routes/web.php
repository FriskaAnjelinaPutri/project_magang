 <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Pasien;
use App\Models\Antrian;
use App\Models\Pendaftaran;

Route::get('/', function () {
    $layanans = Layanan::all();
    $stats = [
        'pasien' => Pasien::count(),
        'antrian' => Antrian::count(),
        'layanan' => Layanan::count(),
        'pendaftaran' => Pendaftaran::count(),
    ];
    return view('landing', compact('layanans', 'stats'));
})->name('landing');

Route::get('/jadwal', function (Request $request) {
    $tanggal = $request->input('tanggal', date('Y-m-d'));
    
    // Asumsikan status yang ditampilkan adalah yang sudah dikonfirmasi atau antri
    $pendaftarans = Pendaftaran::with('pasien', 'layanan')
        ->whereDate('tanggal_kunjungan', $tanggal)
        ->orderBy('created_at', 'asc')
        ->get();
        
    return view('jadwal', compact('pendaftarans', 'tanggal'));
})->name('jadwal');

Route::get('/antrian-monitor', function () {
    $antrians = Antrian::with('pendaftaran.pasien', 'pendaftaran.layanan')
        ->whereDate('tanggal_antrian', today())
        ->orderBy('nomor_antrian', 'asc')
        ->get();

    $sedangDipanggil = $antrians->firstWhere('status', 'dipanggil');

    $stats = [
        'menunggu'  => $antrians->where('status', 'menunggu')->count(),
        'dipanggil' => $antrians->where('status', 'dipanggil')->count(),
        'selesai'   => $antrians->where('status', 'selesai')->count(),
    ];

    return view('antrian-monitor', compact('antrians', 'sedangDipanggil', 'stats'));
})->name('antrian.monitor');

Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'registerStore'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\AdminController;

Route::get('/dashboard/admin', [AdminController::class, 'index'])
    ->middleware('auth')->name('dashboard.admin');

Route::get('/dashboard/kasir', function () {
    return redirect()->route('pembayaran.index');
})->middleware('auth')->name('dashboard.kasir');

Route::get('/dashboard/pasien', [PasienController::class, 'dashboard'])
    ->middleware('auth')->name('dashboard.pasien');

Route::get('/pasien/complete-profile', [PasienController::class, 'completeProfile'])->name('pasien.complete_profile')->middleware('auth');
Route::post('/pasien/store-profile', [PasienController::class, 'storeProfile'])->name('pasien.store_profile')->middleware('auth');

Route::resource('pasien', PasienController::class)->middleware('auth');
Route::resource('layanan', LayananController::class)->middleware('auth');

use App\Http\Controllers\AntrianController;

Route::put('/antrian/{id}/panggil', [AntrianController::class, 'panggil'])->name('antrian.panggil')->middleware('auth');
Route::put('/antrian/{id}/selesai', [AntrianController::class, 'selesai'])->name('antrian.selesai')->middleware('auth');
Route::resource('antrian', AntrianController::class)->middleware('auth');

Route::get('/pendaftaran/{id}/cetak', [PendaftaranController::class, 'cetak'])->name('pendaftaran.cetak')->middleware('auth');

Route::resource('pendaftaran', PendaftaranController::class)->middleware('auth');
Route::resource('pembayaran', PembayaranController::class)->middleware('auth');
