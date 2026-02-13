<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QcHasil extends Model
{
    use HasFactory;

    protected $table = 'qc_hasil';
    protected $primaryKey = 'qc_hasil_id';

    protected $fillable = [
        'detail_pengiriman_id',
        'pengambilan_id',
        'qc_id',
        'tanggal_qc',
        'jumlah_lolos',
        'jumlah_gagal',
        'jumlah_reject',
        'catatan',
        'status',
        'tombol_aksi'
    ];

    protected $casts = [
        'tanggal_qc' => 'date',
    ];

    /* ==========================
       🔗 Relasi antar tabel
    ===========================*/

    // Relasi ke detail pengiriman (relasi utama)
    public function detailPengiriman()
    {
        return $this->belongsTo(DetailPengiriman::class, 'detail_pengiriman_id', 'detail_pengiriman_id');
    }

    // Relasi ke pengambilan (opsional, untuk tracking)
    public function pengambilan()
    {
        return $this->belongsTo(Pengambilan::class, 'pengambilan_id', 'pengambilan_id');
    }

    // Relasi ke user yang berperan sebagai QC
    public function qc()
    {
        return $this->belongsTo(User::class, 'qc_id', 'id');
    }
}
