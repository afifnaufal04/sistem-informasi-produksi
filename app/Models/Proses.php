<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proses extends Model
{
    use HasFactory;

    protected $table = 'proses';
    protected $primaryKey = 'proses_id';

    protected $fillable = ['nama_proses'];

    public function subProses()
    {
        return $this->hasMany(SubProses::class, 'proses_id', 'proses_id');
    }
    public function bahanPendukungBarang()
    {
        return $this->hasMany(BahanPendukungBarang::class, 'proses_id', 'proses_id');
    }
}
