@extends('layouts.admin')

@section('page_title', 'Modifier l\'article')

@section('content')
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="titre" class="form-label">Titre de l'article</label>
                <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre', $article->titre) }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="categorie_id" class="form-label">Catégorie</label>
                    <select name="categorie_id" id="categorie_id" class="form-control" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('categorie_id', $article->categorie_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="image" class="form-label">Image (laisser vide pour conserver)</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>
            </div>

            @if($article->image)
                <div style="margin-bottom: 20px;">
                    <p style="font-size: 0.8rem; margin-bottom: 5px;">Image actuelle :</p>
                    <img src="{{ asset('storage/' . $article->image) }}" style="height: 100px; border: 1px solid #ddd;">
                </div>
            @endif

            <div class="form-group">
                <label for="resume" class="form-label">Résumé (max 500 caractères)</label>
                <textarea name="resume" id="resume" class="form-control" rows="3" required>{{ old('resume', $article->resume) }}</textarea>
            </div>

            <div class="form-group">
                <label for="contenu" class="form-label">Contenu de l'article</label>
                <textarea name="contenu" id="contenu" class="form-control" rows="15" required>{{ old('contenu', $article->contenu) }}</textarea>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" name="publie" id="publie" {{ old('publie', $article->publie) ? 'checked' : '' }}>
                <label for="publie">Publier cet article</label>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
