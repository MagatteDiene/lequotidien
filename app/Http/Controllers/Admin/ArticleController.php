<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['categorie', 'auteur'])->latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Categorie::all();
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'resume' => 'required|string|max:500',
            'contenu' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        Article::create([
            'titre' => $request->titre,
            'resume' => $request->resume,
            'contenu' => $request->contenu,
            'categorie_id' => $request->categorie_id,
            'publie' => $request->has('publie'),
            'image' => $imagePath,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Article créé avec succès.');
    }

    public function edit(Article $article)
    {
        $categories = Categorie::all();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'resume' => 'required|string|max:500',
            'contenu' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $article->image = $request->file('image')->store('articles', 'public');
        }

        $article->update([
            'titre' => $request->titre,
            'resume' => $request->resume,
            'contenu' => $request->contenu,
            'categorie_id' => $request->categorie_id,
            'publie' => $request->has('publie'),
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Article mis à jour.');
    }

    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Article supprimé.');
    }
}
