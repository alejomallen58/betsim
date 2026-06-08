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
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@apuestas.com',
            'password' => Hash::make('password'),
            'saldo'    => 0,
            'is_admin' => true,
        ]);

        // Usuario de prueba
        User::create([
            'name'     => 'Usuario Demo',
            'email'    => 'demo@apuestas.com',
            'password' => Hash::make('password'),
            'saldo'    => 1000.00,
            'is_admin' => false,
        ]);
    }
}
