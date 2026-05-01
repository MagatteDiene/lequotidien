@extends('layouts.app')

@section('title', 'Mon profil | Le•Quotidien')

@section('content')
    <div style="max-width: 620px; margin: 0 auto 80px;">

        {{-- Breadcrumb --}}
        <nav style="margin-bottom: 24px; display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">
            <a href="{{ route('accueil') }}" style="color: inherit; text-decoration: none;">Accueil</a>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <span>Mon profil</span>
        </nav>

        {{-- Header --}}
        <div class="animate-fade-up" style="display: flex; align-items: center; gap: 20px; margin-bottom: 35px;">
            @php
                $colors = ['#ff5722','#2196F3','#4CAF50','#9C27B0','#FF9800','#00BCD4'];
                $bg = $colors[abs(crc32($user->name)) % count($colors)];
                $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper($w[0] ?? ''))->filter()->take(2)->join('');
            @endphp
            <div style="width: 70px; height: 70px; border-radius: 22px; background: {{ $bg }}; color: white;
                        display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 900; flex-shrink: 0;">
                {{ $initials }}
            </div>
            <div>
                <h1 style="font-size: 1.8rem; font-weight: 900; color: var(--text-main);">{{ $user->name }}</h1>
                <div style="display: flex; align-items: center; gap: 10px; margin-top: 6px;">
                    <span class="badge-cat" style="margin-bottom: 0; text-transform: capitalize;">{{ $user->role }}</span>
                    <span style="font-size: 0.82rem; color: var(--text-muted); font-weight: 600;">{{ $user->email }}</span>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="card-modern animate-fade-up delay-1" style="padding: 40px;">
            <h2 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 28px; color: var(--text-main);">Modifier mes informations</h2>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="name" class="form-label">Nom complet</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <div style="font-size: 0.8rem; color: #c0392b; font-weight: 600; margin-top: 6px;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email') <div style="font-size: 0.8rem; color: #c0392b; font-weight: 600; margin-top: 6px;">{{ $message }}</div> @enderror
                </div>

                <hr style="border: 0; border-top: var(--border); margin: 30px 0;">

                <h3 style="font-size: 0.9rem; font-weight: 800; color: var(--text-main); margin-bottom: 18px; text-transform: uppercase; letter-spacing: 0.5px;">Changer le mot de passe</h3>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 22px; font-weight: 500;">Laissez vide pour conserver votre mot de passe actuel.</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="8 caractères minimum">
                        @error('password') <div style="font-size: 0.8rem; color: #c0392b; font-weight: 600; margin-top: 6px;">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="password_confirmation" class="form-label">Confirmation</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Répéter">
                    </div>
                </div>

                <div style="margin-top: 35px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-modern" style="border: none; cursor: pointer; font-family: inherit; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Enregistrer les modifications
                    </button>
                    <a href="{{ route('accueil') }}" style="display: inline-flex; align-items: center; padding: 13px 24px; background: #f1f2f6; color: var(--text-muted); border-radius: 50px; font-weight: 700; font-size: 0.88rem; text-decoration: none; transition: all 0.2s;"
                       onmouseover="this.style.background='#e4e6eb';this.style.color='var(--text-main)'" onmouseout="this.style.background='#f1f2f6';this.style.color='var(--text-muted)'">
                        Annuler
                    </a>
                </div>
            </form>
        </div>

        {{-- Info box --}}
        <div class="animate-fade-up delay-2" style="margin-top: 20px; padding: 18px 22px; background: #f8f9fa; border-radius: 16px; border: var(--border); display: flex; align-items: flex-start; gap: 12px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#636e72" stroke-width="2" style="flex-shrink: 0; margin-top: 1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div>
                <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">Gestion de votre compte</div>
                <div style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.6;">Votre rôle (<strong>{{ $user->role }}</strong>) ne peut être modifié que par un administrateur. Pour toute question sur vos accès, contactez votre administrateur.</div>
            </div>
        </div>
    </div>
@endsection
