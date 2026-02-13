<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';
    protected $primaryKey = 'pengiriman_id';

    protected $fillable = [
        'pemesanan_barang_id',
        'kendaraan_id',
        'supir_id',    // diambil dari users
        'qc_id',       // diambil dari users
        'tanggal_pengiriman',
        'tanggal_selesai',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'total_waktu_antar',
        'tipe_pengiriman',
    ];

    // Tambahkan casts
    protected $casts = [
        'tanggal_pengiriman' => 'date',
        'tanggal_selesai' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /* ==========================
       🔗 Relasi antar tabel
    ===========================*/

    // Relasi ke user yang berperan sebagai supir
    public function supir()
    {
        return $this->belongsTo(User::class, 'supir_id', 'id');
    }

    // Relasi ke user yang berperan sebagai QC
    public function qc()
    {
        return $this->belongsTo(User::class, 'qc_id', 'id');
    }

    public function detailPengiriman()
    {
        return $this->hasMany(DetailPengiriman::class, 'pengiriman_id', 'pengiriman_id');
    }

    // Relasi ke kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id', 'kendaraan_id');
    }
}



