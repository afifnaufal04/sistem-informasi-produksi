<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Finishing extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'finishing_id';

    protected $fillable = [
        'jumlah_barang',
        'sub_proses',
    ];
}
