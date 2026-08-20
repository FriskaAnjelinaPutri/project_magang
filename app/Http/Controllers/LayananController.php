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

    public function create()
    {
        $parents = Layanan::whereNull('parent_id')->get();
        return view('layanan.create', compact('parents'));
    }

    // menyimpan layanan
    public function store(Request $request)
    {
        Layanan::create([
            'nama_layanan' => $request->nama_layanan,
            'harga' => $request->harga,
            'tampil_di_booking' => $request->tampil_di_booking,
            'parent_id' => $request->parent_id
        ]);

        return redirect()->route('layanan.index')
            ->with('success','Layanan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        $parents = Layanan::whereNull('parent_id')->where('id_layanan', '!=', $id)->get();
        return view('layanan.edit', compact('layanan', 'parents'));
    }

    // update layanan
    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);

        $layanan->update([
            'nama_layanan' => $request->nama_layanan, 
            'harga' => $request->harga,
            'tampil_di_booking' => $request->tampil_di_booking,
            'parent_id' => $request->parent_id
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
