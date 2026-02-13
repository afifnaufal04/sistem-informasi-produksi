<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelianBahanPendukung extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian_bahan_pendukung';
    protected $primaryKey = 'detail_pembelian_bahan_pendukung_id';

    protected $fillable = [
        'pembelian_bahan_pendukung_id',
        'bahan_pendukung_id',
        'jumlah_pembelian',
        'harga_bahan_pendukung',
        'subtotal'
    ];

    public function pembelianbahanpendukung()
    {
        return $this->belongsTo(PembelianBahanPendukung::class, 'pembelian_bahan_pendukung_id', 'pembelian_bahan_pendukung_id');
    }

    public function bahanpendukung()
    {
        return $this->belongsTo(BahanPendukung::class, 'bahan_pendukung_id', 'bahan_pendukung_id');
    }
}
