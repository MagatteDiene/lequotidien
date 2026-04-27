@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
    <div class="stats-grid animate-fade-up">
        <div class="stat-card">
            <div style="width: 50px; height: 50px; background: #fff3f0; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: var(--primary);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <h3>Articles</h3>
            <div class="value">{{ $stats['articles'] }}</div>
        </div>
        <div class="stat-card delay-1 animate-fade-up" style="opacity: 0;">
            <div style="width: 50px; height: 50px; background: #e8f5e9; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #2e7d32;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <h3>Publiés</h3>
            <div class="value">{{ $stats['publies'] }}</div>
        </div>
        <div class="stat-card delay-2 animate-fade-up" style="opacity: 0;">
            <div style="width: 50px; height: 50px; background: #e3f2fd; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #1976d2;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </div>
            <h3>Catégories</h3>
            <div class="value">{{ $stats['categories'] }}</div>
        </div>
        @if(auth()->user()->isAdministrateur())
            <div class="stat-card delay-3 animate-fade-up" style="opacity: 0;">
                <div style="width: 50px; height: 50px; background: #f3e5f5; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #7b1fa2;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 1 1 0 7.75"></path></svg>
                </div>
                <h3>Utilisateurs</h3>
                <div class="value">{{ $stats['users'] }}</div>
            </div>
        @endif
    </div>

    <div class="card animate-fade-up delay-2" style="opacity: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="font-size: 1.25rem; font-weight: 800;">Derniers articles</h2>
            <a href="{{ route('admin.articles.index') }}" style="color: var(--primary); text-decoration: none; font-size: 0.9rem; font-weight: 700;">Voir tout</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Auteur</th>
                    <th>Statut</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($derniersArticles as $article)
                    <tr>
                        <td style="max-width: 300px;">
                            <div style="font-weight: 800; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $article->titre }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $article->created_at->format('d/m/Y') }}</div>
                        </td>
                        <td><span class="badge badge-editeur" style="font-size: 0.65rem;">{{ $article->categorie->nom }}</span></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <img src="https://api.dicebear.com/7.x/notionists/svg?seed={{ urlencode($article->auteur->name) }}&backgroundColor=e3f2fd,f1f2f6" alt="{{ $article->auteur->name }}" style="width: 24px; height: 24px; border-radius: 50%;">
                                <span style="font-size: 0.85rem; font-weight: 600;">{{ $article->auteur->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if($article->publie)
                                <span class="badge badge-on">Public</span>
                            @else
                                <span class="badge badge-off">Brouillon</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; transition: transform 0.2s;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
