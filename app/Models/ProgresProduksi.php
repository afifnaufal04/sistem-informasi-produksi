<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgresProduksi extends Model
{
    use HasFactory;

    protected $table = 'progres_produksi';
    protected $primaryKey = 'progres_produksi_id';

    protected $fillable = [
        'produksi_id',
        'sub_proses_id',
        'dlm_proses', 
        'sdh_selesai',
        'jumlah'
        ];

    public function detailPengiriman()
    {
        return $this->hasMany(DetailPengiriman::class, 'progres_produksi_id', 'progres_produksi_id');
    }

    public function subProses()
    {
        return $this->belongsTo(SubProses::class, 'sub_proses_id', 'sub_proses_id');
    }

    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id', 'produksi_id');
    }
}
