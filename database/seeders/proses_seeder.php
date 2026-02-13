<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proses;

class proses_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prosess = [
            ['nama_proses' => 'ww'],
            ['nama_proses' => 'finishing'],
            ['nama_proses' => 'packing'],
        ];

        foreach ($prosess as $proses) {
            Proses::create($proses);
        }
    }
}
