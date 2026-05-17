<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPemesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('detail_pemesanan')->insert([
            ['pemesanan_id' => 1, 'mobil_id' => 1, 'lama_sewa' => 2, 'harga_per_hari' => 800000, 'subtotal' => 1600000],
            ['pemesanan_id' => 2, 'mobil_id' => 2, 'lama_sewa' => 2, 'harga_per_hari' => 350000, 'subtotal' => 700000],
            ['pemesanan_id' => 3, 'mobil_id' => 3, 'lama_sewa' => 2, 'harga_per_hari' => 700000, 'subtotal' => 1400000],
            ['pemesanan_id' => 4, 'mobil_id' => 4, 'lama_sewa' => 1, 'harga_per_hari' => 300000, 'subtotal' => 300000],
            ['pemesanan_id' => 5, 'mobil_id' => 5, 'lama_sewa' => 1, 'harga_per_hari' => 250000, 'subtotal' => 250000],
            ['pemesanan_id' => 6, 'mobil_id' => 6, 'lama_sewa' => 2, 'harga_per_hari' => 850000, 'subtotal' => 1700000],
            ['pemesanan_id' => 7, 'mobil_id' => 7, 'lama_sewa' => 2, 'harga_per_hari' => 400000, 'subtotal' => 800000],
            ['pemesanan_id' => 8, 'mobil_id' => 8, 'lama_sewa' => 2, 'harga_per_hari' => 1200000, 'subtotal' => 2400000],
            ['pemesanan_id' => 9, 'mobil_id' => 11, 'lama_sewa' => 3, 'harga_per_hari' => 500000, 'subtotal' => 1500000],
            ['pemesanan_id' => 10, 'mobil_id' => 12, 'lama_sewa' => 2, 'harga_per_hari' => 1100000, 'subtotal' => 2200000],
            ['pemesanan_id' => 11, 'mobil_id' => 13, 'lama_sewa' => 2, 'harga_per_hari' => 500000, 'subtotal' => 1000000],
            ['pemesanan_id' => 12, 'mobil_id' => 14, 'lama_sewa' => 2, 'harga_per_hari' => 350000, 'subtotal' => 700000],
            ['pemesanan_id' => 13, 'mobil_id' => 15, 'lama_sewa' => 2, 'harga_per_hari' => 750000, 'subtotal' => 1500000],
            ['pemesanan_id' => 14, 'mobil_id' => 16, 'lama_sewa' => 2, 'harga_per_hari' => 1800000, 'subtotal' => 3600000],
            ['pemesanan_id' => 15, 'mobil_id' => 3, 'lama_sewa' => 2, 'harga_per_hari' => 700000, 'subtotal' => 1400000],
        ]);

    }
}
