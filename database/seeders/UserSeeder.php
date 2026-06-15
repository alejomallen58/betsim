<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@apuestas.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('password'),
                'saldo'    => 0,
                'is_admin' => true,
            ]
        );

        // Usuario de prueba
        User::firstOrCreate(
            ['email' => 'demo@apuestas.com'],
            [
                'name'     => 'Usuario Demo',
                'password' => Hash::make('password'),
                'saldo'    => 1000.00,
                'is_admin' => false,
            ]
        );
    }
}
