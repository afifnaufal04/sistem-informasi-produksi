<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kendaraan;

class KendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kendaraans = [
            ['nama' => 'Avanza', 'nomor_polisi' => 'AB 1728 GQ', 'kendaraan_status' => 'Tersedia'],
            ['nama' => 'Grand Max', 'nomor_polisi' => 'AB 8254 LH', 'kendaraan_status' => 'Tersedia'],
            ['nama' => 'Mitsubishi L300', 'nomor_polisi' => 'AB 8452 PB ', 'kendaraan_status' => 'Tersedia'],
        ];
        foreach ($kendaraans as $kendaraan) {
            Kendaraan::create($kendaraan);
        }
    }
}
