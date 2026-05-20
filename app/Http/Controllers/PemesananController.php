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
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Log;



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
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        if ($pemesanan->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak mengakses pembayaran ini.');
        }

        if ($pemesanan->hasValidPayment()) {
            return redirect()
                ->route('user.riwayat_pesanan')
                ->with('info', 'Pesanan ini sudah dibayar.');
        }

        if (!in_array($pemesanan->status, ['pending', 'disetujui'])) {
            return redirect()
                ->route('user.riwayat_pesanan')
                ->with('error', 'Pemesanan tidak dapat dibayar pada status saat ini.');
        }

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Bypass SSL verification to prevent Windows local host cURL cert issues
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [],
        ];

        // Validasi konfigurasi
        if (empty(Config::$serverKey) || empty(Config::$clientKey)) {
            Log::error('Midtrans configuration missing', [
                'server_key_empty' => empty(Config::$serverKey),
                'client_key_empty' => empty(Config::$clientKey),
            ]);
        }

        $orderId = 'ORDER-' . $pemesanan->id . '-' . time();
        session(['midtrans_order_id_' . $pemesanan->id => $orderId]);

        $snapToken = null;
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) round((float) $pemesanan->total_harga),
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
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

        Log::debug('Midtrans Snap Token Request', [
            'params' => $params,
            'server_key_present' => !empty(Config::$serverKey),
            'is_production' => Config::$isProduction,
        ]);

        try {
            $snapToken = Snap::getSnapToken($params);
            Log::info('Midtrans Snap Token Generated Successfully', [
                'pemesanan_id' => $pemesanan->id,
                'order_id' => 'ORDER-' . $pemesanan->id,
                'gross_amount' => $pemesanan->total_harga,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Generation Failed', [
                'pemesanan_id' => $pemesanan->id,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
            // Midtrans opsional; tunai tetap tersedia
        }

        return view('user.payment', compact('snapToken', 'pemesanan'));
    }

    /**
     * Pembayaran tunai oleh customer - Mengajukan proses pengecekan uang ke kasir.
     */
    public function payCash(Request $request, Pemesanan $pemesanan)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        if ($pemesanan->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak mengakses pembayaran ini.');
        }

        if ($pemesanan->hasValidPayment()) {
            return redirect()
                ->route('pemesanan.payment', $pemesanan)
                ->with('info', 'Pemesanan ini sudah lunas.');
        }

        // Buat record pembayaran dengan status 'menunggu' untuk diverifikasi petugas
        \App\Models\Pembayaran::updateOrCreate(
            ['pemesanan_id' => $pemesanan->id],
            [
                'metode_pembayaran' => 'Tunai',
                'tanggal_bayar'     => now()->toDateString(),
                'jumlah_bayar'      => $pemesanan->total_harga,
                'status'            => 'menunggu',
                'bukti_bayar'       => null,
                'uang_diterima'     => null,
                'kembalian'         => null,
            ]
        );

        return redirect()
            ->route('pemesanan.payment', $pemesanan)
            ->with('success', 'Permintaan pembayaran tunai berhasil diajukan. Status berubah menjadi proses pengecekan oleh kasir.');
    }

    /**
     * Halaman sukses pembayaran (redirect dari Midtrans).
     * Karena webhook Midtrans tidak bisa menjangkau localhost,
     * kita polling langsung ke API Midtrans untuk konfirmasi status.
     */
    public function paymentSuccess(Pemesanan $pemesanan)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($pemesanan->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        // Jika pembayaran belum valid, coba konfirmasi langsung ke Midtrans
        if (!$pemesanan->hasValidPayment()) {
            try {
                // Setup Midtrans config
                Config::$serverKey     = config('midtrans.server_key');
                Config::$isProduction  = config('midtrans.is_production');
                Config::$curlOptions   = [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_HTTPHEADER     => [],
                ];

                // Gunakan order_id yang disimpan di session saat generate Snap Token
                $orderId = session('midtrans_order_id_' . $pemesanan->id, 'ORDER-' . $pemesanan->id);

                $statusResponse = Transaction::status($orderId);

                $transactionStatus = $statusResponse->transaction_status ?? null;
                $grossAmount       = (int) ($statusResponse->gross_amount ?? $pemesanan->total_harga);
                $paymentType       = $statusResponse->payment_type ?? 'midtrans';

                $metodeMap = [
                    'credit_card'   => 'Kartu Kredit',
                    'bank_transfer' => 'Transfer Bank',
                    'gopay'         => 'GoPay',
                    'qris'          => 'QRIS',
                    'ovo'           => 'OVO',
                    'shopeepay'     => 'ShopeePay',
                    'dana'          => 'DANA',
                    'cstore'        => 'Convenience Store',
                    'echannel'      => 'Mandiri Bill',
                ];
                $metodeName = $metodeMap[$paymentType] ?? ucfirst($paymentType);

                if (in_array($transactionStatus, ['settlement', 'capture'])) {
                    // Update / buat record pembayaran
                    \App\Models\Pembayaran::updateOrCreate(
                        ['pemesanan_id' => $pemesanan->id],
                        [
                            'metode_pembayaran' => $metodeName,
                            'tanggal_bayar'     => now()->toDateString(),
                            'jumlah_bayar'      => $grossAmount,
                            'status'            => 'valid',
                        ]
                    );

                    // Update status pemesanan & mobil
                    $pemesanan->update(['status' => 'disetujui']);
                    $pemesanan->load('details.mobil');
                    foreach ($pemesanan->details as $detail) {
                        if ($detail->mobil) {
                            $detail->mobil->update(['status' => 'disewa']);
                        }
                    }

                    Log::info('paymentSuccess: status dikonfirmasi via Midtrans API', [
                        'pemesanan_id'      => $pemesanan->id,
                        'transaction_status' => $transactionStatus,
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('paymentSuccess: gagal polling Midtrans API', [
                    'pemesanan_id' => $pemesanan->id,
                    'error'        => $e->getMessage(),
                ]);
            }
        }

        // Reload fresh dari database agar view menampilkan status terbaru
        $pemesanan->refresh();

        return view('user.payment_success', compact('pemesanan'));
    }

    /**
     * Halaman gagal pembayaran (redirect dari Midtrans)
     */
    public function paymentFailed(Pemesanan $pemesanan)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        // Pastikan user yang login adalah pemilik pemesanan
        if ($pemesanan->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('user.payment_failed', compact('pemesanan'));
    }
}
