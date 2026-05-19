<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'pemesanan_id',
        'metode_pembayaran',
        'tanggal_bayar',
        'jumlah_bayar',
        'uang_diterima',
        'kembalian',
        'petugas_id',
        'bukti_bayar',
        'status',
    ];

    protected $casts = [
        'tanggal_bayar'  => 'date',
        'jumlah_bayar'   => 'decimal:2',
        'uang_diterima'  => 'decimal:2',
        'kembalian'      => 'decimal:2',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
