<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';


    protected $fillable = [
        'user_id',
        'tanggal_pesan',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_harga',
        'status'
    ];

    protected $casts = [
        'tanggal_pesan' => 'datetime',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(DetailPemesanan::class, 'pemesanan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }


    public function getStatusTampilanAttribute()
    {
        // 1. Belum bayar
        if (!$this->pembayaran || $this->pembayaran->status !== 'valid') {
            return 'Menunggu Pembayaran';
        }

        // 2. Sudah bayar + pengembalian selesai
        if ($this->pembayaran->status === 'valid' && 
            $this->pengembalian && 
            $this->pengembalian->status_pengembalian === 'selesai') {
            return 'Selesai';
        }

        // 3. Sudah ajukan pengembalian (pending)
        if ($this->pengembalian && $this->pengembalian->status_pengembalian === 'pending') {
            return 'Pengembalian Pending';
        }

        // 4. Sewa selesai (dari status pemesanan)
        if ($this->status === 'selesai') {
            return 'Selesai';
        }

        if ($this->pembayaran->status === 'valid' && $this->status === 'pending') {
            return 'Pending';
        }

        if ($this->pengembalian && $this->pengembalian->status_pengembalian === 'bermasalah') {
            return 'Pengembalian Bermasalah';
        }

        // 5. Default (sudah bayar, sedang aktif)
        return 'Sedang Disewa';
    }


    public function getStatusBadgeAttribute()
    {
        return match ($this->status_tampilan) {
            'Menunggu Pembayaran' => 'secondary',
            'Pengembalian Pending' => 'warning',
            'Pending' => 'warning',
            'Selesai' => 'success',
            'Sedang Disewa' => 'info',
            'Pengembalian Bermasalah' => 'danger',
            default => 'success',
        };
    }


}
