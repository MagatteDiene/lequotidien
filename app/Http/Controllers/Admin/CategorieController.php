<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::withCount('articles')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom',
            'description' => 'nullable|string',
        ]);

        Categorie::create([
            'nom' => $request->nom,
            'slug' => Str::slug($request->nom),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée.');
    }

    public function edit(Categorie $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Categorie $category)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'nom' => $request->nom,
            'slug' => Str::slug($request->nom),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Categorie $category)
    {
        if ($category->articles()->exists()) {
            return redirect()->back()->with('error', 'Impossible de supprimer une catégorie contenant des articles.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée.');
    }
}
