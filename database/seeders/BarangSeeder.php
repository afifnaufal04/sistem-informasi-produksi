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
                'ww_id' => null, 
                'finishing_id' => null, 
                'packing_id' => null,
                'nama_barang' => 'Talenan Oval',
                'harga_barang' => 20000,
                'stok_gudang' => 50,
                'gambar_barang' => 'TalenanOval.jpg',
                'jenis_barang' => 'diy',
                'panjang' => 40.0,
                'lebar' => 30.0,
                'tinggi' => 2.0,
                'jumlah_stok_acc' => null,
                'jumlah_pemesanan' => null,
            ], 
            [
                'ww_id' => null, 
                'finishing_id' => null, 
                'packing_id' => null,
                'nama_barang' => 'Sutil Kayu',
                'harga_barang' => 10000,
                'stok_gudang' => 100,
                'gambar_barang' => 'SutilKayu.jpg',
                'jenis_barang' => 'superindo',
                'panjang' => 45.0,
                'lebar' => 15.0,
                'tinggi' => 2.0,
                'jumlah_stok_acc' => null,
                'jumlah_pemesanan' => null,
            ],
            [
                'ww_id' => null, 
                'finishing_id' => null, 
                'packing_id' => null,
                'nama_barang' => 'Tray Bambu',
                'harga_barang' => 30000,
                'stok_gudang' => 50,
                'gambar_barang' => 'TrayBambu.jpg',
                'jenis_barang' => 'pendopo',
                'panjang' => 45.0,
                'lebar' => 20.0,
                'tinggi' => 5.0,
                'jumlah_stok_acc' => null,
                'jumlah_pemesanan' => null,
            ],
        ];


        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
