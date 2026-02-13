<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pembeli;

class PembeliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pembelis = [
            ['nama_pembeli' => 'Superindo', 'kode_buyer' => 'SUP'],
            ['nama_pembeli' => 'DIY', 'kode_buyer' => 'DIY'],
            ['nama_pembeli' => 'Pendopo', 'kode_buyer' => 'PDP'],
            ['nama_pembeli' => 'OOA', 'kode_buyer' => 'OOA'],
        ];
        foreach ($pembelis as $pembeli) {
            Pembeli::create($pembeli);
        }
    }
}
