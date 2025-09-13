<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur administrateur par défaut
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@tutormatch.com',
            'password' => Hash::make('passwordtutormatch123'),
            'email_verified_at' => now(),
        ]);

        // Créer un utilisateur de test
        User::create([
            'name' => 'Utilisateur Test',
            'email' => 'test@tutormatch.com',
            'password' => Hash::make('tutormatchpassword123'),
            'email_verified_at' => now(),
        ]);
    }
}
