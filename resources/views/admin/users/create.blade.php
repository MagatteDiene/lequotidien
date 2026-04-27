@extends('layouts.admin')

@section('page_title', 'Nouvel utilisateur')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmation</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Rôle</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="visiteur" {{ old('role') == 'visiteur' ? 'selected' : '' }}>Visiteur</option>
                    <option value="editeur" {{ old('role') == 'editeur' ? 'selected' : '' }}>Éditeur</option>
                    <option value="administrateur" {{ old('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                </select>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', true) ? 'checked' : '' }}>
                <label for="actif">Compte actif</label>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
