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
        // Crear administrador
        User::create([
            'name' => 'Administrador del Club',
            'email' => 'admin@tenis.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Crear usuario normal
        User::create([
            'name' => 'Jugador de Prueba',
            'email' => 'jugador@tenis.com',
            'password' => Hash::make('jugador123'),
            'role' => 'user',
        ]);

        // Crear más usuarios aleatorios
        User::factory()->count(3)->create();
    }
}
