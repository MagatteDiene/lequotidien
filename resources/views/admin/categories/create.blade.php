@extends('layouts.admin')

@section('page_title', 'Nouvelle catégorie')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="nom" class="form-label">Nom de la catégorie</label>
                <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description (facultatif)</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">Créer la catégorie</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
