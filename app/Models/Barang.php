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
    ];

    public function bahanPendukungBarang()
    {
        return $this->hasMany(BahanPendukungBarang::class, 'barang_id', 'barang_id');
    }

    public function gudangQcGagal()
    {
        return $this->hasMany(GudangQcGagal::class, 'barang_id');
    }


    public function produksi()
    {
        return $this->hasMany(Produksi::class, 'barang_id', 'barang_id');
    }

    public function pemesananBarang()
    {
        return $this->hasMany(PemesananBarang::class, 'barang_id', 'barang_id');
    }
}
