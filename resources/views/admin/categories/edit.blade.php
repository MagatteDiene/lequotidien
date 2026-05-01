@extends('layouts.admin')

@section('page_title', 'Modifier la catégorie')

@section('actions')
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">← Retour</a>
@endsection

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nom" class="form-label">Nom de la catégorie *</label>
                <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $category->nom) }}" required>
                @error('nom') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description (facultatif)</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="margin-top: 30px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                    Mettre à jour
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
