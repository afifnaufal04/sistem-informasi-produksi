<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'produksi';
    protected $primaryKey = 'produksi_id';

    protected $fillable = [
        'barang_id',
        'jumlah_produksi',
        'status_produksi'
    ];


    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    public function progresProduksi()
    {
        return $this->hasMany(ProgresProduksi::class, 'produksi_id', 'produksi_id');
    }

    public function detailPengiriman()
    {
        return $this->hasMany(DetailPengiriman::class, 'produksi_id', 'produksi_id');
    }
}

