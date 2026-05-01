@extends('layouts.app')

@section('title', 'Connexion | Le•Quotidien')

@section('content')
    <div style="display: flex; justify-content: center; align-items: center; min-height: 75vh; padding: 20px;">
        <div class="card-modern animate-fade-up" style="width: 100%; max-width: 440px; padding: 48px 44px; background: white;">

            {{-- Logo --}}
            <div style="text-align: center; margin-bottom: 36px;">
                <a href="{{ route('accueil') }}" class="logo" style="justify-content: center; font-size: 1.8rem; display: inline-flex;">Le<span>•</span>Quotidien</a>
                <h2 style="font-size: 1.4rem; font-weight: 900; color: var(--text-main); margin-top: 20px; margin-bottom: 8px;">Espace rédaction</h2>
                <p style="color: var(--text-muted); font-weight: 600; font-size: 0.88rem;">Connectez-vous pour gérer le journal</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email') }}" required autofocus
                           placeholder="nom@lequotidien.fr">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control"
                           required placeholder="••••••••">
                </div>

                <div style="margin-bottom: 30px;">
                    <div class="checkbox-group">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Rester connecté</label>
                    </div>
                </div>

                <button type="submit" class="btn-modern" style="width: 100%; padding: 16px; font-size: 1rem; border: none; text-align: center; justify-content: center; display: flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    S'identifier
                </button>
            </form>

            <div style="text-align: center; margin-top: 28px; color: var(--text-muted); font-size: 0.82rem; font-weight: 600;">
                Besoin d'accès ? <a href="#" style="color: var(--primary); text-decoration: none; font-weight: 800;">Contacter l'administrateur</a>
            </div>
        </div>
    </div>
@endsection
