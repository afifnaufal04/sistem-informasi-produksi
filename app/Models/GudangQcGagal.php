<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangQcGagal extends Model
{
    use HasFactory;
    
    protected $table = 'gudang_qc_gagal';
    protected $primaryKey = 'gudang_qc_gagal_id';

    protected $fillable = [
        'barang_id',        // ID dari tabel barang
        'sub_proses_id',    // ID dari tabel subproses
        'jumlah',           // Jumlah produk gagal QC
        'asal_spk',         // Nomor SPK asal dari tabel pemesanan
        'catatan',          // Catatan tambahan
    ];

    // Relasi ke model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // Relasi ke model SubProses
    public function subProses()
    {
        return $this->belongsTo(SubProses::class, 'sub_proses_id');
    }
}
