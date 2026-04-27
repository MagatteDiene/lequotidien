@extends('layouts.admin')

@section('page_title', 'Nouvel article')

@section('content')
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="titre" class="form-label">Titre de l'article</label>
                <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre') }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="categorie_id" class="form-label">Catégorie</label>
                    <select name="categorie_id" id="categorie_id" class="form-control" required>
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="image" class="form-label">Image mise en avant</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="resume" class="form-label">Résumé (max 500 caractères)</label>
                <textarea name="resume" id="resume" class="form-control" rows="3" required>{{ old('resume') }}</textarea>
            </div>

            <div class="form-group">
                <label for="contenu" class="form-label">Contenu de l'article</label>
                <textarea name="contenu" id="contenu" class="form-control" rows="15" required>{{ old('contenu') }}</textarea>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" name="publie" id="publie" {{ old('publie', true) ? 'checked' : '' }}>
                <label for="publie">Publier immédiatement</label>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">Enregistrer l'article</button>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
