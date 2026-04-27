@extends('layouts.app')

@section('content')
    <div class="hero-section animate-fade-up" style="margin-bottom: 60px;">
        <div class="card-modern hero-card" style="display: flex; gap: 40px; align-items: center; padding: 40px; position: relative; overflow: hidden; min-height: 400px;">
            @php $topArticle = $articles->first(); @endphp
            @if($topArticle)
                <div class="img-zoom-container" style="flex: 1.2; height: 320px; border-radius: 20px;">
                    @if($topArticle->image)
                        <img src="{{ str_starts_with($topArticle->image, 'http') ? $topArticle->image : asset('storage/' . $topArticle->image) }}" alt="{{ $topArticle->titre }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="category-placeholder placeholder-{{ Str::slug($topArticle->categorie->nom) }}">
                            {{ $topArticle->categorie->nom }}
                        </div>
                    @endif
                </div>
                <div class="hero-content" style="flex: 1;">
                    <span class="badge-cat">{{ $topArticle->categorie->nom }}</span>
                    <h1 style="font-size: 2.5rem; line-height: 1.2; margin-bottom: 20px; font-weight: 800; color: var(--text-main);">{{ $topArticle->titre }}</h1>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px;">{{ Str::limit($topArticle->resume, 150) }}</p>
                    <a href="{{ route('article.show', $topArticle->slug) }}" class="btn-modern">Lire l'article</a>
                </div>
            @endif
        </div>
    </div>

    <div class="animate-fade-up delay-1" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="font-size: 1.8rem; font-weight: 800;">Dernières actualités</h2>
    </div>

    <div class="articles-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
        @foreach($articles->skip(1) as $index => $article)
            @php $delay = ($index % 4) + 1; @endphp
            <div class="card-modern animate-fade-up delay-{{ $delay }}" style="padding: 15px; display: flex; flex-direction: column;">
                <div class="img-zoom-container" style="height: 160px; border-radius: 18px; margin-bottom: 20px; position: relative;">
                    @if($article->image)
                        <img src="{{ str_starts_with($article->image, 'http') ? $article->image : asset('storage/' . $article->image) }}" alt="{{ $article->titre }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="category-placeholder placeholder-{{ Str::slug($article->categorie->nom) }}" style="font-size: 0.9rem; letter-spacing: 1px;">
                            {{ $article->categorie->nom }}
                        </div>
                    @endif
                </div>
                <div style="padding: 0 10px 10px; flex-grow: 1; display: flex; flex-direction: column;">
                    <h3 style="font-size: 1.25rem; font-weight: 800; line-height: 1.4; margin-bottom: 12px; height: 3.5em; overflow: hidden; color: var(--text-main);">
                        <a href="{{ route('article.show', $article->slug) }}" style="text-decoration: none; color: inherit;">{{ $article->titre }}</a>
                    </h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 20px; height: 3em; overflow: hidden; flex-grow: 1;">{{ $article->resume }}</p>
                    
                    <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f0f2f5; padding-top: 15px; margin-top: 15px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="https://api.dicebear.com/7.x/notionists/svg?seed={{ urlencode($article->auteur->name) }}&backgroundColor=ff5722,ffcc80" alt="{{ $article->auteur->name }}" style="width: 32px; height: 32px; border-radius: 50%; background: #f0f2f5;">
                            <span style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">{{ $article->auteur->name }}</span>
                        </div>
                        <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">{{ $article->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pagination-wrapper animate-fade-up delay-4" style="margin-top: 60px;">
        {{ $articles->links('vendor.pagination.custom') }}
    </div>
@endsection
