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
            --border: 1px solid #eef0f2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        /* Header / Navbar */
        header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
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
            gap: 10px;
        }

        .logo span {
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            gap: 15px;
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
            padding: 10px 25px;
            border-radius: 50px;
            transition: all 0.3s;
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
            padding: 10px 25px;
            cursor: pointer;
            font-family: inherit;
        }

        /* Category Scroll */
        .category-nav {
            margin: 30px 0;
        }

        .category-list {
            display: flex;
            gap: 12px;
            list-style: none;
            overflow-x: auto;
            padding: 5px;
        }

        .category-list li a {
            text-decoration: none;
            color: var(--text-muted);
            background: white;
            padding: 12px 28px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.3s;
            box-shadow: var(--shadow);
        }

        .category-list a.active, .category-list a:hover {
            background: var(--text-main);
            color: white;
        }

        /* Card Style */
        .card-modern {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 25px;
            box-shadow: var(--shadow);
            border: var(--border);
            transition: transform 0.3s;
        }

        .card-modern:hover {
            transform: translateY(-5px);
        }

        /* Buttons */
        .btn-modern {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-modern:hover {
            background: var(--primary-hover);
            box-shadow: 0 8px 20px rgba(255, 87, 34, 0.4);
        }

        /* Badges */
        .badge-cat {
            background: #fff3f0;
            color: var(--primary);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 10px;
            display: inline-block;
        }

        /* Footer */
        footer {
            background: white;
            padding: 80px 0 40px;
            margin-top: 80px;
            border-top: var(--border);
        }

        .copyright {
            text-align: center;
            margin-top: 60px;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 60px;
        }

        .pagination a, .pagination span {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 18px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 700;
            box-shadow: var(--shadow);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .pagination a:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
            color: var(--primary);
        }

        .pagination .active {
            background: var(--primary);
            color: white;
            box-shadow: 0 8px 20px rgba(255, 87, 34, 0.3);
            transform: scale(1.05);
        }

        /* Alerts */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 15px;
        }
        .alert-error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        .img-zoom-container {
            overflow: hidden;
            border-radius: inherit;
        }
        
        .img-zoom-container img {
            transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .card-modern:hover .img-zoom-container img {
            transform: scale(1.08);
        }

        /* Refined Placeholders */
        .category-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 900;
            font-size: 1.2rem;
            background: #f0f2f5;
        }
        
        .placeholder-politique { background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%); color: #00796b !important; }
        .placeholder-technologie { background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); color: #1976d2 !important; }
        .placeholder-sport { background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); color: #e65100 !important; }
        .placeholder-economie { background: linear-gradient(135deg, #f1f8e9 0%, #dcedc8 100%); color: #33691e !important; }
        .placeholder-culture { background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%); color: #7b1fa2 !important; }
        .placeholder-sante { background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%); color: #c62828 !important; }

        /* Form Styles */
        .form-group { margin-bottom: 25px; }
        .form-label { display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-main); margin-bottom: 10px; }
        .form-control {
            width: 100%;
            padding: 15px 20px;
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 15px;
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--text-main);
            transition: all 0.3s;
            outline: none;
        }
        .form-control:focus { border-color: #ff572244; background: white; box-shadow: 0 0 0 4px #ff572211; }
        .alert { padding: 15px 20px; border-radius: 15px; margin-bottom: 25px; font-weight: 600; font-size: 0.9rem; }
        .alert-error { background: #fff5f5; color: #e53e3e; border: 1px solid #feb2b2; }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hero-card { flex-direction: column !important; height: auto !important; padding: 25px !important; gap: 20px !important; }
            .img-zoom-container { height: 220px !important; width: 100% !important; }
            .articles-grid { grid-template-columns: 1fr !important; }
            h1 { font-size: 2rem !important; }
            .hero-content { text-align: center; }
            .hero-content .btn-modern { width: 100%; }
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
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-logout">Logout</button>
                        </form>
                    @else
                        @if(!request()->is('login'))
                            <a href="{{ route('login') }}" class="btn-modern" style="padding: 10px 30px; font-size: 0.9rem;">Se connecter</a>
                        @endif
                    @endauth
                </nav>
            </div>
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

    <main>
        <div class="container">
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer>
        <div class="container">
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <div class="logo">Le<span>•</span>Quotidien</div>
                    <p style="margin-top: 15px; color: var(--text-muted); max-width: 300px;">
                        Votre dose quotidienne d'actualités filtrée avec soin.
                    </p>
                </div>
                <div style="display: flex; gap: 60px;">
                    <div>
                        <h4 style="margin-bottom: 20px; font-weight: 800;">Navigation</h4>
                        <ul style="list-style: none; color: var(--text-muted); line-height: 2;">
                            <li><a href="{{ route('accueil') }}" style="color: inherit; text-decoration: none;">Accueil</a></li>
                            @if(isset($categories))
                                @foreach($categories->take(3) as $cat)
                                    <li><a href="{{ route('categorie.articles', $cat->slug) }}" style="color: inherit; text-decoration: none;">{{ $cat->nom }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                &copy; {{ date('Y') }} Le Quotidien. Tous droits réservés.
            </div>
        </div>
    </footer>
</body>
</html>
