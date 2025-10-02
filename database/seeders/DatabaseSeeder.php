<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PembeliSeeder::class,
            KendaraanSeeder::class,
            BahanPendukungSeeder::class,
            BarangSeeder::class,
        ]);
    }
}
