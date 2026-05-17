<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengembalianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengembalian')->insert([
            ['pemesanan_id' => 1, 'tanggal_kembali' => '2026-01-13', 'kondisi_mobil' => 'Bagus', 'catatan' => 'Sesuai jadwal', 'status_pengembalian' => 'selesai'],
            ['pemesanan_id' => 2, 'tanggal_kembali' => '2026-01-14', 'kondisi_mobil' => 'Bagus', 'catatan' => 'Sesuai jadwal', 'status_pengembalian' => 'selesai'],
            ['pemesanan_id' => 3, 'tanggal_kembali' => '2026-01-16', 'kondisi_mobil' => 'Lecet sedikit', 'catatan' => 'Terlambat 1 hari', 'status_pengembalian' => 'bermasalah'],
            ['pemesanan_id' => 4, 'tanggal_kembali' => '2026-01-15', 'kondisi_mobil' => 'Bagus', 'catatan' => 'Sesuai jadwal', 'status_pengembalian' => 'selesai'],
            ['pemesanan_id' => 5, 'tanggal_kembali' => '2026-01-16', 'kondisi_mobil' => 'Bagus', 'catatan' => 'Sesuai jadwal', 'status_pengembalian' => 'selesai'],
            ['pemesanan_id' => 6, 'tanggal_kembali' => '2026-01-18', 'kondisi_mobil' => 'Bagus', 'catatan' => 'Sesuai jadwal', 'status_pengembalian' => 'selesai'],
            ['pemesanan_id' => 7, 'tanggal_kembali' => '2026-01-20', 'kondisi_mobil' => 'Kotor', 'catatan' => 'Terlambat 1 hari', 'status_pengembalian' => 'bermasalah'],
            ['pemesanan_id' => 8, 'tanggal_kembali' => '2026-01-20', 'kondisi_mobil' => 'Bagus', 'catatan' => 'Sesuai jadwal', 'status_pengembalian' => 'selesai'],
            ['pemesanan_id' => 9, 'tanggal_kembali' => '2026-01-22', 'kondisi_mobil' => 'Bagus', 'catatan' => 'Sesuai jadwal', 'status_pengembalian' => 'pending'],
            ['pemesanan_id' => 10, 'tanggal_kembali' => '2026-01-23', 'kondisi_mobil' => 'Bagus', 'catatan' => 'Sesuai jadwal', 'status_pengembalian' => 'pending'],
            ['pemesanan_id' => 11, 'tanggal_kembali' => '2026-01-24', 'kondisi_mobil' => 'Penyok pintu', 'catatan' => 'Masalah unit', 'status_pengembalian' => 'bermasalah'],
            ['pemesanan_id' => 12, 'tanggal_kembali' => '2026-01-25', 'kondisi_mobil' => 'Bagus', 'catatan' => 'Sesuai jadwal', 'status_pengembalian' => 'pending'],
            ['pemesanan_id' => 13, 'tanggal_kembali' => '2026-01-26', 'kondisi_mobil' => 'Bagus', 'catatan' => 'Sesuai jadwal', 'status_pengembalian' => 'pending'],
            ['pemesanan_id' => 14, 'tanggal_kembali' => '2026-01-28', 'kondisi_mobil' => 'Kaca pecah', 'catatan' => 'Masalah unit', 'status_pengembalian' => 'bermasalah'],
            ['pemesanan_id' => 15, 'tanggal_kembali' => '2026-01-28', 'kondisi_mobil' => 'Bagus', 'catatan' => 'Sesuai jadwal', 'status_pengembalian' => 'pending'],
        ]);
    }
}
