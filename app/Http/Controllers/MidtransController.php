<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function callback(Request $request)
    {
        try {
            Log::info('=== MIDTRANS CALLBACK RECEIVED ===');
            Log::info('Request method: ' . $request->method());
            Log::info('Request content type: ' . $request->header('Content-Type'));
            Log::info('Request all data: ' . json_encode($request->all()));

            // Setup config DULU sebelum Notification
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            // Parse data dari request (Midtrans mengirim via POST)
            // Bisa berupa form-urlencoded atau JSON
            $orderId = $request->input('order_id') ?? $request->get('order_id');
            $transactionStatus = $request->input('transaction_status') ?? $request->get('transaction_status');
            $paymentType = $request->input('payment_type') ?? $request->get('payment_type') ?? 'unknown';
            $grossAmount = $request->input('gross_amount') ?? $request->get('gross_amount');

            Log::info('Parsed callback data', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'gross_amount' => $grossAmount,
            ]);

            // Validasi data
            if (!$orderId || !$transactionStatus || !$grossAmount) {
                Log::error('Missing required callback data', [
                    'order_id' => $orderId,
                    'transaction_status' => $transactionStatus,
                    'gross_amount' => $grossAmount,
                ]);
                return response()->json(['status' => 'error', 'message' => 'Missing required data'], 400);
            }

            // Extract order ID (hapus prefix ORDER-)
            $orderId = str_replace('ORDER-', '', $orderId);

            // Cek apakah pemesanan ada
            $pemesanan = Pemesanan::find($orderId);
            if (!$pemesanan) {
                Log::error('Order not found: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Cek apakah pembayaran sudah ada
            $existingPayment = Pembayaran::where('pemesanan_id', $orderId)->first();

            // Jika pembayaran sudah sukses sebelumnya, jangan update lagi (idempotency)
            if ($existingPayment && $existingPayment->status == 'valid') {
                Log::info('Payment already verified for order: ' . $orderId);
                return response('ok', 200)->header('Content-Type', 'text/plain');
            }

            // Terjemahkan payment type
            $metodeMap = [
                'credit_card' => 'Kartu Kredit',
                'bank_transfer' => 'Transfer Bank',
                'cimb_clicks' => 'CIMB Clicks',
                'bca_clicks' => 'BCA Clicks',
                'mandiri_clickpay' => 'Mandiri ClickPay',
                'echannel' => 'Mandiri ECHANNEL',
                'permata_va' => 'Bank Permata VA',
                'bca_va' => 'BCA VA',
                'bni_va' => 'BNI VA',
                'cimb_va' => 'CIMB VA',
                'other_va' => 'Bank VA Lainnya',
                'gopay' => 'GoPay',
                'ovo' => 'OVO',
                'qris' => 'QRIS',
            ];

            $metodeDisplayName = $metodeMap[$paymentType] ?? ucwords(str_replace('_', ' ', $paymentType));

            // Siapkan data pembayaran untuk create/update
            $paymentData = [
                'pemesanan_id' => $orderId,
                'metode_pembayaran' => $metodeDisplayName,
                'tanggal_bayar' => now()->toDateString(),
                'jumlah_bayar' => $grossAmount,
            ];

            // Handle berbagai status transaksi
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                // Pembayaran berhasil
                $paymentData['status'] = 'valid';
                
                // Update status pemesanan
                $pemesanan->update(['status' => 'pending']);
                
                Log::info('✅ Payment SUCCESSFUL for order: ' . $orderId);
            } elseif ($transactionStatus == 'pending') {
                // Pembayaran masih pending
                $paymentData['status'] = 'menunggu';
                
                Log::info('⏳ Payment PENDING for order: ' . $orderId);
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'cancel' || $transactionStatus == 'expire') {
                // Pembayaran gagal/dibatalkan
                $paymentData['status'] = 'ditolak';
                
                // Update status pemesanan
                $pemesanan->update(['status' => 'ditolak']);

                Log::warning('❌ Payment FAILED/CANCELLED for order: ' . $orderId);
            }

            // Simpan atau Update
            if (isset($paymentData['status'])) {
                if ($existingPayment) {
                    $existingPayment->update($paymentData);
                } else {
                    Pembayaran::create($paymentData);
                }
            }

            Log::info('=== CALLBACK PROCESSED SUCCESSFULLY ===');
            // Midtrans expects plain text "ok" response
            return response('ok', 200)
                ->header('Content-Type', 'text/plain');

        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage(), [
                'exception' => $e,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all(),
            ]);
            // Return ok anyway so Midtrans stops retrying
            return response('ok', 200)
                ->header('Content-Type', 'text/plain');
        }
    }
}

