<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessCashPaymentRequest;
use App\Models\Pemesanan;
use App\Models\Car as Mobil;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use InvalidArgumentException;
use Midtrans\Snap;
use Midtrans\Config;


class PemesananController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}
    public function index()
    {
        $pemesanan = Pemesanan::with([
            'user',
            'details.mobil',
            'pembayaran',
            'kontrak',
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
     * Halaman payment (Step 3): Midtrans atau tunai — hanya setelah disetujui petugas.
     */
    public function payment(Pemesanan $pemesanan)
    {
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses pembayaran ini.');
        }

        if ($pemesanan->status === 'pending') {
            return redirect()
                ->route('user.riwayat_pesanan')
                ->with('warning', 'Pembayaran dapat dilakukan setelah pemesanan disetujui petugas.');
        }

        if ($pemesanan->hasValidPayment()) {
            return redirect()
                ->route('user.riwayat_pesanan')
                ->with('info', 'Pesanan ini sudah dibayar.');
        }

        if (!$pemesanan->canAcceptPayment()) {
            return redirect()
                ->route('user.riwayat_pesanan')
                ->with('error', 'Pemesanan tidak dapat dibayar pada status saat ini.');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $snapToken = null;
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $pemesanan->id,
                'gross_amount' => (int) round((float) $pemesanan->total_harga),
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $pemesanan->id,
                    'price' => (int) round((float) $pemesanan->total_harga),
                    'quantity' => 1,
                    'name' => 'Rental Mobil - Order #' . $pemesanan->id,
                ],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            // Midtrans opsional; tunai tetap tersedia
        }

        return view('user.payment', compact('snapToken', 'pemesanan'));
    }

    /**
     * Pembayaran tunai oleh customer (validasi server-side).
     */
    public function payCash(ProcessCashPaymentRequest $request, Pemesanan $pemesanan)
    {
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak melakukan pembayaran ini.');
        }

        try {
            $pembayaran = $this->paymentService->processCashPayment(
                $pemesanan,
                (float) $request->uang_diterima
            );

            return redirect()
                ->route('pemesanan.success', $pemesanan)
                ->with('success', 'Pembayaran tunai berhasil. Kembalian: Rp ' . number_format($pembayaran->kembalian, 0, ',', '.'));
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage())->withInput();
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
