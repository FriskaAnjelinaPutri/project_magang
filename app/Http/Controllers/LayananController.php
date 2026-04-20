<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;

class LayananController extends Controller
{
    // menampilkan semua layanan
    public function index()
    {
        $layanan = Layanan::all();
        return view('layanan.index', compact('layanan'));
    }

    // menampilkan form tambah layanan
    public function create()
    {
        return view('layanan.create');
    }

    // menyimpan layanan
    public function store(Request $request)
    {
        Layanan::create([
            'nama_layanan' => $request->nama_layanan,
            'harga' => $request->harga
        ]);

        return redirect()->route('layanan.index')
            ->with('success','Layanan berhasil ditambahkan');
    }

    // menampilkan form edit
    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        return view('layanan.edit', compact('layanan'));
    }

    // update layanan
    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);

        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'harga' => $request->harga
        ]);

        return redirect()->route('layanan.index')
            ->with('success','Layanan berhasil diupdate');
    }

    // hapus layanan
    public function destroy($id)
    {
        Layanan::destroy($id);

        return redirect()->route('layanan.index')
            ->with('success','Layanan berhasil dihapus');
    }
}
