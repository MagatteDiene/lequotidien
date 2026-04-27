<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Politique', 'description' => 'Actualités politiques nationales et internationales.'],
            ['nom' => 'Technologie', 'description' => 'Innovations, gadgets et futur numérique.'],
            ['nom' => 'Sport', 'description' => 'Résultats, interviews et analyses sportives.'],
            ['nom' => 'Économie', 'description' => 'Marchés financiers, entreprises et économie mondiale.'],
            ['nom' => 'Culture', 'description' => 'Cinéma, musique, littérature et arts.'],
            ['nom' => 'Santé', 'description' => 'Bien-être, médecine et actualités santé.'],
        ];

        foreach ($categories as $cat) {
            Categorie::create([
                'nom' => $cat['nom'],
                'slug' => Str::slug($cat['nom']),
                'description' => $cat['description']
            ]);
        }
    }
}
