<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class BahanPendukung extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'bahan_pendukung_id';

    protected $fillable = [
        'nama',
        'stok',
    ];
}
