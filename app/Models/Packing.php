<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Packing extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'packing';
    protected $primaryKey = 'packing_id';

    protected $fillable = [
        'jumlah_barang',
    ];
}
