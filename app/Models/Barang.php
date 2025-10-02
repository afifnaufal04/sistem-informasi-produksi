<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';
    protected $primaryKey = 'barang_id';
    protected $fillable = [
        'ww_id',
        'finishing_id',
        'packing_id',
        'nama_barang',
        'harga_barang',
        'stok_gudang',
        'gambar_barang',
        'jenis_barang',
        'panjang',
        'lebar',
        'tinggi',
        'jumlah_stok_acc',
        'jumlah_pemesanan',
    ];
}
