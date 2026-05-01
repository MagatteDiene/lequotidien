@extends('layouts.admin')

@section('page_title', 'Nouvel utilisateur')

@section('actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Retour</a>
@endsection

@section('content')
    <div class="card" style="max-width: 620px; margin: 0 auto;">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nom complet *</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Prénom Nom">
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresse e-mail *</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required placeholder="nom@lequotidien.fr">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe *</label>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="8 caractères minimum">
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmation *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Répéter le mot de passe">
                </div>
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Rôle *</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="visiteur" {{ old('role') == 'visiteur' ? 'selected' : '' }}>Visiteur</option>
                    <option value="editeur" {{ old('role') == 'editeur' ? 'selected' : '' }}>Éditeur</option>
                    <option value="administrateur" {{ old('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                </select>
                @error('role') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', true) ? 'checked' : '' }}>
                    <label for="actif">Compte actif (peut se connecter)</label>
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    Créer l'utilisateur
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
