<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pembayaran')->insert([
            ['pemesanan_id' => 1, 'metode_pembayaran' => 'Transfer Bank', 'tanggal_bayar' => '2026-01-10', 'jumlah_bayar' => 1600000, 'status' => 'valid'],
            ['pemesanan_id' => 2, 'metode_pembayaran' => 'E-Wallet', 'tanggal_bayar' => '2026-01-11', 'jumlah_bayar' => 700000, 'status' => 'valid'],
            ['pemesanan_id' => 3, 'metode_pembayaran' => 'Kartu Kredit', 'tanggal_bayar' => '2026-01-12', 'jumlah_bayar' => 1400000, 'status' => 'valid'],
            ['pemesanan_id' => 4, 'metode_pembayaran' => 'Tunai', 'tanggal_bayar' => '2026-01-13', 'jumlah_bayar' => 300000, 'status' => 'valid'],
            ['pemesanan_id' => 5, 'metode_pembayaran' => 'Transfer Bank', 'tanggal_bayar' => '2026-01-14', 'jumlah_bayar' => 250000, 'status' => 'valid'],
            ['pemesanan_id' => 6, 'metode_pembayaran' => 'E-Wallet', 'tanggal_bayar' => '2026-01-15', 'jumlah_bayar' => 1700000, 'status' => 'valid'],
            ['pemesanan_id' => 7, 'metode_pembayaran' => 'QRIS', 'tanggal_bayar' => '2026-01-16', 'jumlah_bayar' => 800000, 'status' => 'valid'],
            ['pemesanan_id' => 8, 'metode_pembayaran' => 'Transfer Bank', 'tanggal_bayar' => '2026-01-17', 'jumlah_bayar' => 2400000, 'status' => 'valid'],
            ['pemesanan_id' => 9, 'metode_pembayaran' => 'E-Wallet', 'tanggal_bayar' => '2026-01-18', 'jumlah_bayar' => 1500000, 'status' => 'menunggu'],
            ['pemesanan_id' => 10, 'metode_pembayaran' => 'Kartu Kredit', 'tanggal_bayar' => '2026-01-18', 'jumlah_bayar' => 2200000, 'status' => 'menunggu'],
            ['pemesanan_id' => 11, 'metode_pembayaran' => 'Tunai', 'tanggal_bayar' => '2026-01-19', 'jumlah_bayar' => 1000000, 'status' => 'menunggu'],
            ['pemesanan_id' => 12, 'metode_pembayaran' => 'QRIS', 'tanggal_bayar' => '2026-01-19', 'jumlah_bayar' => 700000, 'status' => 'menunggu'],
            ['pemesanan_id' => 13, 'metode_pembayaran' => 'Transfer Bank', 'tanggal_bayar' => '2026-01-20', 'jumlah_bayar' => 1500000, 'status' => 'menunggu'],
            ['pemesanan_id' => 14, 'metode_pembayaran' => 'E-Wallet', 'tanggal_bayar' => '2026-01-20', 'jumlah_bayar' => 3600000, 'status' => 'menunggu'],
            ['pemesanan_id' => 15, 'metode_pembayaran' => 'QRIS', 'tanggal_bayar' => '2026-01-21', 'jumlah_bayar' => 1400000, 'status' => 'menunggu'],
        ]);

    }
}
