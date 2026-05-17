<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pemesanan')->insert([
            ['user_id' => 2, 'tanggal_pesan' => '2026-01-10', 'tanggal_mulai' => '2026-01-11', 'tanggal_selesai' => '2026-01-13', 'total_harga' => 1600000, 'status' => 'selesai'],
            ['user_id' => 4, 'tanggal_pesan' => '2026-01-11', 'tanggal_mulai' => '2026-01-12', 'tanggal_selesai' => '2026-01-14', 'total_harga' => 700000, 'status' => 'selesai'],
            ['user_id' => 6, 'tanggal_pesan' => '2026-01-12', 'tanggal_mulai' => '2026-01-13', 'tanggal_selesai' => '2026-01-15', 'total_harga' => 1400000, 'status' => 'selesai'],
            ['user_id' => 8, 'tanggal_pesan' => '2026-01-13', 'tanggal_mulai' => '2026-01-14', 'tanggal_selesai' => '2026-01-15', 'total_harga' => 300000, 'status' => 'selesai'],
            ['user_id' => 10, 'tanggal_pesan' => '2026-01-14', 'tanggal_mulai' => '2026-01-15', 'tanggal_selesai' => '2026-01-16', 'total_harga' => 250000, 'status' => 'selesai'],
            ['user_id' => 12, 'tanggal_pesan' => '2026-01-15', 'tanggal_mulai' => '2026-01-16', 'tanggal_selesai' => '2026-01-18', 'total_harga' => 1700000, 'status' => 'disetujui'],
            ['user_id' => 14, 'tanggal_pesan' => '2026-01-16', 'tanggal_mulai' => '2026-01-17', 'tanggal_selesai' => '2026-01-19', 'total_harga' => 800000, 'status' => 'disetujui'],
            ['user_id' => 2, 'tanggal_pesan' => '2026-01-17', 'tanggal_mulai' => '2026-01-18', 'tanggal_selesai' => '2026-01-20', 'total_harga' => 2400000, 'status' => 'disetujui'],
            ['user_id' => 4, 'tanggal_pesan' => '2026-01-18', 'tanggal_mulai' => '2026-01-19', 'tanggal_selesai' => '2026-01-21', 'total_harga' => 1500000, 'status' => 'pending'],
            ['user_id' => 6, 'tanggal_pesan' => '2026-01-18', 'tanggal_mulai' => '2026-01-20', 'tanggal_selesai' => '2026-01-22', 'total_harga' => 2200000, 'status' => 'pending'],
            ['user_id' => 8, 'tanggal_pesan' => '2026-01-19', 'tanggal_mulai' => '2026-01-21', 'tanggal_selesai' => '2026-01-23', 'total_harga' => 1000000, 'status' => 'pending'],
            ['user_id' => 10, 'tanggal_pesan' => '2026-01-19', 'tanggal_mulai' => '2026-01-22', 'tanggal_selesai' => '2026-01-24', 'total_harga' => 700000, 'status' => 'pending'],
            ['user_id' => 12, 'tanggal_pesan' => '2026-01-20', 'tanggal_mulai' => '2026-01-23', 'tanggal_selesai' => '2026-01-25', 'total_harga' => 1500000, 'status' => 'pending'],
            ['user_id' => 14, 'tanggal_pesan' => '2026-01-20', 'tanggal_mulai' => '2026-01-24', 'tanggal_selesai' => '2026-01-26', 'total_harga' => 3600000, 'status' => 'pending'],
            ['user_id' => 2, 'tanggal_pesan' => '2026-01-21', 'tanggal_mulai' => '2026-01-25', 'tanggal_selesai' => '2026-01-27', 'total_harga' => 1400000, 'status' => 'pending'],
        ]);

    }
}
