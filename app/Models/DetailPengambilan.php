<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPengambilan extends Model
{
    use HasFactory;

    protected $table = 'detail_pengambilan';
    protected $primaryKey = 'detail_pengambilan_id';

    protected $fillable = [
        'pengambilan_id',
        'detail_pengiriman_id',
        'jumlah_diambil',
        'harga_produksi',
        'total_pembayaran',
        'status_bayar',
    ];

    /* ==========================
       🔗 Relasi antar tabel
    ===========================*/

    // Relasi ke pengambilan
    public function pengambilan()
    {
        return $this->belongsTo(Pengambilan::class, 'pengambilan_id', 'pengambilan_id');
    }

    // Relasi ke detail pengiriman
    public function detailPengiriman()
    {
        return $this->belongsTo(DetailPengiriman::class, 'detail_pengiriman_id', 'detail_pengiriman_id');
    }
}
