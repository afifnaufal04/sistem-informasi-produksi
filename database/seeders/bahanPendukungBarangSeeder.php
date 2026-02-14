<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BahanPendukungBarang;

class BahanPendukungBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $bahanPendukungBarangs = [
            // Talenan Oval
            ['bahan_pendukung_id' => 1, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 5,'sub_proses_id' => 1],
            ['bahan_pendukung_id' => 2, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 5,'sub_proses_id' => 2],
            ['bahan_pendukung_id' => 3, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 5,'sub_proses_id' => 3],
            ['bahan_pendukung_id' => 4, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 5,'sub_proses_id' => 4],
            ['bahan_pendukung_id' => 5, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 4],
            ['bahan_pendukung_id' => 6, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 5],
            ['bahan_pendukung_id' => 7, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 6],
            ['bahan_pendukung_id' => 8, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 7],
            ['bahan_pendukung_id' => 9, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 8],
            ['bahan_pendukung_id' => 10, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 9],
            ['bahan_pendukung_id' => 11, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 10],
            ['bahan_pendukung_id' => 19, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 11],
            ['bahan_pendukung_id' => 20, 'barang_id' => 1, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 11],
           
            // Sutil Kayu
            ['bahan_pendukung_id' => 1, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 5,'sub_proses_id' => 1],
            ['bahan_pendukung_id' => 2, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 5,'sub_proses_id' => 2],
            ['bahan_pendukung_id' => 3, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 5,'sub_proses_id' => 3],
            ['bahan_pendukung_id' => 4, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 5,'sub_proses_id' => 4],
            ['bahan_pendukung_id' => 5, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 5],
            ['bahan_pendukung_id' => 6, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 6],
            ['bahan_pendukung_id' => 7, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 7],
            ['bahan_pendukung_id' => 8, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 8],
            ['bahan_pendukung_id' => 9, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 9],
            ['bahan_pendukung_id' => 10, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 10],
            ['bahan_pendukung_id' => 11, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 11],
            ['bahan_pendukung_id' => 19, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 11],
            ['bahan_pendukung_id' => 20, 'barang_id' => 2, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 11],

            // Tray Bambu
            ['bahan_pendukung_id' => 2, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 5,'sub_proses_id' => 2],
            ['bahan_pendukung_id' => 1, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 5,'sub_proses_id' => 1],
            ['bahan_pendukung_id' => 3, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 5,'sub_proses_id' => 3],
            ['bahan_pendukung_id' => 4, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 5,'sub_proses_id' => 4],
            ['bahan_pendukung_id' => 5, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 5],
            ['bahan_pendukung_id' => 6, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 6],
            ['bahan_pendukung_id' => 7, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 7],
            ['bahan_pendukung_id' => 8, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 8],
            ['bahan_pendukung_id' => 9, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 9],
            ['bahan_pendukung_id' => 10, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 10],
            ['bahan_pendukung_id' => 11, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 11],
            ['bahan_pendukung_id' => 18, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 11],
            ['bahan_pendukung_id' => 20, 'barang_id' => 3, 'jumlah_bahan_pendukung' => 1,'sub_proses_id' => 11],
        ]; 

        foreach ($bahanPendukungBarangs as $bahanPendukungBarang) {
            BahanPendukungBarang::create($bahanPendukungBarang);
        };
    }
}