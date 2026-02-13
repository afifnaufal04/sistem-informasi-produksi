<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Pemesanan;
use App\Models\Barang;

// class PemesananBarang extends Model
// {
//     use HasFactory;

//     protected $table = 'pemesanan_barang';
//     protected $primaryKey = 'pemesanan_barang_id';

//     protected $fillable = [
//         'pemesanan_id',
//         'barang_id',
//         'status',
//         'jumlah_pemesanan',
//         'jumlah_ww',
//         'jumlah_finishing',
//         'jumlah_packing',
//     ];

//     // Relasi ke Pemesanan
//     public function pemesanan()
//     {
//         return $this->belongsTo(Pemesanan::class, 'pemesanan_id', 'pemesanan_id');
//     }

//     // Relasi ke BahanPendukungBarang
//     public function barang()
//     {
//         return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
//     }
// }

//BARU YA PUNYA RICO
class PemesananBarang extends Model
{
    use HasFactory;

    protected $table = 'pemesanan_barang';
    protected $primaryKey = 'pemesanan_barang_id';

    protected $fillable = [
        'pemesanan_id',
        'barang_id',
        'status',
        'jumlah_pemesanan',
        'jumlah_stok_acc',
        'jumlah_qc_gagal',
        'jumlah_selesai_packing',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id', 'pemesanan_id');
    }
    
    // Tambahkan relasi ke barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    public function produksi()
    {
        return $this->hasMany(Produksi::class, 'pemesanan_barang_id', 'pemesanan_barang_id');
    }

    //relasi one to one dengan packing
    public function packing()
    {
        return $this->hasMany(Packing::class, 'pemesanan_barang_id', 'pemesanan_barang_id');
    }
}