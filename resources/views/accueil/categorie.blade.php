@extends('layouts.app')

@section('content')
    <div class="category-header animate-fade-up" style="margin-bottom: 60px; text-align: center; background: white; padding: 60px 20px; border-radius: var(--radius-lg); box-shadow: var(--shadow);">
        <span class="badge-cat" style="display: inline-block; margin-bottom: 20px;">Rubrique</span>
        <h1 style="font-size: 3.5rem; font-weight: 900; color: var(--text-main); margin-bottom: 15px;">{{ $categorie->nom }}</h1>
        <div style="font-size: 0.9rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 2px;">{{ $articles->total() }} articles publiés</div>
        @if($categorie->description)
            <p style="margin-top: 25px; font-size: 1.1rem; color: var(--text-muted); max-width: 700px; margin-left: auto; margin-right: auto; line-height: 1.6;">{{ $categorie->description }}</p>
        @endif
    </div>

    <div class="articles-grid animate-fade-up delay-1" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
        @forelse($articles as $index => $article)
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
        @empty
            <div class="card-modern" style="grid-column: 1 / -1; padding: 100px; text-align: center;">
                <h3 style="color: var(--text-muted);">Aucun article dans cette catégorie pour le moment.</h3>
                <a href="{{ route('accueil') }}" class="btn-modern" style="display: inline-block; margin-top: 20px;">Retour à l'accueil</a>
            </div>
        @endforelse
    </div>

    <div class="pagination-wrapper animate-fade-up delay-4" style="margin-top: 60px;">
        {{ $articles->links('vendor.pagination.custom') }}
    </div>
@endsection
