<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'), // Hashea la contraseña
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
