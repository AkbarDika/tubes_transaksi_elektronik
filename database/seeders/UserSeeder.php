<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'role_id' => 1,
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'email' => 'superadmin@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Jakarta Selatan',
                'no_hp' => '0811111111'
            ],
            [
                'role_id' => 3,
                'name' => 'Budi Santoso',
                'username' => 'budi',
                'email' => 'budi@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Bandung',
                'no_hp' => '0822222222'
            ],
            [
                'role_id' => 4,
                'name' => 'Petugas Ahmad',
                'username' => 'ahmad',
                'email' => 'ahmad@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Surabaya',
                'no_hp' => '0833333333'
            ],
            [
                'role_id' => 3,
                'name' => 'Siti Aminah',
                'username' => 'siti',
                'email' => 'siti@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Yogyakarta',
                'no_hp' => '0844444444'
            ],
            [
                'role_id' => 2,
                'name' => 'Admin Rina',
                'username' => 'rina',
                'email' => 'rina@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Semarang',
                'no_hp' => '0855555555'
            ],
            [
                'role_id' => 3,
                'name' => 'Iwan Fals',
                'username' => 'iwan',
                'email' => 'iwan@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Malang',
                'no_hp' => '0866666666'
            ],
            [
                'role_id' => 5,
                'name' => 'Manager Rudi',
                'username' => 'rudi_man',
                'email' => 'rudi@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Bali',
                'no_hp' => '0877777777'
            ],
            [
                'role_id' => 3,
                'name' => 'Dewi Persik',
                'username' => 'dewi',
                'email' => 'dewi@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Medan',
                'no_hp' => '0888888888'
            ],
            [
                'role_id' => 8,
                'name' => 'Finance Eko',
                'username' => 'eko_fin',
                'email' => 'eko@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Palembang',
                'no_hp' => '0899999999'
            ],
            [
                'role_id' => 3,
                'name' => 'Jokowi Widodo',
                'username' => 'jokowi',
                'email' => 'jokowi@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Solo',
                'no_hp' => '0812121212'
            ],
            [
                'role_id' => 4,
                'name' => 'Petugas Sari',
                'username' => 'sari',
                'email' => 'sari@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Makassar',
                'no_hp' => '0813131313'
            ],
            [
                'role_id' => 3,
                'name' => 'Prabowo Subianto',
                'username' => 'prabowo',
                'email' => 'prabowo@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Bogor',
                'no_hp' => '0814141414'
            ],
            [
                'role_id' => 10,
                'name' => 'CS Maya',
                'username' => 'maya_cs',
                'email' => 'maya@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Depok',
                'no_hp' => '0815151515'
            ],
            [
                'role_id' => 3,
                'name' => 'Anies Baswedan',
                'username' => 'anies',
                'email' => 'anies@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Jakarta Utara',
                'no_hp' => '0816161616'
            ],
            [
                'role_id' => 14,
                'name' => 'Driver Bambang',
                'username' => 'bambang_dr',
                'email' => 'bambang@mail.com',
                'password' => Hash::make('password'),
                'alamat' => 'Tangerang',
                'no_hp' => '0817171717'
            ],
        ]);

    }
}
