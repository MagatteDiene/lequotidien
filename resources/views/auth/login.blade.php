@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: center; align-items: center; min-height: 70vh; padding: 20px;">
        <div class="card-modern animate-fade-up" style="width: 100%; max-width: 450px; padding: 50px; background: white; border-radius: 30px;">
            <div style="text-align: center; margin-bottom: 40px;">
                <h2 style="font-size: 2rem; font-weight: 900; color: var(--text-main); margin-bottom: 10px;">Connexion</h2>
                <p style="color: var(--text-muted); font-weight: 600; font-size: 0.95rem;">Accédez à l'espace rédaction</p>
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
                    <label for="email" class="form-label">E-mail Professionnel</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="nom@lequotidien.fr">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 35px;">
                    <label style="display: flex; align-items: center; gap: 10px; font-size: 0.85rem; color: var(--text-muted); cursor: pointer; font-weight: 600;">
                        <input type="checkbox" name="remember" id="remember" style="accent-color: var(--primary); width: 18px; height: 18px;">
                        Se souvenir de moi
                    </label>
                </div>

                <button type="submit" class="btn-modern" style="width: 100%; padding: 18px; font-size: 1rem; border: none; cursor: pointer;">S'identifier</button>
            </form>
            
            <div style="text-align: center; margin-top: 30px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">
                Besoin d'aide ? <a href="#" style="color: var(--primary); text-decoration: none; font-weight: 800;">Contactez le support IT</a>
            </div>
        </div>
    </div>
@endsection
