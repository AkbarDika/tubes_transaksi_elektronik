<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';
    protected $primaryKey = 'id_denda';

    protected $fillable = [
        'pengembalian_id',
        'jenis_denda',
        'jumlah_hari_terlambat',
        'tarif_denda_per_hari',
        'total_denda',
        'keterangan'
    ];

    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'pengembalian_id', 'id_pengembalian');
    }
}
