<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $primaryKey = 'id_pengembalian';

    protected $fillable = [
        'pemesanan_id',
        'jenis',
        'tanggal_kembali',
        'kondisi_mobil',
        'catatan',
        'status_pengembalian'
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }

    public function denda()
    {
        return $this->hasOne(Denda::class, 'pengembalian_id', 'id_pengembalian');
    }

    
}
