<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalian = Pengembalian::with('pemesanan')->paginate(10);
        $pemesanan = Pemesanan::all();

        return view('admin.pengembalian.index', compact('pengembalian', 'pemesanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemesanan_id' => 'required',
            'tanggal_kembali' => 'required|date',
            'kondisi_mobil' => 'required',
            'status_pengembalian' => 'required'
        ]);

        Pengembalian::create($request->all());

        return redirect()->back()->with('success', 'Data pengembalian berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        $request->validate([
            'pemesanan_id' => 'required',
            'tanggal_kembali' => 'required|date',
            'kondisi_mobil' => 'required',
            'status_pengembalian' => 'required'
        ]);

        $pengembalian->update($request->all());

        return redirect()->back()->with('success', 'Data pengembalian berhasil diperbarui');
    }

    public function destroy($id)
    {
        Pengembalian::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data pengembalian berhasil dihapus');
    }


    public function storeUser(Request $request)
    {
        $request->validate([
            'pemesanan_id'   => 'required|exists:pemesanan,id',
            'tanggal_kembali'=> 'required|date',
            'kondisi_mobil'  => 'required|in:baik,lecet,rusak',
            'catatan'        => 'nullable|string',
        ]);

        // ambil pemesanan milik user
        $pemesanan = Pemesanan::where('id', $request->pemesanan_id)
            ->where('user_id', auth()->id())
            ->with(['pembayaran', 'pengembalian'])
            ->firstOrFail();

        // 1️⃣ harus sudah dibayar
        if (!$pemesanan->pembayaran || $pemesanan->pembayaran->status !== 'valid') {
            return back()->with('error', 'Pesanan belum dibayar.');
        }

        // 2️⃣ tidak boleh double pengembalian
        if ($pemesanan->pengembalian) {
            return back()->with('error', 'Pengembalian sudah diajukan.');
        }

        // INSERT pengembalian
        Pengembalian::create([
            'pemesanan_id'        => $pemesanan->id,
            'tanggal_kembali'     => $request->tanggal_kembali,
            'kondisi_mobil'       => $request->kondisi_mobil,
            'catatan'             => $request->catatan,
            'status_pengembalian' => 'pending',
        ]);

        return back()->with('success', 'Pengembalian berhasil diajukan. Menunggu konfirmasi petugas.');
    }

}
