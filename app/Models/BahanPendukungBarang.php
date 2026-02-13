<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BahanPendukung;
use App\Models\Barang;
use App\Models\Proses;

class BahanPendukungBarang extends Model
{
    use HasFactory;

    protected $table = 'bahan_pendukung_barang';
    protected $primaryKey = 'bahan_dan_barang_id';

    protected $fillable = [
        'bahan_pendukung_id',
        'barang_id',
        'sub_proses_id',
        'jumlah_bahan_pendukung',
    ];

    public function bahanPendukung()
    {
        return $this->belongsTo(BahanPendukung::class, 'bahan_pendukung_id', 'bahan_pendukung_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }
    public function subProses()
    {
        return $this->belongsTo(SubProses::class, 'sub_proses_id', 'sub_proses_id');
    }
}
