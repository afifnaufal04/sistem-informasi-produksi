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
            ['nama_pembeli' => 'Superindo'],
            ['nama_pembeli' => 'DIY'],
            ['nama_pembeli' => 'Pendopo'],
            ['nama_pembeli' => 'OOA'],
        ];
        foreach ($pembelis as $pembeli) {
            Pembeli::create($pembeli);
        }
    }
}
