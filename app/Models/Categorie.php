<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'slug', 'description'];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function articlesPublies()
    {
        return $this->hasMany(Article::class)->where('publie', true);
    }
}
