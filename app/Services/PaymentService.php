<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PaymentService
{
    /**
     * Proses pembayaran tunai (POS) dengan validasi server-side.
     * Total tagihan SELALU diambil dari database, bukan dari input client.
     */
    public function processCashPayment(
        Pemesanan $pemesanan,
        float $uangDiterima,
        ?int $petugasId = null
    ): Pembayaran {
        return DB::transaction(function () use ($pemesanan, $uangDiterima, $petugasId) {
            $pemesanan = Pemesanan::whereKey($pemesanan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$pemesanan->canAcceptPayment()) {
                throw new InvalidArgumentException(
                    'Pemesanan tidak dapat dibayar. Pastikan sudah disetujui petugas dan belum lunas.'
                );
            }

            $total = round((float) $pemesanan->total_harga, 2);
            $uangDiterima = round($uangDiterima, 2);

            if ($uangDiterima < $total) {
                $kurang = $total - $uangDiterima;
                throw new InvalidArgumentException(
                    'Uang tunai tidak mencukupi. Kurang Rp ' . number_format($kurang, 0, ',', '.')
                );
            }

            $kembalian = round($uangDiterima - $total, 2);

            $pembayaran = Pembayaran::updateOrCreate(
                ['pemesanan_id' => $pemesanan->id],
                [
                    'metode_pembayaran' => 'Tunai',
                    'tanggal_bayar'     => now()->toDateString(),
                    'jumlah_bayar'      => $total,
                    'uang_diterima'     => $uangDiterima,
                    'kembalian'         => $kembalian,
                    'petugas_id'        => $petugasId,
                    'status'            => 'valid',
                ]
            );

            return $pembayaran;
        });
    }
}
