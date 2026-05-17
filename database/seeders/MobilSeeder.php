<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mobil')->insert([
            [
                'kategori_id' => 1,
                'merk' => 'Toyota',
                'model' => 'Fortuner 2024',
                'tahun' => 2024,
                'nomor_plat' => 'B 1234 ABC',
                'harga_sewa' => 800000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 2,
                'merk' => 'Toyota',
                'model' => 'Avanza 2023',
                'tahun' => 2023,
                'nomor_plat' => 'D 5678 EFG',
                'harga_sewa' => 350000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 3,
                'merk' => 'Honda',
                'model' => 'Civic Turbo',
                'tahun' => 2022,
                'nomor_plat' => 'L 9012 HIJ',
                'harga_sewa' => 700000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 4,
                'merk' => 'Mitsubishi',
                'model' => 'L300 Pickup',
                'tahun' => 2021,
                'nomor_plat' => 'F 3456 KLM',
                'harga_sewa' => 300000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 5,
                'merk' => 'Honda',
                'model' => 'Brio RS',
                'tahun' => 2023,
                'nomor_plat' => 'B 7890 NOP',
                'harga_sewa' => 250000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 1,
                'merk' => 'Mitsubishi',
                'model' => 'Pajero Sport',
                'tahun' => 2024,
                'nomor_plat' => 'B 1122 QRS',
                'harga_sewa' => 850000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 2,
                'merk' => 'Suzuki',
                'model' => 'Ertiga Hybrid',
                'tahun' => 2023,
                'nomor_plat' => 'D 3344 TUV',
                'harga_sewa' => 400000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 6,
                'merk' => 'Mazda',
                'model' => 'MX-5 Miata',
                'tahun' => 2022,
                'nomor_plat' => 'L 5566 WXY',
                'harga_sewa' => 1200000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 8,
                'merk' => 'BMW',
                'model' => '320i Sport',
                'tahun' => 2024,
                'nomor_plat' => 'B 7788 ZAA',
                'harga_sewa' => 2500000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 10,
                'merk' => 'Hyundai',
                'model' => 'Ioniq 5',
                'tahun' => 2023,
                'nomor_plat' => 'B 9900 BCA',
                'harga_sewa' => 1500000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 12,
                'merk' => 'Toyota',
                'model' => 'Hiace Premio',
                'tahun' => 2022,
                'nomor_plat' => 'D 2211 CBA',
                'harga_sewa' => 1100000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 1,
                'merk' => 'Wuling',
                'model' => 'Almaz RS',
                'tahun' => 2023,
                'nomor_plat' => 'L 4433 ABC',
                'harga_sewa' => 500000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 2,
                'merk' => 'Daihatsu',
                'model' => 'Xenia 1.5',
                'tahun' => 2023,
                'nomor_plat' => 'F 6655 DEF',
                'harga_sewa' => 350000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 1,
                'merk' => 'Nissan',
                'model' => 'Terra VL',
                'tahun' => 2022,
                'nomor_plat' => 'B 8877 GHI',
                'harga_sewa' => 750000,
                'status' => 'tersedia'
            ],
            [
                'kategori_id' => 3,
                'merk' => 'Toyota',
                'model' => 'Camry Hybrid',
                'tahun' => 2024,
                'nomor_plat' => 'B 4411 JKL',
                'harga_sewa' => 1800000,
                'status' => 'tersedia'
            ],
        ]);
    }
}
