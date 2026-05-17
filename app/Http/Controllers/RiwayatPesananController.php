<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;

class RiwayatPesananController extends Controller
{
    public function index()
    {
        $pesanan = Pemesanan::with(['details', 'pembayaran', 'pengembalian'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(5);

        return view('user.riwayat_pesanan.index', compact('pesanan'));
    }
}
