<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WW extends Model
{
    use HasFactory;

    protected $primaryKey = 'ww_id';
    protected $fillable = [
        'jumlah_barang',
        'sub_proses',
    ];
}
