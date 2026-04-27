@extends('layouts.admin')

@section('page_title', 'Modifier l\'utilisateur')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div style="background: #f9f9f9; padding: 20px; border: 1px dashed #ddd; margin-bottom: 25px;">
                <p style="font-size: 0.85rem; margin-bottom: 15px; color: #666;">
                    Laissez les champs de mot de passe vides pour ne pas le modifier.
                </p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="password_confirmation" class="form-label">Confirmation</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Rôle</label>
                <select name="role" id="role" class="form-control" required {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <option value="visiteur" {{ old('role', $user->role) == 'visiteur' ? 'selected' : '' }}>Visiteur</option>
                    <option value="editeur" {{ old('role', $user->role) == 'editeur' ? 'selected' : '' }}>Éditeur</option>
                    <option value="administrateur" {{ old('role', $user->role) == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                </select>
                @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <p style="font-size: 0.75rem; color: #999; margin-top: 5px;">Vous ne pouvez pas changer votre propre rôle.</p>
                @endif
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', $user->actif) ? 'checked' : '' }} {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                <label for="actif">Compte actif</label>
                @if($user->id === auth()->id())
                     <input type="hidden" name="actif" value="1">
                @endif
            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
