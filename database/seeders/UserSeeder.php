<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Usuario administrador manual
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
        ]);

        // Usuario de prueba manual
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('test123'), // Asegúrate de ponerle clave
        ]);
    }
}
