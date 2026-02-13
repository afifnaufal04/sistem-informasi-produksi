<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = [
            [
                'nama_barang' => 'Talenan Oval',
                'harga_barang' => 20000,
                'stok_gudang' => 50,
                'gambar_barang' => null,
                'jenis_barang' => 'diy',
                'panjang' => 40.0,
                'lebar' => 30.0,
                'tinggi' => 2.0,
                'jumlah_stok_acc' => null,
            ], 
            [
                'nama_barang' => 'Sutil Kayu',
                'harga_barang' => 10000,
                'stok_gudang' => 100,
                'gambar_barang' => null,
                'jenis_barang' => 'superindo',
                'panjang' => 45.0,
                'lebar' => 15.0,
                'tinggi' => 2.0,
                'jumlah_stok_acc' => null,
            ],
            [

                'nama_barang' => 'Tray Bambu',
                'harga_barang' => 30000,
                'stok_gudang' => 50,
                'gambar_barang' => null,
                'jenis_barang' => 'pendopo',
                'panjang' => 45.0,
                'lebar' => 20.0,
                'tinggi' => 5.0,
                'jumlah_stok_acc' => null,
            ],
        ];


        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
