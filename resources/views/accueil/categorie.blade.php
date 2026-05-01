@extends('layouts.app')

@section('title', $categorie->nom . ' | Le•Quotidien')

@section('content')
    {{-- Category Header --}}
    <div class="animate-fade-up" style="margin-bottom: 50px; text-align: center; background: white; padding: 55px 30px; border-radius: var(--radius-lg); box-shadow: var(--shadow); position: relative; overflow: hidden;">
        <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,87,34,0.03) 0%, transparent 60%); pointer-events: none;"></div>
        <span class="badge-cat" style="display: inline-block; margin-bottom: 16px;">Rubrique</span>
        <h1 style="font-size: 3rem; font-weight: 900; color: var(--text-main); margin-bottom: 12px; letter-spacing: -1px;">{{ $categorie->nom }}</h1>
        <div style="font-size: 0.82rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 2px;">{{ $articles->total() }} article{{ $articles->total() > 1 ? 's' : '' }} publié{{ $articles->total() > 1 ? 's' : '' }}</div>
        @if($categorie->description)
            <p style="margin-top: 22px; font-size: 1rem; color: var(--text-muted); max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.7;">{{ $categorie->description }}</p>
        @endif
    </div>

    {{-- Articles grid --}}
    <div class="articles-grid animate-fade-up delay-1" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(310px, 1fr)); gap: 24px;">
        @forelse($articles as $index => $article)
            @php
                $delay = ($index % 4) + 1;
                $wordCount = str_word_count(strip_tags($article->contenu ?? ''));
                $readTime = max(1, ceil($wordCount / 200));
                $colors = ['#ff5722','#2196F3','#4CAF50','#9C27B0','#FF9800','#00BCD4','#F44336','#3F51B5'];
                $bg = $colors[abs(crc32(($article->auteur?->name ?? 'Anonyme'))) % count($colors)];
                $initials = collect(explode(' ', ($article->auteur?->name ?? 'Anonyme')))->map(fn($w) => strtoupper($w[0] ?? ''))->filter()->take(2)->join('');
            @endphp
            <div class="card-modern animate-fade-up delay-{{ $delay }}" style="padding: 0; display: flex; flex-direction: column; overflow: hidden;">
                <div class="img-zoom-container" style="height: 180px; border-radius: 0; flex-shrink: 0;">
                    @if($article->image)
                        <img src="{{ str_starts_with($article->image, 'http') ? $article->image : asset('storage/' . $article->image) }}"
                             alt="{{ $article->titre }}"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="category-placeholder placeholder-{{ Str::slug($article->categorie->nom) }}" style="font-size: 0.85rem; letter-spacing: 1px;">
                            {{ $article->categorie->nom }}
                        </div>
                    @endif
                </div>

                <div style="padding: 22px 22px 18px; flex-grow: 1; display: flex; flex-direction: column;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <span class="badge-cat" style="margin-bottom: 0;">{{ $article->categorie->nom }}</span>
                        <span class="reading-time">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ $readTime }} min
                        </span>
                    </div>

                    <h3 style="font-size: 1.1rem; font-weight: 800; line-height: 1.4; margin-bottom: 10px; flex-grow: 1; color: var(--text-main); overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                        <a href="{{ route('article.show', $article->slug) }}" style="text-decoration: none; color: inherit; transition: color 0.2s;"
                           onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='inherit'">{{ $article->titre }}</a>
                    </h3>

                    <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 18px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.6;">{{ $article->resume }}</p>

                    <div style="display: flex; align-items: center; justify-content: space-between; border-top: var(--border); padding-top: 14px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div class="avatar-initials" style="width: 30px; height: 30px; background: {{ $bg }}; font-size: 0.65rem; border-radius: 8px;">{{ $initials }}</div>
                            <span style="font-size: 0.82rem; font-weight: 700; color: var(--text-main);">{{ ($article->auteur?->name ?? 'Anonyme') }}</span>
                        </div>
                        <span style="font-size: 0.78rem; color: var(--text-muted); font-weight: 600;">{{ $article->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-modern" style="grid-column: 1 / -1; padding: 80px 40px; text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 20px;">📰</div>
                <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--text-muted); margin-bottom: 12px;">Aucun article dans cette rubrique</h3>
                <p style="color: var(--text-muted); margin-bottom: 25px; font-size: 0.9rem;">Les articles de cette catégorie seront bientôt disponibles.</p>
                <a href="{{ route('accueil') }}" class="btn-modern">← Retour à l'accueil</a>
            </div>
        @endforelse
    </div>

    <div class="animate-fade-up delay-4" style="margin-top: 50px;">
        {{ $articles->links('vendor.pagination.custom') }}
    </div>
@endsection
