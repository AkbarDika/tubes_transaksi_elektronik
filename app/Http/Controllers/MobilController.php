<?php

namespace App\Http\Controllers;

use App\Models\Car as Mobil;
use App\Models\KategoriMobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index()
    {
        $mobils = Mobil::with('kategori')->paginate(10);
        $kategori = KategoriMobil::all();

        return view('admin.cars.index', compact('mobils', 'kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_mobil,id',
            'merk'        => 'required',
            'model'       => 'required',
            'tahun'       => 'required|digits:4',
            'nomor_plat'  => 'required|unique:mobil,nomor_plat',
            'harga_sewa'  => 'required|numeric',
            'status'      => 'required',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('cars', 'public');
            $validated['foto'] = $fotoPath;
        }

        Mobil::create($validated);

        return redirect()->back()->with('success', 'Mobil berhasil ditambahkan');
    }

    public function update(Request $request, Mobil $mobil)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_mobil,id',
            'merk'        => 'required',
            'model'       => 'required',
            'tahun'       => 'required|digits:4',
            'nomor_plat'  => 'required|unique:mobil,nomor_plat,' . $mobil->id,
            'harga_sewa'  => 'required|numeric',
            'status'      => 'required',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($mobil->foto && \Storage::disk('public')->exists($mobil->foto)) {
                \Storage::disk('public')->delete($mobil->foto);
            }
            $fotoPath = $request->file('foto')->store('cars', 'public');
            $validated['foto'] = $fotoPath;
        }

        $mobil->update($validated);

        return redirect()->back()->with('success', 'Mobil berhasil diupdate');
    }

    public function destroy(Mobil $mobil)
    {
        // Hapus foto jika ada
        if ($mobil->foto && \Storage::disk('public')->exists($mobil->foto)) {
            \Storage::disk('public')->delete($mobil->foto);
        }

        $mobil->delete();
        return redirect()->back()->with('success', 'Mobil berhasil dihapus');
    }
}
