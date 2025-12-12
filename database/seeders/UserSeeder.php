<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert user owner/dokter
        DB::table('users')->insert([
            'username' => 'owner',
            'email' => 'owner@clinic.com',
            'password' => Hash::make('owner123'),
            'role' => 'dokter',
            'status_akun' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert user admin petugas klinik
        DB::table('users')->insert([
            'username' => 'admin',
            'email' => 'admin@clinic.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status_akun' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert user owner/dokter
        DB::table('users')->insert([
            'username' => 'dokter',
            'email' => 'dokter@clinic.com',
            'password' => Hash::make('dokter123'),
            'role' => 'dokter',
            'status_akun' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
    }
}
