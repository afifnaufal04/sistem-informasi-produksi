<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            User::create([
                'username' => 'supir',
                'name' => 'Supir',
                'email' => 'supir@example.com',
                'password' => Hash::make('111'),
                'role' => 'supir',
                'work_status' => 'Tersedia'
            ]);

            User::create([
                'username' => 'gudang',
                'name' => 'Gudang',
                'email' => 'gudang@example.com',
                'password' => Hash::make('111'),
                'role' => 'gudang',
                'work_status' => 'Tersedia'
            ]);

            User::create([
                'username' => 'ppic',
                'name' => 'PPIC',
                'email' => 'ppic@example.com',
                'password' => Hash::make('111'),
                'role' => 'ppic',
                'work_status' => 'Tersedia'
            ]);

            User::create([
                'username' => 'qc',
                'name' => 'Quality Control',
                'email' => 'qc@example.com',
                'password' => Hash::make('111'),
                'role' => 'qc',
                'work_status' => 'Tersedia'
            ]);

            User::create([
                'username' => 'keprod',
                'name' => 'Kepala Produksi',
                'email' => 'keprod@example.com',
                'password' => Hash::make('111'),
                'role' => 'keprod',
                'work_status' => 'Tersedia'
            ]);

            User::create([
                'username' => 'direktur',
                'name' => 'Direktur',
                'email' => 'direktur@example.com',
                'password' => Hash::make('111'),
                'role' => 'direktur',
                'work_status' => 'Tersedia'
            ]);

            User::create([
                'username' => 'admin',
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('111'),
                'role' => 'admin',
                'work_status' => 'Tersedia'
            ]);

            User::create([
                'username' => 'marketing',
                'name' => 'Marketing',
                'email' => 'marketing@example.com',
                'password' => Hash::make('111'),
                'role' => 'marketing',
                'work_status' => 'Tersedia'
            ]);

            User::create([
                'username' => 'purchasing',
                'name' => 'Purchasing',
                'email' => 'purchasing@example.com',
                'password' => Hash::make('111'),
                'role' => 'purchasing',
                'work_status' => 'Tersedia'
            ]);

            User::create([
                'username' => 'supplier',
                'name' => 'Supplier',
                'email' => 'supplier@example.com',
                'password' => Hash::make('111'),
                'role' => 'supplier',
                'work_status' => 'Tersedia'
            ]);

            User::create([
                'username' => 'packing',
                'name' => 'Packing',
                'email' => 'packing@example.com',
                'password' => Hash::make('111'),
                'role' => 'packing',
                'work_status' => 'Tersedia'
            ]);
    }
}
