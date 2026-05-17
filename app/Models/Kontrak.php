<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model
{
    protected $table = 'kontrak';

    protected $fillable = [
        'pemesanan_id',
        'nomor_kontrak',
        'tanggal_kontrak',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_harga',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_kontrak' => 'date',
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'total_harga'     => 'decimal:2',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktif'      => 'primary',
            'selesai'    => 'success',
            'dibatalkan' => 'danger',
            default      => 'secondary',
        };
    }

    public function getDurasiSewaAttribute(): int
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai);
    }

    /**
     * Generate nomor kontrak unik: KTR-YYYYMMDD-XXXX
     */
    public static function generateNomor(): string
    {
        $prefix = 'KTR-' . now()->format('Ymd') . '-';
        $last   = self::where('nomor_kontrak', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $sequence = $last
            ? (int) substr($last->nomor_kontrak, -4) + 1
            : 1;

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
