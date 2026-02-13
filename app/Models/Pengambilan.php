<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengambilan extends Model
{
    use HasFactory;

    protected $table = 'pengambilan';
    protected $primaryKey = 'pengambilan_id';

    protected $fillable = [
        'kendaraan_id',
        'supir_id',
        'qc_id',
        'tanggal_pengambilan',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_pengambilan' => 'date',
        'tanggal_selesai' => 'date',
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

    // Relasi ke kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id', 'kendaraan_id');
    }

    // Relasi ke detail pengambilan
    public function detailPengambilan()
    {
        return $this->hasMany(DetailPengambilan::class, 'pengambilan_id', 'pengambilan_id');
    }

    // Relasi ke qc hasil (untuk tracking QC yang dilakukan di pengambilan ini)
    public function qcHasil()
    {
        return $this->hasMany(QcHasil::class, 'pengambilan_id', 'pengambilan_id');
    }
}
