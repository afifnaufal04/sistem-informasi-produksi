<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProses extends Model
{
    use HasFactory;

    protected $table = 'sub_proses';
    protected $primaryKey = 'sub_proses_id';

    protected $fillable = ['proses_id', 'nama_sub_proses', 'urutan'];

    public function proses()
    {
        return $this->belongsTo(Proses::class, 'proses_id', 'proses_id');
    }

    public function gudangQcGagal()
    {
        return $this->hasMany(GudangQcGagal::class, 'sub_proses_id');
    }

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'sub_proses_id');
    }
    public function progresProduksi()
    {
        return $this->hasMany(ProgresProduksi::class, 'sub_proses_id', 'sub_proses_id');
    }
    public function bahanPendukungBarang()
    {
        return $this->hasMany(BahanPendukungBarang::class, 'sub_proses_id', 'sub_proses_id');
    }
    public function detailPengiriman()
    {
        return $this->hasMany(DetailPengiriman::class, 'sub_proses_id', 'sub_proses_id');
    }

}
