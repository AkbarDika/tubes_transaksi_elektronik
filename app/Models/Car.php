<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $table = 'mobil';

    protected $fillable = [
        'kategori_id',
        'merk',
        'model',
        'tahun',
        'nomor_plat',
        'jumlah_kursi',
        'harga_sewa',
        'status',
        'foto'
    ];

    // RELASI: Mobil → Kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriMobil::class, 'kategori_id');
    }
}
