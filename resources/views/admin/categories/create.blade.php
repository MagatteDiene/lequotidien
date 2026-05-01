@extends('layouts.admin')

@section('page_title', 'Nouvelle catégorie')

@section('actions')
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">← Retour</a>
@endsection

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nom" class="form-label">Nom de la catégorie *</label>
                <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required placeholder="ex: Politique, Technologie, Sport...">
                @error('nom') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description (facultatif)</label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Décrivez brièvement cette rubrique...">{{ old('description') }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="margin-top: 30px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Créer la catégorie
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
