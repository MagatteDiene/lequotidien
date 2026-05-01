@extends('layouts.app')

@section('content')
    {{-- Hero Article --}}
    @php $topArticle = $articles->first(); @endphp
    @if($topArticle)
    <div class="hero-section animate-fade-up" style="margin-bottom: 55px;">
        <div class="card-modern hero-card" style="display: flex; gap: 0; align-items: stretch; padding: 0; position: relative; overflow: hidden; min-height: 420px;">
            <div class="img-zoom-container" style="flex: 1.3; min-height: 360px; border-radius: 0; position: relative;">
                @if($topArticle->image)
                    <img src="{{ str_starts_with($topArticle->image, 'http') ? $topArticle->image : asset('storage/' . $topArticle->image) }}"
                         alt="{{ $topArticle->titre }}"
                         style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div class="category-placeholder placeholder-{{ Str::slug($topArticle->categorie->nom) }}" style="min-height: 360px;">
                        {{ $topArticle->categorie->nom }}
                    </div>
                @endif
                {{-- Gradient overlay --}}
                <div style="position: absolute; inset: 0; background: linear-gradient(to right, transparent 60%, white); pointer-events: none; display: none;" class="hero-overlay"></div>
            </div>

            <div class="hero-content" style="flex: 1; padding: 50px 45px; display: flex; flex-direction: column; justify-content: center;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px; flex-wrap: wrap;">
                    <span class="badge-cat" style="margin-bottom: 0;">{{ $topArticle->categorie->nom }}</span>
                    <span style="font-size: 0.78rem; color: var(--text-muted); font-weight: 600;">
                        À la une
                    </span>
                </div>
                <h1 style="font-size: 2.2rem; line-height: 1.25; margin-bottom: 18px; font-weight: 900; letter-spacing: -0.5px;">
                    <a href="{{ route('article.show', $topArticle->slug) }}" style="text-decoration: none; color: var(--text-main); transition: color 0.3s;"
                       onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-main)'">{{ $topArticle->titre }}</a>
                </h1>
                <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 32px; line-height: 1.7;">{{ Str::limit($topArticle->resume, 160) }}</p>
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                    <a href="{{ route('article.show', $topArticle->slug) }}" class="btn-modern">Lire l'article →</a>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        @php
                            $colors = ['#ff5722','#2196F3','#4CAF50','#9C27B0','#FF9800','#00BCD4','#F44336','#3F51B5'];
                            $bg = $colors[abs(crc32(($topArticle->auteur?->name ?? 'Anonyme'))) % count($colors)];
                            $initials = collect(explode(' ', ($topArticle->auteur?->name ?? 'Anonyme')))->map(fn($w) => strtoupper($w[0] ?? ''))->filter()->take(2)->join('');
                        @endphp
                        <div class="avatar-initials" style="width: 34px; height: 34px; background: {{ $bg }}; font-size: 0.72rem; border-radius: 10px;">{{ $initials }}</div>
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">{{ ($topArticle->auteur?->name ?? 'Anonyme') }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $topArticle->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Section header --}}
    <div class="animate-fade-up delay-1" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
        <h2 style="font-size: 1.6rem; font-weight: 900; color: var(--text-main);">Dernières actualités</h2>
        <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">{{ $articles->total() }} articles publiés</span>
    </div>

    {{-- Articles grid --}}
    <div class="articles-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(310px, 1fr)); gap: 24px;">
        @foreach($articles->skip(1) as $index => $article)
            @php
                $delay = ($index % 4) + 1;
                $wordCount = str_word_count(strip_tags($article->contenu ?? ''));
                $readTime = max(1, ceil($wordCount / 200));
                $colors2 = ['#ff5722','#2196F3','#4CAF50','#9C27B0','#FF9800','#00BCD4','#F44336','#3F51B5'];
                $bg2 = $colors2[abs(crc32(($article->auteur?->name ?? 'Anonyme'))) % count($colors2)];
                $initials2 = collect(explode(' ', ($article->auteur?->name ?? 'Anonyme')))->map(fn($w) => strtoupper($w[0] ?? ''))->filter()->take(2)->join('');
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
                            <div class="avatar-initials" style="width: 30px; height: 30px; background: {{ $bg2 }}; font-size: 0.65rem; border-radius: 8px;">{{ $initials2 }}</div>
                            <span style="font-size: 0.82rem; font-weight: 700; color: var(--text-main);">{{ ($article->auteur?->name ?? 'Anonyme') }}</span>
                        </div>
                        <span style="font-size: 0.78rem; color: var(--text-muted); font-weight: 600;">{{ $article->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="animate-fade-up delay-4" style="margin-top: 50px;">
        {{ $articles->links('vendor.pagination.custom') }}
    </div>
@endsection
