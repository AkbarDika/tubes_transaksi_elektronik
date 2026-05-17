<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Car as Mobil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;


class PemesananController extends Controller
{
    public function index()
    {
        $pemesanan = Pemesanan::with([
            'user',
            'details.mobil'
        ])->paginate(10);

        $users = User::all();

        return view('admin.orders.index', compact('pemesanan', 'users'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'total_harga' => 'required|numeric',
            'status' => 'required'
        ]);

        Pemesanan::create([
            'user_id' => $request->user_id,
            'tanggal_pesan' => now(),
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'total_harga' => $request->total_harga,
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Pemesanan berhasil ditambahkan');
    }


    public function update(Request $request, Pemesanan $pemesanan)
    {
        $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'total_harga'     => 'required|numeric',
            'status'          => 'required'
        ]);

        DB::transaction(function () use ($request, $pemesanan) {

            $mulai   = Carbon::parse($request->tanggal_mulai);
            $selesai = Carbon::parse($request->tanggal_selesai);

            $lamaSewa = $mulai->diffInDays($selesai);

            /* UPDATE PEMESANAN */
            $pemesanan->update([
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'total_harga'     => $request->total_harga,
                'status'          => $request->status
            ]);

            /* UPDATE DETAIL PEMESANAN (PAKSA) */
            $updated = $pemesanan->details()->update([
                'lama_sewa' => $lamaSewa,
                'subtotal'  => $request->total_harga
            ]);

            // optional debug
            // logger('detail updated rows: '.$updated);
        });

        return redirect()->back()->with('success', 'Pemesanan & detail berhasil diperbarui');
    }



    public function destroy(Pemesanan $pemesanan)
    {
        DB::transaction(function () use ($pemesanan) {
            $pemesanan->details()->delete(); // hapus detail dulu
            $pemesanan->delete();            // baru hapus pemesanan
        });

        return redirect()->back()->with('success', 'Pemesanan & detail berhasil dihapus');
    }


    /* DETAIL */
    public function show(Pemesanan $pemesanan)
    {
        return view('admin.pemesanan.detail', compact('pemesanan'));
    }

    /**
     * Halaman payment untuk customer setelah pemesanan dibuat
     */
    public function payment(Pemesanan $pemesanan)
    {
        // Pastikan user yang login adalah pemilik pemesanan
        if ($pemesanan->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Pastikan belum dibayar
        if ($pemesanan->status == 'disetujui') {
            return redirect()->route('rental.show', $pemesanan->id)
                ->with('info', 'Pesanan ini sudah dibayar');
        }

        // Setup Midtrans Config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Generate Snap Token
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $pemesanan->id,
                'gross_amount' => $pemesanan->total_harga,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $pemesanan->id,
                    'price' => $pemesanan->total_harga,
                    'quantity' => 1,
                    'name' => 'Rental Mobil - Order #' . $pemesanan->id,
                ]
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return view('user.payment', compact('snapToken', 'pemesanan'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat payment token: ' . $e->getMessage());
        }
    }

    /**
     * Halaman sukses pembayaran (redirect dari Midtrans)
     */
    public function paymentSuccess(Pemesanan $pemesanan)
    {
        // Pastikan user yang login adalah pemilik pemesanan
        if ($pemesanan->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('user.payment_success', compact('pemesanan'));
    }

    /**
     * Halaman gagal pembayaran (redirect dari Midtrans)
     */
    public function paymentFailed(Pemesanan $pemesanan)
    {
        // Pastikan user yang login adalah pemilik pemesanan
        if ($pemesanan->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('user.payment_failed', compact('pemesanan'));
    }
}
