<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BahanPendukung;

class BahanPendukungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bahan_pendukungs = [
            ['nama_bahan_pendukung' => 'Lem Putih', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 10000, 'satuan' => 'Mililiter'],
            ['nama_bahan_pendukung' => 'Wax', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 50000, 'satuan' => 'Gram'],
            ['nama_bahan_pendukung' => 'Amplas 100', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 15000, 'satuan' => 'Lembar'],
            ['nama_bahan_pendukung' => 'Amplas 240', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 20000, 'satuan' => 'Lembar'],
            ['nama_bahan_pendukung' => 'Amplas 400', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 25000, 'satuan' => 'Lembar'],
            ['nama_bahan_pendukung' => 'Amplas 1000', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 30000, 'satuan' => 'Lembar'],
            ['nama_bahan_pendukung' => 'Wood Filler Jati', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 10000, 'satuan' => 'Gram'],
            ['nama_bahan_pendukung' => '3M', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 10000, 'satuan' => 'Lembar'],
            ['nama_bahan_pendukung' => 'Kain Perca', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 5000, 'satuan' => 'Lembar'],
            ['nama_bahan_pendukung' => 'Sanding Sealer', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 50000, 'satuan' => 'Mililiter'],
            ['nama_bahan_pendukung' => 'Biowarna + Warna', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 50000, 'satuan' => 'Mililiter'],
            ['nama_bahan_pendukung' => 'Alteko', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 5000, 'satuan' => 'Mililiter'],
            ['nama_bahan_pendukung' => 'Paku F25', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 30000, 'satuan' => 'Dus'],
            ['nama_bahan_pendukung' => 'Warna Duco', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 50000, 'satuan' => 'Mililiter'],
            ['nama_bahan_pendukung' => 'Vernis', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 50000, 'satuan' => 'Mililiter'],
            ['nama_bahan_pendukung' => 'Cat Hijau', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 50000, 'satuan' => 'Mililiter'],
            ['nama_bahan_pendukung' => 'Cat Merah', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 50000, 'satuan' => 'Mililiter'],
            ['nama_bahan_pendukung' => 'Kardus (45x30)', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 50000, 'satuan' => 'Pcs'],
            ['nama_bahan_pendukung' => 'Kardus (30x25)', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 50000, 'satuan' => 'Pcs'],
            ['nama_bahan_pendukung' => 'Hand Tag', 'stok_bahan_pendukung' => 500, 'harga_bahan_pendukung' => 1000, 'satuan' => 'Pcs'],
        ];

        foreach ($bahan_pendukungs as $bahan_pendukung) {
            BahanPendukung::create($bahan_pendukung);
        }
    }
}
