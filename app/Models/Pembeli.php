<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Pembeli extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'pembeli';
    protected $primaryKey = 'pembeli_id';

    protected $fillable = [
        'nama_pembeli',
        'kode_buyer',
    ];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'pembeli_id', 'pembeli_id');
    }

}
