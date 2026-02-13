<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Packing extends Model
{
    use HasFactory;
    protected $table = 'packing';
    protected $primaryKey = 'packing_id';
    protected $fillable = [
        'pemesanan_barang_id',
        'jumlah_packing',
        'jumlah_selesai_packing',
        'status_packing',
        'gudang_konfirmasi',
    ];

    protected $casts = [
    'gudang_konfirmasi' => 'boolean',
];

    // Relasi one to one dengan PemesananBarang
    public function pemesananBarang()
    {
        return $this->belongsTo(PemesananBarang::class, 'pemesanan_barang_id', 'pemesanan_barang_id');
    }
}
