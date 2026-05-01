@extends('layouts.admin')

@section('page_title', 'Nouvel article')

@section('actions')
    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">← Retour</a>
@endsection

@section('content')
    <div class="card" style="max-width: 820px; margin: 0 auto;">
        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="titre" class="form-label">Titre de l'article *</label>
                <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre') }}" required placeholder="Saisissez un titre accrocheur...">
                @error('titre') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="categorie_id" class="form-label">Catégorie *</label>
                    <select name="categorie_id" id="categorie_id" class="form-control" required>
                        <option value="">Sélectionner...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                        @endforeach
                    </select>
                    @error('categorie_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="image" class="form-label">Image de couverture</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 6px;">JPG, PNG ou WebP — max 2 Mo</div>
                    @error('image') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="resume" class="form-label">Résumé / Chapô *</label>
                <textarea name="resume" id="resume" class="form-control" rows="3" required placeholder="Un court résumé de l'article (affiché sur la page d'accueil)...">{{ old('resume') }}</textarea>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 6px;">Maximum 500 caractères</div>
                @error('resume') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="contenu" class="form-label">Contenu de l'article *</label>
                <textarea name="contenu" id="contenu" class="form-control" rows="16" required placeholder="Rédigez votre article ici...">{{ old('contenu') }}</textarea>
                @error('contenu') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="publie" id="publie" {{ old('publie', true) ? 'checked' : '' }}>
                    <label for="publie">Publier immédiatement sur le site</label>
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Enregistrer
                </button>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
