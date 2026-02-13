<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengiriman extends Model
{
    use HasFactory;

    protected $table = 'detail_pengiriman';
    protected $primaryKey = 'detail_pengiriman_id';

    protected $fillable = [
        'pengiriman_id',
        'produksi_id',
        'sub_proses_id',
        'supplier_id', // diambil dari users
        'jumlah_pengiriman',
        'butuh_bp',
        'status_pengiriman',
        'waktu_sampai',
        'waktu_diterima',
        'status_pengerjaan',
        'jumlah_selesai',
        'gudang_konfirmasi',
    ];

    protected $casts = [
        'waktu_sampai' => 'datetime',
        'waktu_diterima' => 'datetime',
    ];

    public function pengiriman()
    {
        return $this->belongsTo(Pengiriman::class, 'pengiriman_id', 'pengiriman_id');
    }

    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id', 'produksi_id');
    }
    
    public function subProses()
    {
        return $this->belongsTo(SubProses::class, 'sub_proses_id', 'sub_proses_id');
    }

    // Relasi ke user yang berperan sebagai supplier
    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id', 'id');
    }

    public function detailPengambilan()
    {
        return $this->hasMany(DetailPengambilan::class, 'detail_pengiriman_id', 'detail_pengiriman_id');
    }

    // Relasi one-to-one ke QC hasil
    public function qcHasil()
    {
        return $this->hasOne(QcHasil::class, 'detail_pengiriman_id', 'detail_pengiriman_id');
    }

}
