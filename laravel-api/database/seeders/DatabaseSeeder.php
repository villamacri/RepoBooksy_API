<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        
        User::create([
            'nombre' => 'Usuario',
            'apellidos' => 'Prueba',
            'email' => 'test@test.com',
            'password' => Hash::make('12345678'),
            'fecha_registro' => now(),        
            'role' => 'admin',               
            'estado' => 'activo'
        ]);


    }
}