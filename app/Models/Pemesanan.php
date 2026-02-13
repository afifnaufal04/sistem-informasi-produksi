<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\PemesananBarang;
use App\Models\Pembeli;

class Pemesanan extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'pemesanan';
    protected $primaryKey = 'pemesanan_id';
    
    protected $fillable = [
        'pembeli_id',
        'no_PO',
        'tanggal_pemesanan',
        'no_SPK_kwas',
        'periode_produksi',
        'tgl_penerbitan_spk',
        'status_pemesanan',
        'konfirmasi_marketing',
        'konfirmasi_ppic',
        'konfirmasi_gudang',
        'konfirmasi_finishing',
    ];

    // Relasi ke pemesanan_barang
    public function pemesananBarang()
    {
        return $this->hasMany(PemesananBarang::class, 'pemesanan_id', 'pemesanan_id');
    }

    // relasi ke pembeli
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id', 'pembeli_id');
    }

}
