<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@klinik.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // Kasir
        User::create([
            'name' => 'Kasir Klinik',
            'email' => 'kasir@klinik.com',
            'password' => bcrypt('password123'),
            'role' => 'kasir',
        ]);

        // Pasien
        User::create([
            'name' => 'Pasien Budi',
            'email' => 'pasien@klinik.com',
            'password' => bcrypt('password123'),
            'role' => 'pasien',
        ]);
    }
}
