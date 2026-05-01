@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
    {{-- Stats --}}
    <div class="stats-grid animate-fade-up">
        <a href="{{ route('admin.articles.index') }}" class="stat-card" style="color: inherit; text-decoration: none;">
            <div class="stat-icon" style="background: #fff3f0; color: var(--primary);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <h3>Total articles</h3>
            <div class="value">{{ $stats['articles'] }}</div>
            <div class="value-sub">dont {{ $stats['publies'] }} publiés</div>
        </a>

        <a href="{{ route('admin.articles.index') }}" class="stat-card animate-fade-up delay-1" style="color: inherit; text-decoration: none;">
            <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <h3>Publiés</h3>
            <div class="value">{{ $stats['publies'] }}</div>
            <div class="value-sub">{{ $stats['articles'] > 0 ? round($stats['publies'] / $stats['articles'] * 100) : 0 }}% du total</div>
        </a>

        <a href="{{ route('admin.categories.index') }}" class="stat-card animate-fade-up delay-2" style="color: inherit; text-decoration: none;">
            <div class="stat-icon" style="background: #e3f2fd; color: #1565c0;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            </div>
            <h3>Catégories</h3>
            <div class="value">{{ $stats['categories'] }}</div>
            <div class="value-sub">rubriques actives</div>
        </a>

        @if(auth()->user()->isAdministrateur())
            <a href="{{ route('admin.users.index') }}" class="stat-card animate-fade-up delay-3" style="color: inherit; text-decoration: none;">
                <div class="stat-icon" style="background: #f3e5f5; color: #6a1b9a;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 1 1 0 7.75"/></svg>
                </div>
                <h3>Utilisateurs</h3>
                <div class="value">{{ $stats['users'] }}</div>
                <div class="value-sub">membres de la rédaction</div>
            </a>
        @endif
    </div>

    {{-- Recent Articles --}}
    <div class="card animate-fade-up delay-2">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.1rem; font-weight: 800; color: var(--text-main);">Derniers articles</h2>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary btn-sm">Voir tout →</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Auteur</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($derniersArticles as $article)
                    @php
                        $colors = ['#ff5722','#2196F3','#4CAF50','#9C27B0','#FF9800','#00BCD4'];
                        $auteurName = $article->auteur?->name ?? 'Auteur supprimé';
                        $bg = $colors[abs(crc32($auteurName)) % count($colors)];
                        $initials = collect(explode(' ', $auteurName))->map(fn($w) => strtoupper($w[0] ?? ''))->filter()->take(2)->join('');
                    @endphp
                    <tr>
                        <td style="max-width: 280px;">
                            <div style="font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.9rem;">{{ $article->titre }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 2px;">{{ $article->created_at->format('d/m/Y à H:i') }}</div>
                        </td>
                        <td>
                            <span class="badge badge-editeur" style="font-size: 0.62rem;">{{ $article->categorie->nom }}</span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="avatar-initials" style="width: 26px; height: 26px; background: {{ $bg }}; font-size: 0.6rem;">{{ $initials }}</div>
                                <span style="font-size: 0.85rem; font-weight: 600;">{{ $auteurName }}</span>
                            </div>
                        </td>
                        <td style="font-size: 0.82rem; color: var(--text-muted); white-space: nowrap;">{{ $article->created_at->diffForHumans() }}</td>
                        <td>
                            @if($article->publie)
                                <span class="badge badge-on">Publié</span>
                            @else
                                <span class="badge badge-off">Brouillon</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-secondary btn-sm">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
