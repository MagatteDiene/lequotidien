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
            'password' => Hash::make('123'),
            'role' => 'administrateur',
            'actif' => true,
        ]);

        User::create([
            'name' => 'Magatte',
            'email' => 'magatte@news.com',
            'password' => Hash::make('123'),
            'role' => 'editeur',
            'actif' => true,
        ]);

    }
}
