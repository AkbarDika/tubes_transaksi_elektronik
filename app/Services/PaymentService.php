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
        ?int $petugasId = null,
        string $status = 'valid',
        ?string $buktiBayar = null
    ): Pembayaran {
        return DB::transaction(function () use ($pemesanan, $uangDiterima, $petugasId, $status, $buktiBayar) {
            $pemesanan = Pemesanan::whereKey($pemesanan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($status === 'ditolak') {
                $pembayaran = Pembayaran::updateOrCreate(
                    ['pemesanan_id' => $pemesanan->id],
                    [
                        'metode_pembayaran' => 'Tunai',
                        'tanggal_bayar'     => now()->toDateString(),
                        'jumlah_bayar'      => 0,
                        'uang_diterima'     => 0,
                        'kembalian'         => 0,
                        'bukti_bayar'       => $buktiBayar,
                        'petugas_id'        => $petugasId,
                        'status'            => 'ditolak',
                    ]
                );
                return $pembayaran;
            }

            if (!$pemesanan->canAcceptPayment() && ($pemesanan->pembayaran && $pemesanan->pembayaran->status !== 'menunggu')) {
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
                    'bukti_bayar'       => $buktiBayar ?: ($pemesanan->pembayaran ? $pemesanan->pembayaran->bukti_bayar : null),
                    'petugas_id'        => $petugasId,
                    'status'            => 'valid',
                ]
            );

            return $pembayaran;
        });
    }
}
