<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('denda')->insert([
            ['pengembalian_id' => 1, 'jenis_denda' => null, 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 0, 'keterangan' => 'Tidak ada denda'],
            ['pengembalian_id' => 2, 'jenis_denda' => null, 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 0, 'keterangan' => 'Tidak ada denda'],
            ['pengembalian_id' => 3, 'jenis_denda' => 'telat', 'jumlah_hari_terlambat' => 1, 'tarif_denda_per_hari' => 100000, 'total_denda' => 100000, 'keterangan' => 'Terlambat 1 hari'],
            ['pengembalian_id' => 4, 'jenis_denda' => null, 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 0, 'keterangan' => 'Tidak ada denda'],
            ['pengembalian_id' => 5, 'jenis_denda' => null, 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 0, 'keterangan' => 'Tidak ada denda'],
            ['pengembalian_id' => 6, 'jenis_denda' => null, 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 0, 'keterangan' => 'Tidak ada denda'],
            ['pengembalian_id' => 7, 'jenis_denda' => 'telat', 'jumlah_hari_terlambat' => 1, 'tarif_denda_per_hari' => 100000, 'total_denda' => 100000, 'keterangan' => 'Terlambat 1 hari'],
            ['pengembalian_id' => 8, 'jenis_denda' => null, 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 0, 'keterangan' => 'Tidak ada denda'],
            ['pengembalian_id' => 9, 'jenis_denda' => null, 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 0, 'keterangan' => 'Belum kembali'],
            ['pengembalian_id' => 10, 'jenis_denda' => null, 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 0, 'keterangan' => 'Belum kembali'],
            ['pengembalian_id' => 11, 'jenis_denda' => 'masalah_unit', 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 500000, 'keterangan' => 'Penyok pintu samping'],
            ['pengembalian_id' => 12, 'jenis_denda' => null, 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 0, 'keterangan' => 'Belum kembali'],
            ['pengembalian_id' => 13, 'jenis_denda' => null, 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 0, 'keterangan' => 'Belum kembali'],
            ['pengembalian_id' => 14, 'jenis_denda' => 'masalah_unit', 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 1500000, 'keterangan' => 'Kaca depan pecah'],
            ['pengembalian_id' => 15, 'jenis_denda' => null, 'jumlah_hari_terlambat' => 0, 'tarif_denda_per_hari' => 0, 'total_denda' => 0, 'keterangan' => 'Belum kembali'],
        ]);
    }
}
