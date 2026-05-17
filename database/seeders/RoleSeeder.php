<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nama_role' => 'Super Admin', 'deskripsi' => 'Akses penuh ke semua fitur'],
            ['nama_role' => 'Admin', 'deskripsi' => 'Manajemen data kendaraan dan pengguna'],
            ['nama_role' => 'Customer', 'deskripsi' => 'Penyewa mobil'],
            ['nama_role' => 'Petugas', 'deskripsi' => 'Validasi pesanan dan pengembalian'],
            ['nama_role' => 'Manager', 'deskripsi' => 'Melihat laporan pendapatan'],
            ['nama_role' => 'Operator', 'deskripsi' => 'Input data harian'],
            ['nama_role' => 'Marketing', 'deskripsi' => 'Kelola promo dan katalog'],
            ['nama_role' => 'Finance', 'deskripsi' => 'Validasi pembayaran'],
            ['nama_role' => 'Logistik', 'deskripsi' => 'Cek kondisi unit'],
            ['nama_role' => 'CS', 'deskripsi' => 'Customer Service'],
            ['nama_role' => 'HRD', 'deskripsi' => 'Kelola data karyawan'],
            ['nama_role' => 'IT Support', 'deskripsi' => 'Maintenance sistem'],
            ['nama_role' => 'Security', 'deskripsi' => 'Keamanan area parkir'],
            ['nama_role' => 'Driver', 'deskripsi' => 'Pengemudi sewaan'],
            ['nama_role' => 'Guest', 'deskripsi' => 'Akses terbatas'],
        ]);

    }
}
