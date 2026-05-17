<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KontrakSeeder extends Seeder
{
    /**
     * Buat kontrak untuk pemesanan yang sudah ada di DB.
     * Mengambil ID secara dinamis agar tidak tergantung urutan.
     */
    public function run(): void
    {
        // Ambil pemesanan yang sudah dibayar (ada di tabel pembayaran dengan status valid)
        $pemesananIds = DB::table('pemesanan')
            ->join('pembayaran', 'pemesanan.id', '=', 'pembayaran.pemesanan_id')
            ->where('pembayaran.status', 'valid')
            ->orderBy('pemesanan.id')
            ->pluck('pemesanan.id')
            ->toArray();

        if (empty($pemesananIds)) {
            $this->command->warn('Tidak ada pemesanan dengan pembayaran valid. KontrakSeeder dilewati.');
            return;
        }

        $statuses   = ['selesai', 'selesai', 'selesai', 'selesai', 'aktif', 'aktif', 'aktif', 'aktif'];
        $catatan    = [null, null, null, null, null, null, null, 'Mobil diperiksa kondisi sebelum diserahkan.'];

        $rows = [];
        foreach ($pemesananIds as $i => $pid) {
            // Ambil data tanggal dari tabel pemesanan
            $p = DB::table('pemesanan')->where('id', $pid)->first();

            $rows[] = [
                'pemesanan_id'    => $pid,
                'nomor_kontrak'   => 'KTR-' . date('Ymd', strtotime($p->tanggal_pesan ?? 'now')) . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'tanggal_kontrak' => $p->tanggal_pesan ? date('Y-m-d', strtotime($p->tanggal_pesan)) : now()->toDateString(),
                'tanggal_mulai'   => $p->tanggal_mulai,
                'tanggal_selesai' => $p->tanggal_selesai,
                'total_harga'     => $p->total_harga,
                'status'          => $statuses[$i] ?? 'aktif',
                'catatan'         => $catatan[$i] ?? null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        DB::table('kontrak')->insert($rows);

        $this->command->info('KontrakSeeder: ' . count($rows) . ' kontrak berhasil dibuat.');
    }
}
