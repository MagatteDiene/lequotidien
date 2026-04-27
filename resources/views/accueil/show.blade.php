@extends('layouts.app')

@section('content')
    <article class="article-detail animate-fade-up" style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
        <nav style="margin-bottom: 30px; display: flex; align-items: center; gap: 10px; font-size: 0.9rem; font-weight: 600; color: var(--text-muted);">
            <a href="{{ route('accueil') }}" style="color: inherit; text-decoration: none; transition: color 0.3s;">Accueil</a>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            <a href="{{ route('categorie.articles', $article->categorie->slug) }}" style="color: inherit; text-decoration: none; transition: color 0.3s;">{{ $article->categorie->nom }}</a>
        </nav>

        <div class="card-modern" style="padding: 0; overflow: hidden;">
            <div style="height: 350px; width: 100%;">
                @if($article->image)
                    <img src="{{ str_starts_with($article->image, 'http') ? $article->image : asset('storage/' . $article->image) }}" alt="{{ $article->titre }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div class="category-placeholder placeholder-{{ Str::slug($article->categorie->nom) }}" style="font-size: 3rem;">
                        {{ $article->categorie->nom }}
                    </div>
                @endif
            </div>
            
            <div style="padding: 50px 60px;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
                    <span class="badge-cat" style="margin-bottom: 0;">{{ $article->categorie->nom }}</span>
                    <span style="color: var(--text-muted); font-size: 0.9rem; font-weight: 600;">{{ $article->created_at->translatedFormat('d F Y') }}</span>
                </div>

                <h1 style="font-size: 3rem; font-weight: 900; line-height: 1.1; margin-bottom: 30px; color: var(--text-main);">{{ $article->titre }}</h1>

                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 40px; padding-bottom: 30px; border-bottom: 1px solid #f0f2f5;">
                    <img src="https://api.dicebear.com/7.x/notionists/svg?seed={{ urlencode($article->auteur->name) }}&backgroundColor=ff5722,ffcc80" alt="{{ $article->auteur->name }}" style="width: 50px; height: 50px; border-radius: 50%; background: #f0f2f5;">
                    <div>
                        <div style="font-weight: 800; color: var(--text-main); font-size: 1.1rem;">{{ $article->auteur->name }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">Journaliste expert</div>
                    </div>
                </div>

                <div class="article-body" style="font-size: 1.15rem; line-height: 1.8; color: #4a4a4a;">
                    <p style="font-size: 1.4rem; font-weight: 700; color: var(--text-main); margin-bottom: 30px; line-height: 1.5; border-left: 5px solid var(--primary); padding-left: 25px; border-radius: 4px;">
                        {{ $article->resume }}
                    </p>
                    
                    <div style="margin-top: 30px;">
                        {!! nl2br(e($article->contenu)) !!}
                    </div>
                </div>
            </div>
        </div>

        @if($articlesLies->count() > 0)
            <div class="animate-fade-up delay-2" style="margin-top: 80px;">
                <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 30px;">Sur le même sujet</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                    @foreach($articlesLies as $lie)
                        <div class="card-modern" style="padding: 15px; display: flex; flex-direction: column;">
                             <div class="img-zoom-container" style="height: 160px; border-radius: 15px; overflow: hidden; margin-bottom: 15px;">
                                @if($lie->image)
                                    <img src="{{ str_starts_with($lie->image, 'http') ? $lie->image : asset('storage/' . $lie->image) }}" alt="{{ $lie->titre }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div class="category-placeholder placeholder-{{ Str::slug($lie->categorie->nom) }}" style="font-size: 1rem; letter-spacing: 1px;">
                                        {{ $lie->categorie->nom }}
                                    </div>
                                @endif
                             </div>
                             <h3 style="font-size: 1.1rem; font-weight: 800; line-height: 1.4; margin-bottom: 10px;">
                                <a href="{{ route('article.show', $lie->slug) }}" style="text-decoration: none; color: var(--text-main); transition: color 0.3s;">{{ $lie->titre }}</a>
                             </h3>
                             <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">{{ $lie->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </article>
@endsection
