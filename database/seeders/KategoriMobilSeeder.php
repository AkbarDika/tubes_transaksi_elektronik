<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriMobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori_mobil')->insert([
            ['nama_kategori' => 'SUV', 'deskripsi' => 'Sport Utility Vehicle'],
            ['nama_kategori' => 'MPV', 'deskripsi' => 'Multi Purpose Vehicle'],
            ['nama_kategori' => 'Sedan', 'deskripsi' => 'Mobil Mewah'],
            ['nama_kategori' => 'Pickup', 'deskripsi' => 'Mobil Angkutan'],
            ['nama_kategori' => 'Hatchback', 'deskripsi' => 'Mobil City'],
            ['nama_kategori' => 'Coupe', 'deskripsi' => 'Mobil Sport'],
            ['nama_kategori' => 'Convertible', 'deskripsi' => 'Atap Terbuka'],
            ['nama_kategori' => 'Luxury', 'deskripsi' => 'Kelas Atas'],
            ['nama_kategori' => 'Offroad', 'deskripsi' => 'Medan Berat'],
            ['nama_kategori' => 'Electric', 'deskripsi' => 'Mobil Listrik'],
            ['nama_kategori' => 'Hybrid', 'deskripsi' => 'Dua Mesin'],
            ['nama_kategori' => 'Van', 'deskripsi' => 'Kapasitas Besar'],
            ['nama_kategori' => 'Compact SUV', 'deskripsi' => 'SUV Kecil'],
            ['nama_kategori' => 'Micro', 'deskripsi' => 'Mungil'],
            ['nama_kategori' => 'Station Wagon', 'deskripsi' => 'Atap Panjang'],
        ]);

    }
}
