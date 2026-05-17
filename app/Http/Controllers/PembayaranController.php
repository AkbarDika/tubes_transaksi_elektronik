<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with('pemesanan')->paginate(10);
        $pemesanan = Pemesanan::all();

        return view('admin.payments.index', compact('pembayaran', 'pemesanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemesanan_id' => 'required',
            'metode_pembayaran' => 'required',
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar' => 'required|numeric',
            'bukti_bayar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required'
        ]);

        $data = $request->except('bukti_bayar');

        if ($request->hasFile('bukti_bayar')) {
            $data['bukti_bayar'] = $request->file('bukti_bayar')
                ->store('bukti_bayar', 'public');
        }

        Pembayaran::create($data);

        return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan');
    }


    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $request->validate([
            'pemesanan_id' => 'required',
            'metode_pembayaran' => 'required',
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar' => 'required|numeric',
            'bukti_bayar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required'
        ]);

        $data = $request->except('bukti_bayar');

        if ($request->hasFile('bukti_bayar')) {

            // hapus bukti lama
            if ($pembayaran->bukti_bayar) {
                Storage::disk('public')->delete($pembayaran->bukti_bayar);
            }

            // simpan bukti baru
            $data['bukti_bayar'] = $request->file('bukti_bayar')
                ->store('bukti_bayar', 'public');
        }

        $pembayaran->update($data);

        return redirect()->back()->with('success', 'Pembayaran berhasil diperbarui');
    }

    

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        if ($pembayaran->bukti_bayar) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }

        $pembayaran->delete();

        return redirect()->back()->with('success', 'Pembayaran berhasil dihapus');
    }

}
