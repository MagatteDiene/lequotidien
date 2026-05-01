@extends('layouts.admin')

@section('page_title', 'Modifier l\'article')

@section('actions')
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('article.show', $article->slug) }}" class="btn btn-secondary" target="_blank">Voir sur le site ↗</a>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">← Retour</a>
    </div>
@endsection

@section('content')
    {{-- ─── Formulaire de mise à jour ─── --}}
    <div class="card" style="max-width: 820px; margin: 0 auto 25px;">
        <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="titre" class="form-label">Titre de l'article *</label>
                <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre', $article->titre) }}" required>
                @error('titre') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="categorie_id" class="form-label">Catégorie *</label>
                    <select name="categorie_id" id="categorie_id" class="form-control" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('categorie_id', $article->categorie_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                        @endforeach
                    </select>
                    @error('categorie_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="image" class="form-label">Nouvelle image (optionnel)</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    @if($article->image)
                        <div style="margin-top: 10px; display: flex; align-items: center; gap: 10px;">
                            <img src="{{ str_starts_with($article->image, 'http') ? $article->image : asset('storage/' . $article->image) }}"
                                 style="height: 56px; width: 80px; object-fit: cover; border-radius: 10px; border: var(--border);"
                                 alt="Image actuelle">
                            <span style="font-size: 0.75rem; color: var(--text-muted);">Image actuelle</span>
                        </div>
                    @endif
                    @error('image') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="resume" class="form-label">Résumé / Chapô *</label>
                <textarea name="resume" id="resume" class="form-control" rows="3" required>{{ old('resume', $article->resume) }}</textarea>
                @error('resume') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="contenu" class="form-label">Contenu de l'article *</label>
                <textarea name="contenu" id="contenu" class="form-control" rows="16" required>{{ old('contenu', $article->contenu) }}</textarea>
                @error('contenu') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="publie" id="publie" {{ old('publie', $article->publie) ? 'checked' : '' }}>
                    <label for="publie">Article publié sur le site</label>
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Mettre à jour
                </button>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    {{-- ─── Zone dangereuse (formulaire SÉPARÉ, jamais imbriqué) ─── --}}
    <div style="max-width: 820px; margin: 0 auto; border: 1px solid #ffd7d7; border-radius: 20px; padding: 24px 28px; background: #fff8f8;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
            <div>
                <div style="font-size: 0.85rem; font-weight: 800; color: #c0392b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Zone dangereuse</div>
                <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">
                    Supprimer définitivement cet article. Cette action est irréversible.
                </div>
            </div>
            <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST"
                  onsubmit="return confirm('Supprimer définitivement cet article ? Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="white-space: nowrap;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    Supprimer l'article
                </button>
            </form>
        </div>
    </div>
@endsection
