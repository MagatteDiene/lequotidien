@extends('layouts.app')

@section('title', $article->titre . ' | Le•Quotidien')

@section('content')
    @php
        $wordCount = str_word_count(strip_tags($article->contenu ?? ''));
        $readTime = max(1, ceil($wordCount / 200));
        $colors = ['#ff5722','#2196F3','#4CAF50','#9C27B0','#FF9800','#00BCD4','#F44336','#3F51B5'];
        $bg = $colors[abs(crc32(($article->auteur?->name ?? 'Anonyme'))) % count($colors)];
        $initials = collect(explode(' ', ($article->auteur?->name ?? 'Anonyme')))->map(fn($w) => strtoupper($w[0] ?? ''))->filter()->take(2)->join('');
    @endphp

    <article class="animate-fade-up" style="max-width: 860px; margin: 0 auto 80px;">

        {{-- Breadcrumb --}}
        <nav style="margin-bottom: 28px; display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); flex-wrap: wrap;">
            <a href="{{ route('accueil') }}" style="color: inherit; text-decoration: none; transition: color 0.2s;"
               onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">Accueil</a>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <a href="{{ route('categorie.articles', $article->categorie->slug) }}" style="color: inherit; text-decoration: none; transition: color 0.2s;"
               onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">{{ $article->categorie->nom }}</a>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">{{ Str::limit($article->titre, 40) }}</span>
        </nav>

        {{-- Article card --}}
        <div class="card-modern" style="padding: 0; overflow: hidden;">

            {{-- Cover image --}}
            <div style="height: 380px; position: relative; overflow: hidden;">
                @if($article->image)
                    <img src="{{ str_starts_with($article->image, 'http') ? $article->image : asset('storage/' . $article->image) }}"
                         alt="{{ $article->titre }}"
                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s ease;">
                @else
                    <div class="category-placeholder placeholder-{{ Str::slug($article->categorie->nom) }}" style="height: 380px; font-size: 2.5rem;">
                        {{ $article->categorie->nom }}
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div style="padding: 50px 56px;">

                {{-- Meta badges --}}
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
                    <span class="badge-cat" style="margin-bottom: 0;">{{ $article->categorie->nom }}</span>
                    <span class="reading-time">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {{ $readTime }} min de lecture
                    </span>
                    <span style="color: var(--text-muted); font-size: 0.82rem; font-weight: 600;">
                        {{ $article->created_at->translatedFormat('d F Y') }}
                    </span>
                </div>

                {{-- Title --}}
                <h1 style="font-size: 2.6rem; font-weight: 900; line-height: 1.15; margin-bottom: 32px; color: var(--text-main); letter-spacing: -0.8px;">{{ $article->titre }}</h1>

                {{-- Author --}}
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 40px; padding-bottom: 32px; border-bottom: var(--border);">
                    <div class="avatar-initials" style="width: 50px; height: 50px; background: {{ $bg }}; font-size: 1rem; border-radius: 16px;">{{ $initials }}</div>
                    <div>
                        <div style="font-weight: 800; color: var(--text-main); font-size: 1rem;">{{ $article->auteur?->name ?? 'Anonyme' }}</div>
                        <div style="font-size: 0.82rem; color: var(--text-muted); font-weight: 600; margin-top: 2px;">
                            @php
                                echo match($article->auteur?->role) {
                                    'administrateur' => 'Rédacteur en chef',
                                    'editeur'        => 'Journaliste',
                                    default          => 'Journaliste',
                                };
                            @endphp
                        </div>
                    </div>
                </div>

                {{-- Excerpt / chapô --}}
                <p style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 32px; line-height: 1.6; border-left: 4px solid var(--primary); padding-left: 22px; border-radius: 2px;">
                    {{ $article->resume }}
                </p>

                {{-- Body --}}
                <div style="font-size: 1.08rem; line-height: 1.9; color: #3d3d3d; margin-top: 10px;">
                    {!! nl2br(e($article->contenu)) !!}
                </div>

                {{-- Footer article --}}
                <div style="margin-top: 50px; padding-top: 30px; border-top: var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                    <a href="{{ route('categorie.articles', $article->categorie->slug) }}"
                       style="display: inline-flex; align-items: center; gap: 8px; color: var(--primary); text-decoration: none; font-weight: 700; font-size: 0.9rem; padding: 10px 20px; background: #fff3f0; border-radius: 50px; transition: all 0.2s;"
                       onmouseover="this.style.background='var(--primary)';this.style.color='white'" onmouseout="this.style.background='#fff3f0';this.style.color='var(--primary)'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                        Voir tous les articles {{ $article->categorie->nom }}
                    </a>
                    <a href="{{ route('accueil') }}"
                       style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: color 0.2s;"
                       onmouseover="this.style.color='var(--text-main)'" onmouseout="this.style.color='var(--text-muted)'">← Retour à l'accueil</a>
                </div>
            </div>
        </div>

        {{-- Related articles --}}
        @if($articlesLies->count() > 0)
            <div class="animate-fade-up delay-2" style="margin-top: 60px;">
                <h2 style="font-size: 1.5rem; font-weight: 900; margin-bottom: 24px; color: var(--text-main);">Sur le même sujet</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
                    @foreach($articlesLies as $lie)
                        @php
                            $lieColors = ['#ff5722','#2196F3','#4CAF50','#9C27B0','#FF9800','#00BCD4'];
                            $lieBg = $lieColors[abs(crc32(($lie->auteur?->name ?? 'Anonyme'))) % count($lieColors)];
                            $lieInitials = collect(explode(' ', ($lie->auteur?->name ?? 'Anonyme')))->map(fn($w) => strtoupper($w[0] ?? ''))->filter()->take(2)->join('');
                        @endphp
                        <div class="card-modern" style="padding: 0; overflow: hidden;">
                            <div class="img-zoom-container" style="height: 150px; border-radius: 0;">
                                @if($lie->image)
                                    <img src="{{ str_starts_with($lie->image, 'http') ? $lie->image : asset('storage/' . $lie->image) }}"
                                         alt="{{ $lie->titre }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div class="category-placeholder placeholder-{{ Str::slug($lie->categorie->nom) }}" style="font-size: 0.85rem; letter-spacing: 1px;">
                                        {{ $lie->categorie->nom }}
                                    </div>
                                @endif
                            </div>
                            <div style="padding: 18px 20px;">
                                <h3 style="font-size: 0.95rem; font-weight: 800; line-height: 1.45; margin-bottom: 12px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                    <a href="{{ route('article.show', $lie->slug) }}" style="text-decoration: none; color: var(--text-main); transition: color 0.2s;"
                                       onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-main)'">{{ $lie->titre }}</a>
                                </h3>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div class="avatar-initials" style="width: 26px; height: 26px; background: {{ $lieBg }}; font-size: 0.6rem; border-radius: 7px;">{{ $lieInitials }}</div>
                                    <span style="font-size: 0.78rem; color: var(--text-muted); font-weight: 600;">{{ $lie->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </article>
@endsection
