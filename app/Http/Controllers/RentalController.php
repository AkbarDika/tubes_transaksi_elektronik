<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    /**
     * Show rental form untuk mobil tertentu
     */
    public function create($carId)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login')->with('message', 'Silakan login terlebih dahulu untuk melakukan pemesanan');
        }

        // Ambil data mobil berdasarkan ID
        $car = Car::findOrFail($carId);

        return view('rental.create', compact('car'));
    }

    /**
     * Store pemesanan ke database
     * Menyimpan ke 2 tabel: pemesanan (master) dan detail_pemesanan (detail)
     */
    public function store(Request $request)
    {
        // Validate input dengan custom validation untuk memastikan 1 hari minimal
        $validated = $request->validate([
            'mobil_id' => 'required|exists:mobil,id',
            'tanggal_mulai' => 'required|date|after:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // Hitung durasi sewa menggunakan diff()->days untuk akurasi berdasarkan tanggal kalender saja
        $tanggal_mulai = \Carbon\Carbon::parse($request->tanggal_mulai)->startOfDay();
        $tanggal_selesai = \Carbon\Carbon::parse($request->tanggal_selesai)->startOfDay();
        $lama_sewa = $tanggal_selesai->diff($tanggal_mulai)->days;

        // ✅ Validasi: minimal 1 hari, tidak boleh 0 atau negatif
        if ($lama_sewa <= 0) {
            return back()
                ->withInput()
                ->withErrors(['tanggal_selesai' => 'Tanggal kembali harus lebih dari 1 hari setelah tanggal mulai. Minimal sewa 1 hari.']);
        }

        // Ambil harga dari mobil
        $car = Car::find($request->mobil_id);
        $harga_per_hari = $car->harga_sewa;
        $subtotal = $lama_sewa * $harga_per_hari;

        // Transaction: Insert ke 2 tabel atomically
        $pemesanan = DB::transaction(function () use ($request, $tanggal_mulai, $lama_sewa, $harga_per_hari, $subtotal) {
            // 1. Buat pemesanan (master)
            $pemesanan = Pemesanan::create([
                'user_id' => Auth::id(),
                'tanggal_pesan' => now()->toDateString(),
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'total_harga' => $subtotal,
                'status' => 'pending',
            ]);

            // 2. Buat detail pemesanan
            DetailPemesanan::create([
                'pemesanan_id' => $pemesanan->id,
                'mobil_id' => $request->mobil_id,
                'lama_sewa' => $lama_sewa,
                'harga_per_hari' => $harga_per_hari,
                'subtotal' => $subtotal,
            ]);

            return $pemesanan;
        });

        return redirect()
            ->route('user.riwayat_pesanan')
            ->with('success', 'Pemesanan berhasil dibuat. Tunggu persetujuan petugas, lalu lakukan pembayaran.');
    }
}
