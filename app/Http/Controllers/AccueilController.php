<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;

class AccueilController extends Controller
{
    public function index()
    {
        $articles = Article::publie()
            ->with(['categorie', 'auteur'])
            ->latest()
            ->paginate(6);

        $categories = Categorie::withCount('articlesPublies')->get();

        return view('accueil.index', compact('articles', 'categories'));
    }

    public function show($slug)
    {
        $article = Article::publie()
            ->where('slug', $slug)
            ->with(['categorie', 'auteur'])
            ->firstOrFail();

        $articlesLies = Article::publie()
            ->where('categorie_id', $article->categorie_id)
            ->where('id', '!=', $article->id)
            ->latest()
            ->take(3)
            ->get();

        $categories = Categorie::withCount('articlesPublies')->get();

        return view('accueil.show', compact('article', 'articlesLies', 'categories'));
    }

    public function parCategorie($slug)
    {
        $categorie = Categorie::where('slug', $slug)->firstOrFail();
        
        $articles = Article::publie()
            ->where('categorie_id', $categorie->id)
            ->with(['categorie', 'auteur'])
            ->latest()
            ->paginate(6);

        $categories = Categorie::withCount('articlesPublies')->get();

        return view('accueil.categorie', compact('articles', 'categories', 'categorie'));
    }
}
