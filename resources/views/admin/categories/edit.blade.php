@extends('layouts.admin')

@section('page_title', 'Modifier la catégorie')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="nom" class="form-label">Nom de la catégorie</label>
                <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $category->nom) }}" required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description (facultatif)</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
