<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@news.com',
            'password' => Hash::make('password'),
            'role' => 'administrateur',
            'actif' => true,
        ]);

        User::create([
            'name' => 'Éditeur',
            'email' => 'editeur@news.com',
            'password' => Hash::make('password'),
            'role' => 'editeur',
            'actif' => true,
        ]);

        User::create([
            'name' => 'Visiteur',
            'email' => 'visiteur@news.com',
            'password' => Hash::make('password'),
            'role' => 'visiteur',
            'actif' => true,
        ]);
    }
}
