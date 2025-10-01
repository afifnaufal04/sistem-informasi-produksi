<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Kendaraan extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'kendaraan';
    protected $primaryKey = 'kendaraan_id';

    protected $fillable = [
        'nama',
        'nomor_polisi',
        'kendaraan_status',
    ];
}
