@extends('layouts.admin')

@section('page_title', 'Modifier l\'utilisateur')

@section('actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Retour</a>
@endsection

@section('content')
    {{-- ─── Formulaire de mise à jour ─── --}}
    <div class="card" style="max-width: 620px; margin: 0 auto 25px;">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">Nom complet *</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresse e-mail *</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Mot de passe --}}
            <div style="background: #f8f9fa; padding: 22px; border-radius: 14px; margin-bottom: 22px; border: var(--border);">
                <div style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); margin-bottom: 12px;">Changer le mot de passe</div>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 16px; font-weight: 500;">Laisser vide pour conserver le mot de passe actuel.</p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="8 caractères minimum">
                        @error('password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="form-label">Confirmation</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Répéter">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Rôle *</label>
                <select name="role" id="role" class="form-control" required {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <option value="visiteur"       {{ old('role', $user->role) == 'visiteur'       ? 'selected' : '' }}>Visiteur</option>
                    <option value="editeur"        {{ old('role', $user->role) == 'editeur'        ? 'selected' : '' }}>Éditeur</option>
                    <option value="administrateur" {{ old('role', $user->role) == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                </select>
                @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 6px; font-style: italic;">Vous ne pouvez pas modifier votre propre rôle.</div>
                @endif
                @error('role') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="actif" id="actif" value="1"
                           {{ old('actif', $user->actif) ? 'checked' : '' }}
                           {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <label for="actif">Compte actif (l'utilisateur peut se connecter)</label>
                </div>
                @if($user->id === auth()->id())
                    <input type="hidden" name="actif" value="1">
                @endif
            </div>

            <div style="margin-top: 30px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                    Mettre à jour
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    {{-- ─── Zone dangereuse (formulaire SÉPARÉ) ─── --}}
    @if($user->id !== auth()->id())
        <div style="max-width: 620px; margin: 0 auto; border: 1px solid #ffd7d7; border-radius: 20px; padding: 24px 28px; background: #fff8f8;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                <div>
                    <div style="font-size: 0.85rem; font-weight: 800; color: #c0392b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Zone dangereuse</div>
                    <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">
                        Supprimer <strong>{{ $user->name }}</strong>. Ses articles seront conservés mais désassociés.
                    </div>
                </div>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                      onsubmit="return confirm('Supprimer définitivement {{ addslashes($user->name) }} ? Ses articles seront conservés.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="white-space: nowrap;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        Supprimer l'utilisateur
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection
