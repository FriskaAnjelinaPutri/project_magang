<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RekamMedis;

class RekamMedisController extends Controller
{
    public static function getDaftarObat()
    {
        return [
            ['nama_obat' => 'Paracetamol 500mg', 'harga' => 15000],
            ['nama_obat' => 'Amoxicillin 500mg', 'harga' => 20000],
            ['nama_obat' => 'Ibuprofen 400mg', 'harga' => 12000],
            ['nama_obat' => 'Antasida Doen', 'harga' => 10000],
            ['nama_obat' => 'Vitamin C', 'harga' => 5000],
            ['nama_obat' => 'Kalsium', 'harga' => 8000],
        ];
    }

    public function index(Request $request)
    {
        $tanggalFilter = $request->query('tanggal', now()->toDateString());
        
        $rekamMedis = RekamMedis::with(['pasien', 'pendaftaran.layanans'])
            ->whereDate('tanggal_periksa', $tanggalFilter)
            ->latest()
            ->get();
            
        // Determine layout based on user role (Admin or Dokter)
        $layout = auth()->user()->role === 'dokter' ? 'layouts.dokter' : 'layouts.admin';
        return view('rekam_medis.index', compact('rekamMedis', 'layout', 'tanggalFilter'));
    }

    public function create(Request $request)
    {
        $idPendaftaran = $request->query('id_pendaftaran');
        // Fetch pendaftaran data if provided to pre-fill the form
        $pendaftaran = null;
        if ($idPendaftaran) {
            $pendaftaran = \App\Models\Pendaftaran::with('pasien', 'layanans')->find($idPendaftaran);
        }
        
        $layanan = \App\Models\Layanan::whereNull('parent_id')->with('children')->get();
        $obatList = self::getDaftarObat();
        $layout = auth()->user()->role === 'dokter' ? 'layouts.dokter' : 'layouts.admin';
        return view('rekam_medis.create', compact('pendaftaran', 'layout', 'layanan', 'obatList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pasien' => 'required|exists:pasien,id_pasien',
            'id_pendaftaran' => 'nullable|exists:pendaftaran,id_pendaftaran',
            'id_layanan' => 'required|array|min:1',
            'id_layanan.*' => 'required|exists:layanan,id_layanan',
            'keluhan' => 'required|string',
            'tindakan' => 'required|string',
            'resep_obat' => 'nullable|string',
            'tanggal_periksa' => 'required|date',
        ]);

        $biayaObat = 0;
        $resepObatArr = [];
        $daftarObat = self::getDaftarObat();

        if ($request->has('nama_obat_list')) {
            $namaObats = $request->nama_obat_list;
            $jumlahObats = $request->jumlah_obat;
            $dosisObats = $request->dosis_obat;
            foreach ($namaObats as $idx => $namaObat) {
                if ($namaObat) {
                    // Cari harga obat
                    $harga = 0;
                    foreach ($daftarObat as $obat) {
                        if ($obat['nama_obat'] === $namaObat) {
                            $harga = $obat['harga'];
                            break;
                        }
                    }

                    $qty = $jumlahObats[$idx] ?? 1;
                    $dosis = $dosisObats[$idx] ?? '';
                    $biayaObat += ($harga * $qty);
                    
                    $dosisText = $dosis ? " ($dosis)" : "";
                    $resepObatArr[] = "- $namaObat ($qty pcs)$dosisText";
                }
            }
        }

        $inputData = $request->except(['id_layanan', 'nama_obat_list', 'jumlah_obat', 'dosis_obat']);
        $inputData['biaya_obat'] = $biayaObat;
        
        // Gabungkan resep hardcode dengan catatan tambahan
        $gabunganResep = implode("\n", $resepObatArr);
        if ($request->resep_obat) {
            $gabunganResep .= "\n\nCatatan: " . $request->resep_obat;
        }
        $inputData['resep_obat'] = trim($gabunganResep);
        
        $rekamMedis = RekamMedis::create($inputData);

        if ($request->id_pendaftaran) {
            $pendaftaran = \App\Models\Pendaftaran::find($request->id_pendaftaran);
            if ($pendaftaran) {
                // Update daftar layanan final pilihan dokter beserta jumlahnya
                $syncData = [];
                $idLayananUnique = array_unique($request->id_layanan);
                foreach($idLayananUnique as $id) {
                    $syncData[$id] = ['jumlah' => $request->jumlah[$id] ?? 1];
                }
                $pendaftaran->layanans()->sync($syncData);

                // Reload relasi untuk mengambil pivot jumlah terbaru
                $pendaftaran->load('layanans');
                $totalLayanan = 0;
                foreach($pendaftaran->layanans as $lay) {
                    $totalLayanan += $lay->harga * ($lay->pivot->jumlah ?? 1);
                }
                
                $totalBayar = $totalLayanan + $biayaObat;

                // Buat tagihan pembayaran (jika belum ada)
                $pembayaran = \App\Models\Pembayaran::where('id_pendaftaran', $request->id_pendaftaran)->first();
                if ($pembayaran) {
                    $pembayaran->total_bayar = $totalBayar;
                    $pembayaran->save();
                } else {
                    \App\Models\Pembayaran::create([
                        'id_pendaftaran' => $request->id_pendaftaran,
                        'total_bayar' => $totalBayar,
                        'tanggal_pembayaran' => date('Y-m-d'),
                        'status' => 'belum lunas',
                        'metode_pembayaran' => 'cash',
                    ]);
                }

                // Otomatis selesaikan antrian pasien ini dan panggil pasien berikutnya
                $antrian = \App\Models\Antrian::where('id_pendaftaran', $request->id_pendaftaran)->first();
                if ($antrian && strtolower(trim((string)$antrian->status)) === 'dipanggil') {
                    $antrian->update(['status' => 'selesai']);
                    $pendaftaran->update(['status' => 'selesai']);
                    
                    // Cari dan panggil antrian berikutnya
                    $antrianBerikutnya = \App\Models\Antrian::with('pendaftaran')
                        ->whereDate('tanggal_antrian', $antrian->tanggal_antrian)
                        ->whereIn('status', ['menunggu', 'dilewati'])
                        ->orderBy('nomor_antrian', 'asc')
                        ->first();
                        
                    if ($antrianBerikutnya) {
                        $antrianBerikutnya->update(['status' => 'dipanggil']);
                        if ($antrianBerikutnya->pendaftaran) {
                            $antrianBerikutnya->pendaftaran->update(['status' => 'dipanggil']);
                        }
                    }
                }
            }
        }

        return redirect()->route('dashboard.dokter')->with('success', 'Rekam medis berhasil disimpan dan layanan final diperbarui.');
    }

    public function show($id)
    {
        $rekamMedis = RekamMedis::with(['pasien', 'pendaftaran'])->findOrFail($id);
        return response()->json($rekamMedis);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'keluhan' => 'required|string',
            'tindakan' => 'required|string',
            'resep_obat' => 'nullable|string',
            'tanggal_periksa' => 'required|date',
            'id_layanan' => 'sometimes|array|min:1',
            'id_layanan.*' => 'exists:layanan,id_layanan',
        ]);

        $rekamMedis = RekamMedis::findOrFail($id);
        
        $biayaObat = 0;
        $resepObatArr = [];
        $daftarObat = self::getDaftarObat();

        if ($request->has('nama_obat_list')) {
            $namaObats = $request->nama_obat_list;
            $jumlahObats = $request->jumlah_obat;
            $dosisObats = $request->dosis_obat;
            foreach ($namaObats as $idx => $namaObat) {
                if ($namaObat) {
                    // Cari harga obat
                    $harga = 0;
                    foreach ($daftarObat as $obat) {
                        if ($obat['nama_obat'] === $namaObat) {
                            $harga = $obat['harga'];
                            break;
                        }
                    }

                    $qty = $jumlahObats[$idx] ?? 1;
                    $dosis = $dosisObats[$idx] ?? '';
                    $biayaObat += ($harga * $qty);
                    
                    $dosisText = $dosis ? " ($dosis)" : "";
                    $resepObatArr[] = "- $namaObat ($qty pcs)$dosisText";
                }
            }
        }

        $inputData = $request->except(['id_layanan', 'nama_obat_list', 'jumlah_obat', 'dosis_obat']);
        $inputData['biaya_obat'] = $biayaObat;
        
        // Karena ini update dari API, mungkin kita timpa resep_obat sepenuhnya jika ada list obat,
        // tapi resep_obat dari request juga harus dimasukkan.
        $gabunganResep = implode("\n", $resepObatArr);
        if ($request->resep_obat) {
            $gabunganResep .= "\n\nCatatan: " . $request->resep_obat;
        }
        $inputData['resep_obat'] = trim($gabunganResep);
        
        $rekamMedis->update($inputData);

        if ($rekamMedis->id_pendaftaran && $request->has('id_layanan')) {
            $pendaftaran = \App\Models\Pendaftaran::find($rekamMedis->id_pendaftaran);
            if ($pendaftaran) {
                $syncData = [];
                $idLayananUnique = array_unique($request->id_layanan);
                foreach($idLayananUnique as $id) {
                    $syncData[$id] = ['jumlah' => $request->jumlah[$id] ?? 1];
                }
                $pendaftaran->layanans()->sync($syncData);
                
                $pendaftaran->load('layanans');
                $totalLayanan = 0;
                foreach($pendaftaran->layanans as $lay) {
                    $totalLayanan += $lay->harga * ($lay->pivot->jumlah ?? 1);
                }
                
                $totalBayar = $totalLayanan + $biayaObat;
                
                $pembayaran = \App\Models\Pembayaran::where('id_pendaftaran', $rekamMedis->id_pendaftaran)->first();
                if ($pembayaran) {
                    // Update total pembayaran
                    $pembayaran->total_bayar = $totalBayar;
                    $pembayaran->save();
                }
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

    public function cetak(Request $request)
    {
        $tanggalFilter = $request->query('tanggal', now()->toDateString());
        
        $rekamMedis = RekamMedis::with(['pasien', 'pendaftaran.layanans'])
            ->whereDate('tanggal_periksa', $tanggalFilter)
            ->orderBy('tanggal_periksa', 'asc')
            ->get();
            
        return view('rekam_medis.cetak', compact('rekamMedis', 'tanggalFilter'));
    }

    public function simpanRujukan(Request $request, $id)
    {
        $request->validate([
            'rujukan_dokter' => 'required|string',
            'rujukan_rs' => 'required|string',
            'rujukan_diagnosa_sementara' => 'nullable|string',
            'rujukan_kasus' => 'nullable|string',
            'rujukan_terapi' => 'nullable|string',
        ]);

        $rekamMedis = RekamMedis::findOrFail($id);
        $rekamMedis->update([
            'rujukan_dokter' => $request->rujukan_dokter,
            'rujukan_rs' => $request->rujukan_rs,
            'rujukan_diagnosa_sementara' => $request->rujukan_diagnosa_sementara,
            'rujukan_kasus' => $request->rujukan_kasus,
            'rujukan_terapi' => $request->rujukan_terapi,
        ]);

        return redirect()->back()->with('success', 'Surat rujukan berhasil dibuat.');
    }

    public function cetakRujukan($id)
    {
        $rekamMedis = RekamMedis::with(['pasien'])->findOrFail($id);
        
        if (!$rekamMedis->rujukan_dokter) {
            return redirect()->back()->with('error', 'Data rujukan belum diisi.');
        }

        return view('rekam_medis.cetak_rujukan', compact('rekamMedis'));
    }
}
