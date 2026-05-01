<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | Le•Quotidien</title>
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
            --radius-lg: 24px;
            --radius-md: 16px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.09);
            --border: 1px solid #eef0f2;
            --sidebar-width: 265px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            z-index: 1001;
            padding: 28px 16px;
            border-right: var(--border);
            overflow-y: auto;
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 40px;
            padding: 0 12px;
            color: var(--text-main);
            letter-spacing: -0.5px;
            text-decoration: none;
            display: block;
        }

        .sidebar-logo span { color: var(--primary); }

        .sidebar-nav { flex: 1; }

        .nav-section {
            padding: 20px 12px 8px;
            text-transform: uppercase;
            font-size: 0.65rem;
            letter-spacing: 1.8px;
            color: #b2bec3;
            font-weight: 800;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s;
            border-radius: 14px;
            margin-bottom: 3px;
        }

        .sidebar-nav a svg { flex-shrink: 0; opacity: 0.7; }

        .sidebar-nav a:hover {
            color: var(--primary);
            background: #fff3f0;
        }

        .sidebar-nav a:hover svg { opacity: 1; }

        .sidebar-nav a.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 6px 18px rgba(255, 87, 34, 0.3);
        }

        .sidebar-nav a.active svg { opacity: 1; }

        .sidebar-back {
            margin-top: 30px;
            padding: 0 4px;
        }

        .sidebar-back a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            background: #f1f2f6;
            border-radius: 14px;
            transition: all 0.2s;
        }

        .sidebar-back a:hover {
            background: #e4e6eb;
            color: var(--text-main);
        }

        .sidebar-footer {
            padding: 18px 4px 0;
            border-top: var(--border);
            margin-top: 20px;
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: #f8f9fa;
            border-radius: 16px;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .user-info { overflow: hidden; }
        .user-info strong { display: block; font-size: 0.88rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-info span { font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; }

        /* ── Main ── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            height: 75px;
            background: rgba(240, 242, 245, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            border-bottom: var(--border);
        }

        .page-title { font-size: 1.35rem; font-weight: 800; color: var(--text-main); }

        .content { padding: 30px 40px 60px; }

        /* ── Components ── */
        .card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 30px;
            margin-bottom: 30px;
            border: var(--border);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: white;
            padding: 28px 24px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: var(--border);
            transition: all 0.25s;
            text-decoration: none;
            display: block;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        }

        .stat-card h3 {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 8px;
            letter-spacing: 1px;
            font-weight: 800;
        }

        .stat-card .value {
            font-size: 2rem;
            font-weight: 900;
            color: var(--text-main);
            line-height: 1;
        }

        .stat-card .value-sub {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 600;
            margin-top: 5px;
        }

        /* Table */
        table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }

        thead th {
            color: var(--text-muted);
            text-align: left;
            padding: 0 18px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
            padding-bottom: 5px;
        }

        tbody tr {
            background: white;
            transition: all 0.2s;
        }

        tbody td {
            padding: 16px 18px;
            border-top: var(--border);
            border-bottom: var(--border);
            font-size: 0.9rem;
        }

        tbody td:first-child { border-left: var(--border); border-top-left-radius: 16px; border-bottom-left-radius: 16px; }
        tbody td:last-child { border-right: var(--border); border-top-right-radius: 16px; border-bottom-right-radius: 16px; }

        tbody tr:hover { transform: translateY(-2px); box-shadow: var(--shadow); }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 22px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.85rem;
            transition: all 0.2s;
            border: none;
            white-space: nowrap;
        }

        .btn-sm { padding: 7px 16px; font-size: 0.78rem; }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 14px rgba(255, 87, 34, 0.3);
        }

        .btn-primary:hover { transform: translateY(-2px); filter: brightness(1.08); box-shadow: 0 8px 20px rgba(255, 87, 34, 0.35); }

        .btn-danger { background: #fff0ee; color: #c0392b; }
        .btn-danger:hover { background: #e74c3c; color: white; }

        .btn-secondary { background: #f1f2f6; color: var(--text-muted); }
        .btn-secondary:hover { background: #e4e6eb; color: var(--text-main); }

        .btn-success { background: #e8f5e9; color: #2e7d32; }
        .btn-success:hover { background: #2e7d32; color: white; }

        /* Badges */
        .badge {
            padding: 5px 13px;
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 50px;
            display: inline-block;
        }

        .badge-admin { background: #fff3f0; color: var(--primary); }
        .badge-editeur { background: #e3f2fd; color: #1565c0; }
        .badge-administrateur { background: #fff3f0; color: var(--primary); }
        .badge-on { background: #e8f5e9; color: #2e7d32; }
        .badge-off { background: #f5f5f5; color: #9e9e9e; }

        /* Forms */
        .form-group { margin-bottom: 22px; }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 0.82rem;
            color: var(--text-main);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 13px 18px;
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 14px;
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: rgba(255, 87, 34, 0.3);
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 87, 34, 0.07);
        }

        .form-error { font-size: 0.8rem; color: #c0392b; font-weight: 600; margin-top: 6px; }

        /* Checkbox */
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
            margin: 0;
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            margin-bottom: 25px;
            border-radius: 16px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .alert-success { background: #f0fff4; color: #276749; border: 1px solid #c6f6d5; }
        .alert-danger, .alert-error { background: #fff5f5; color: #c53030; border: 1px solid #fed7d7; }
        .alert-danger ul { margin: 0; padding-left: 18px; }
        .alert-danger li { margin-top: 4px; }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-up {
            animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .delay-1 { animation-delay: 0.08s; }
        .delay-2 { animation-delay: 0.16s; }
        .delay-3 { animation-delay: 0.24s; }
        .delay-4 { animation-delay: 0.32s; }

        /* Avatar initials */
        .avatar-initials {
            border-radius: 10px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            text-transform: uppercase;
            flex-shrink: 0;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">Le<span>•</span>Quotidien</a>

        <div class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>

            <div class="nav-section">Contenu</div>
            <a href="{{ route('admin.articles.index') }}" class="{{ request()->is('admin/articles*') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Articles
            </a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Catégories
            </a>

            @if(auth()->user()->isAdministrateur())
                <div class="nav-section">Gestion</div>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Utilisateurs
                </a>
                <a href="{{ route('admin.tokens.index') }}" class="{{ request()->is('admin/tokens*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    API Tokens
                </a>
            @endif

            <div class="nav-section">Compte</div>
            <a href="{{ route('profile.edit') }}" class="{{ request()->is('profile*') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Mon profil
            </a>

            <div class="sidebar-back">
                <a href="{{ route('accueil') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    Retour au site
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-info">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ auth()->user()->role }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm" style="width: 100%; justify-content: center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="topbar">
            <div class="page-title">@yield('page_title')</div>
            <div class="topbar-actions">@yield('actions')</div>
        </div>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>
</html>
