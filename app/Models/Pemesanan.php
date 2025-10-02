<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Pemesanan extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'pemesanan';
    protected $primaryKey = 'pemesanan_id';
    
    protected $fillable = [
        'pembeli_id',
        'no_SPK_pembeli',
        'tanggal_pemesanan',
        'no_SPK_kwas',
    ];
}
