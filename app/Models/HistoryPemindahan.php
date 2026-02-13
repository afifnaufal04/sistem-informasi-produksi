<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HistoryPemindahan extends Model
{
    use HasFactory;

    protected $table = 'history_pemindahan_produksi';
    protected $primaryKey = 'history_pemindahan_produksi_id';
    
    protected $fillable = [
        'produksi_id',
        'barang_id',
        'jumlah',
        'tanggal_pemindahan',
    ];

    // ✅ RELASI KE PRODUKSI
    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }

    // ✅ RELASI KE BARANG
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
