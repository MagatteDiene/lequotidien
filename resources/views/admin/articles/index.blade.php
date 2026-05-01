@extends('layouts.admin')

@section('page_title', 'Gestion des articles')

@section('actions')
    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nouvel article
    </a>
@endsection

@section('content')
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Auteur</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td style="max-width: 280px;">
                            <div style="font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.9rem;">{{ $article->titre }}</div>
                        </td>
                        <td>
                            <span class="badge badge-editeur" style="font-size: 0.62rem;">{{ $article->categorie->nom }}</span>
                        </td>
                        <td style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">
                            {{ $article->auteur?->name ?? '—' }}
                        </td>
                        <td style="font-size: 0.82rem; color: var(--text-muted); white-space: nowrap;">
                            {{ $article->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($article->publie)
                                <span class="badge badge-on">En ligne</span>
                            @else
                                <span class="badge badge-off">Brouillon</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-secondary btn-sm">Modifier</a>
                                <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST"
                                      onsubmit="return confirm('Supprimer cet article ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 60px; color: var(--text-muted);">
                            Aucun article pour le moment.
                            <a href="{{ route('admin.articles.create') }}" style="color: var(--primary); font-weight: 700; text-decoration: none; margin-left: 8px;">Créer le premier →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
