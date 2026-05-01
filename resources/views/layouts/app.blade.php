<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Le•Quotidien')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@200..900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #f0f2f5;
            --bg-card: #ffffff;
            --primary: #ff5722;
            --primary-hover: #e64a19;
            --text-main: #2d3436;
            --text-muted: #636e72;
            --radius-lg: 30px;
            --radius-md: 20px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 20px 50px rgba(0, 0, 0, 0.1);
            --border: 1px solid #eef0f2;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            line-height: 1.5;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }

        /* ── Header ── */
        header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 15px 0;
            border-bottom: var(--border);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.6rem;
            font-weight: 800;
            text-decoration: none;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 4px;
            letter-spacing: -0.5px;
        }

        .logo span { color: var(--primary); }

        .nav-links {
            display: flex;
            gap: 8px;
            align-items: center;
            background: white;
            padding: 6px;
            border-radius: 50px;
            box-shadow: var(--shadow);
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 22px;
            border-radius: 50px;
            transition: all 0.2s;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
            background: #fff3f0;
        }

        .btn-logout {
            background: none;
            border: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 22px;
            cursor: pointer;
            font-family: inherit;
            border-radius: 50px;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            color: #e53e3e;
            background: #fff5f5;
        }

        /* Mobile hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 8px;
            border-radius: 12px;
            background: white;
            box-shadow: var(--shadow);
            border: var(--border);
        }

        .hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--text-main);
            border-radius: 2px;
            transition: all 0.3s;
        }

        .mobile-menu {
            display: none;
            padding: 15px 0 5px;
            flex-direction: column;
            gap: 5px;
        }

        .mobile-menu a, .mobile-menu button {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 14px;
            transition: all 0.2s;
            background: none;
            border: none;
            font-family: inherit;
            cursor: pointer;
            text-align: left;
            width: 100%;
        }

        .mobile-menu a:hover, .mobile-menu button:hover {
            background: #fff3f0;
            color: var(--primary);
        }

        /* Category Nav */
        .category-nav { margin: 25px 0 0; }

        .category-list {
            display: flex;
            gap: 10px;
            list-style: none;
            overflow-x: auto;
            padding-bottom: 5px;
            scrollbar-width: none;
        }

        .category-list::-webkit-scrollbar { display: none; }

        .category-list li a {
            text-decoration: none;
            color: var(--text-muted);
            background: white;
            padding: 10px 24px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            white-space: nowrap;
            transition: all 0.2s;
            box-shadow: var(--shadow);
            display: block;
        }

        .category-list a.active {
            background: var(--text-main);
            color: white;
            box-shadow: 0 8px 20px rgba(45, 52, 54, 0.2);
        }

        .category-list a:not(.active):hover {
            background: var(--primary);
            color: white;
            box-shadow: 0 8px 20px rgba(255, 87, 34, 0.25);
        }

        /* Cards */
        .card-modern {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 25px;
            box-shadow: var(--shadow);
            border: var(--border);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s;
        }

        .card-modern:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
        }

        /* Buttons */
        .btn-modern {
            background: var(--primary);
            color: white;
            border: none;
            padding: 13px 30px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            font-family: inherit;
        }

        .btn-modern:hover {
            background: var(--primary-hover);
            box-shadow: 0 10px 25px rgba(255, 87, 34, 0.4);
            transform: translateY(-1px);
        }

        /* Badges */
        .badge-cat {
            background: #fff3f0;
            color: var(--primary);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: inline-block;
        }

        /* Avatar initials */
        .avatar-initials {
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        /* Footer */
        footer {
            background: white;
            padding: 70px 0 35px;
            margin-top: 80px;
            border-top: var(--border);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 60px;
            margin-bottom: 50px;
        }

        .footer-brand p {
            margin-top: 15px;
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.7;
            max-width: 280px;
        }

        .footer-col h4 {
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-main);
            margin-bottom: 20px;
        }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-col ul li a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: color 0.2s;
        }

        .footer-col ul li a:hover { color: var(--primary); }

        .footer-divider {
            border: 0;
            border-top: var(--border);
            margin-bottom: 30px;
        }

        .copyright {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 500;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 60px;
        }

        .pagination a, .pagination span {
            width: 46px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 16px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 700;
            box-shadow: var(--shadow);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            font-size: 0.9rem;
        }

        .pagination a:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
            color: var(--primary);
        }

        .pagination .active {
            background: var(--primary);
            color: white;
            box-shadow: 0 8px 20px rgba(255, 87, 34, 0.35);
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            margin-bottom: 25px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .alert-error, .alert-danger {
            background: #fff5f5;
            color: #c53030;
            border: 1px solid #fed7d7;
        }

        .alert-success {
            background: #f0fff4;
            color: #276749;
            border: 1px solid #c6f6d5;
        }

        /* Forms */
        .form-group { margin-bottom: 25px; }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 10px;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 14px;
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--text-main);
            transition: all 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: rgba(255, 87, 34, 0.3);
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 87, 34, 0.07);
        }

        /* Checkbox group */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            background: #f8f9fa;
            border-radius: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .checkbox-group:hover { background: #fff3f0; }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
            flex-shrink: 0;
        }

        .checkbox-group label {
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
        }

        /* Placeholders */
        .category-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 900;
            font-size: 1rem;
        }

        .placeholder-politique { background: linear-gradient(135deg, #e0f2f1, #b2dfdb); color: #00796b !important; }
        .placeholder-technologie { background: linear-gradient(135deg, #e3f2fd, #bbdefb); color: #1565c0 !important; }
        .placeholder-sport { background: linear-gradient(135deg, #fff3e0, #ffe0b2); color: #e65100 !important; }
        .placeholder-economie { background: linear-gradient(135deg, #f1f8e9, #dcedc8); color: #33691e !important; }
        .placeholder-culture { background: linear-gradient(135deg, #f3e5f5, #e1bee7); color: #6a1b9a !important; }
        .placeholder-sante { background: linear-gradient(135deg, #ffebee, #ffcdd2); color: #b71c1c !important; }
        .placeholder-monde { background: linear-gradient(135deg, #e8eaf6, #c5cae9); color: #283593 !important; }
        .placeholder-science { background: linear-gradient(135deg, #e0f7fa, #b2ebf2); color: #00695c !important; }

        /* Image zoom */
        .img-zoom-container {
            overflow: hidden;
            border-radius: inherit;
        }

        .img-zoom-container img {
            transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card-modern:hover .img-zoom-container img {
            transform: scale(1.06);
        }

        /* Reading time badge */
        .reading-time {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            background: #f0f2f5;
            padding: 4px 10px;
            border-radius: 50px;
        }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-up {
            animation: fadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .delay-1 { animation-delay: 0.08s; }
        .delay-2 { animation-delay: 0.16s; }
        .delay-3 { animation-delay: 0.24s; }
        .delay-4 { animation-delay: 0.32s; }

        /* Responsive */
        @media (max-width: 900px) {
            .container { padding: 0 20px; }
            .nav-links { display: none; }
            .hamburger { display: flex; }
            .mobile-menu.open { display: flex; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 40px; }
            .footer-brand { grid-column: 1 / -1; }
            .copyright { flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 600px) {
            .footer-grid { grid-template-columns: 1fr; gap: 30px; }
            .hero-card { flex-direction: column !important; height: auto !important; padding: 20px !important; gap: 20px !important; }
            .img-zoom-container { height: 200px !important; width: 100% !important; }
            .articles-grid { grid-template-columns: 1fr !important; }
            h1 { font-size: 1.8rem !important; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <header>
        <div class="container">
            <div class="header-top">
                <a href="{{ route('accueil') }}" class="logo">Le<span>•</span>Quotidien</a>

                <nav class="nav-links">
                    @auth
                        @if(auth()->user()->isEditeur() || auth()->user()->isAdministrateur())
                            <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin*') ? 'active' : '' }}">Dashboard</a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="{{ request()->is('profile*') ? 'active' : '' }}">Mon profil</a>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-logout">Déconnexion</button>
                        </form>
                    @else
                        @if(!request()->is('login'))
                            <a href="{{ route('login') }}" class="btn-modern" style="padding: 10px 26px; font-size: 0.88rem;">Se connecter</a>
                        @endif
                    @endauth
                </nav>

                <button class="hamburger" onclick="toggleMenu()" aria-label="Menu" id="hamburger-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

            <nav class="mobile-menu" id="mobile-menu">
                @auth
                    @if(auth()->user()->isEditeur() || auth()->user()->isAdministrateur())
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    @endif
                    <a href="{{ route('profile.edit') }}">Mon profil</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Déconnexion</button>
                    </form>
                @else
                    @if(!request()->is('login'))
                        <a href="{{ route('login') }}">Se connecter</a>
                    @endif
                @endauth
            </nav>

            @if(isset($categories))
            <nav class="category-nav">
                <ul class="category-list">
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('categorie.articles', $cat->slug) }}"
                               class="{{ request()->is('categorie/'.$cat->slug) ? 'active' : '' }}">
                                {{ $cat->nom }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
            @endif
        </div>
    </header>

    <main style="padding: 40px 0 0;">
        <div class="container">
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="{{ route('accueil') }}" class="logo" style="font-size: 1.4rem;">Le<span>•</span>Quotidien</a>
                    <p>Votre dose quotidienne d'actualités vérifiées et analysées avec soin par notre équipe de journalistes.</p>
                </div>
                <div class="footer-col">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="{{ route('accueil') }}">Accueil</a></li>
                        @if(isset($categories))
                            @foreach($categories->take(4) as $cat)
                                <li><a href="{{ route('categorie.articles', $cat->slug) }}">{{ $cat->nom }}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Le journal</h4>
                    <ul>
                        <li><a href="#">À propos</a></li>
                        <li><a href="#">Notre équipe</a></li>
                        <li><a href="#">Contact</a></li>
                        @guest
                            <li><a href="{{ route('login') }}">Espace rédaction</a></li>
                        @endguest
                    </ul>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="copyright">
                <span>&copy; {{ date('Y') }} Le Quotidien. Tous droits réservés.</span>
                <span>Fait avec passion pour l'information</span>
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('open');
        }
    </script>
</body>
</html>
