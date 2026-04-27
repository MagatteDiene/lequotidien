<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'slug', 'resume', 'contenu', 'image', 'publie', 'categorie_id', 'user_id'
    ];

    protected $casts = [
        'publie' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->titre);
            }
        });

        static::updating(function ($article) {
            $article->slug = Str::slug($article->titre);
        });
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublie($query)
    {
        return $query->where('publie', true);
    }

    public function scopeParCategorie($query, $slug)
    {
        return $query->whereHas('categorie', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });
    }
}
