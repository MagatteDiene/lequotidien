@extends('layouts.admin')

@section('page_title', 'API Tokens')

@section('content')
    <div class="card">
        <h2 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 8px;">Générer un nouveau token</h2>
        <form action="{{ route('admin.tokens.store') }}" method="POST" style="display: flex; gap: 15px; align-items: flex-end; margin-bottom: 30px;">
            @csrf
            <div class="form-group" style="margin-bottom: 0; flex: 1;">
                <label for="nom" class="form-label">Nom du token (ex: Application Mobile)</label>
                <input type="text" name="nom" id="nom" class="form-control" required placeholder="Saisir un nom...">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 48px;">Générer</button>
        </form>

        <hr style="border: 0; border-top: 1px solid #eef0f2; margin-bottom: 30px;">

        <h2 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 8px;">Tokens actifs</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Token</th>
                    <th>Créateur</th>
                    <th>Date de création</th>
                    <th>Statut</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tokens as $token)
                    <tr>
                        <td><strong>{{ $token->nom }}</strong></td>
                        <td><code style="background: #f0f0f0; padding: 4px 8px; border-radius: 4px;">{{ Str::limit($token->token, 20) }}...</code></td>
                        <td>{{ $token->createur->name }}</td>
                        <td>{{ $token->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($token->actif)
                                <span class="badge badge-on">Actif</span>
                            @else
                                <span class="badge badge-off">Désactivé</span>
                            @endif
                        </td>
                        <td style="text-align: right; display: flex; gap: 10px; justify-content: flex-end;">
                            <form action="{{ route('admin.tokens.toggle', $token->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    {{ $token->actif ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.tokens.destroy', $token->id) }}" method="POST" onsubmit="return confirm('Supprimer ce token ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #999;">Aucun token généré pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
