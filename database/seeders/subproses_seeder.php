<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubProses;

class subproses_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sub_prosess = [
            // ww
            ['proses_id' => 1, 'nama_sub_proses' => 'mentah', 'urutan' => 1],
            ['proses_id' => 1, 'nama_sub_proses' => '2d', 'urutan' => 2],
            ['proses_id' => 1, 'nama_sub_proses' => '3d', 'urutan' => 3],
            ['proses_id' => 1, 'nama_sub_proses' => 'laminasi', 'urutan' => 4],
            ['proses_id' => 1, 'nama_sub_proses' => 'planner', 'urutan' => 5],
            ['proses_id' => 1, 'nama_sub_proses' => 'lubangi', 'urutan' => 6],
            ['proses_id' => 1, 'nama_sub_proses' => 'amplas', 'urutan' => 7],
            
            // finishing
            ['proses_id' => 2, 'nama_sub_proses' => 'sanding sealer', 'urutan' => 1],
            ['proses_id' => 2, 'nama_sub_proses' => 'warna', 'urutan' => 2],
            ['proses_id' => 2, 'nama_sub_proses' => 'wax', 'urutan' => 3],

            // packing
            ['proses_id' => 3, 'nama_sub_proses' => 'packing', 'urutan' => 1],
        ];
        
        foreach ($sub_prosess as $sub_proses) {
            SubProses::create($sub_proses);
        }
    }
}
