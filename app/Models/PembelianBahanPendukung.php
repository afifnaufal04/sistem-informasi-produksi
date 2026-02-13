<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianBahanPendukung extends Model
{
    use HasFactory;

    protected $table = 'pembelian_bahan_pendukung';
    protected $primaryKey = 'pembelian_bahan_pendukung_id';

    protected $fillable = [
        'status_order',
        'tanggal_pembelian',
        'total_harga',
        'catatan',
    ];

    public function detailpembelianbahanpendukung()
    {
        return $this->hasMany(DetailPembelianBahanPendukung::class, 'pembelian_bahan_pendukung_id', 'pembelian_bahan_pendukung_id');
    }
}
