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
            --radius-lg: 30px;
            --radius-md: 20px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            --border: 1px solid #eef0f2;
            --sidebar-width: 280px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            z-index: 1001;
            padding: 30px 20px;
            border-right: var(--border);
        }

        .sidebar-logo {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 50px;
            padding: 0 15px;
            color: var(--text-main);
        }

        .sidebar-logo span { color: var(--primary); }

        .sidebar-nav {
            flex: 1;
        }

        .nav-section {
            padding: 20px 15px 10px;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 2px;
            color: #b2bec3;
            font-weight: 800;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s;
            border-radius: 18px;
            margin-bottom: 5px;
        }

        .sidebar-nav a:hover, .sidebar-nav a.active {
            color: var(--primary);
            background-color: #fff3f0;
        }

        .sidebar-nav a.active {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 8px 20px rgba(255, 87, 34, 0.3);
        }

        .sidebar-footer {
            padding: 20px 15px;
            border-top: var(--border);
            margin-top: 20px;
        }

        .user-info {
            margin-bottom: 15px;
        }

        .user-info strong { display: block; font-size: 0.95rem; }
        .user-info span { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }

        /* Main Content */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            height: 90px;
            background: rgba(240, 242, 245, 0.8);
            backdrop-filter: blur(20px);
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .content {
            padding: 20px 40px 60px;
        }

        /* UI Components */
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
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: var(--border);
            text-align: center;
        }

        .stat-card h3 { font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 15px; letter-spacing: 1px; font-weight: 800; }
        .stat-card .value { font-size: 2.2rem; font-weight: 800; color: var(--text-main); }

        /* Table */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
        }

        thead th {
            color: var(--text-muted);
            text-align: left;
            padding: 0 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
        }

        tbody tr {
            background-color: white;
            transition: all 0.3s;
        }

        tbody td {
            padding: 20px;
            border-top: var(--border);
            border-bottom: var(--border);
        }

        tbody td:first-child { border-left: var(--border); border-top-left-radius: 20px; border-bottom-left-radius: 20px; }
        tbody td:last-child { border-right: var(--border); border-top-right-radius: 20px; border-bottom-right-radius: 20px; }

        tbody tr:hover { transform: translateY(-3px); box-shadow: var(--shadow); }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.85rem;
            transition: all 0.3s;
            border: none;
        }

        .btn-sm { padding: 8px 20px; font-size: 0.75rem; }
        .btn-primary { background-color: var(--primary); color: white; box-shadow: 0 4px 15px rgba(255, 87, 34, 0.3); }
        .btn-primary:hover { transform: translateY(-2px); filter: brightness(1.1); }
        
        .btn-danger { background-color: #ffebee; color: #c62828; }
        .btn-danger:hover { background-color: #ef5350; color: white; }
        
        .btn-secondary { background-color: #f1f2f6; color: var(--text-muted); }

        /* Badges */
        .badge {
            padding: 6px 16px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            border-radius: 50px;
        }
        .badge-admin { background-color: #fff3f0; color: var(--primary); }
        .badge-editeur { background-color: #e3f2fd; color: #1976d2; }
        .badge-on { background-color: #e8f5e9; color: #2e7d32; }
        .badge-off { background-color: #f5f5f5; color: #757575; }

        /* Forms */
        .form-group { margin-bottom: 25px; }
        .form-label { display: block; margin-bottom: 10px; font-weight: 700; font-size: 0.85rem; color: var(--text-muted); }
        .form-control {
            width: 100%;
            padding: 15px 20px;
            background: #f8f9fa;
            border: 1px solid #eef0f2;
            border-radius: 18px;
            color: var(--text-main);
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); background: white; box-shadow: var(--shadow); }

        /* Alerts */
        .alert { padding: 20px; margin-bottom: 30px; border-radius: 20px; font-size: 0.95rem; font-weight: 600; }
        .alert-success { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-logo">Le<span>•</span>Quotidien</div>
        <div class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin') ? 'active' : '' }}">Dashboard</a>
            
            <div class="nav-section">Contenu</div>
            <a href="{{ route('admin.articles.index') }}" class="{{ request()->is('admin/articles*') ? 'active' : '' }}">Articles</a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->is('admin/categories*') ? 'active' : '' }}">Catégories</a>

            @if(auth()->user()->isAdministrateur())
                <div class="nav-section">Gestion</div>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">Utilisateurs</a>
                <a href="{{ route('admin.tokens.index') }}" class="{{ request()->is('admin/tokens*') ? 'active' : '' }}">API Tokens</a>
            @endif

            <div style="margin-top: 40px; padding: 0 15px;">
                <a href="{{ route('accueil') }}" style="background: #f1f2f6; color: var(--text-muted);">← Retour au site</a>
            </div>
        </div>
        <div class="sidebar-footer">
            <div class="user-info">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ auth()->user()->role }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">Déconnexion</button>
            </form>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="topbar">
            <div class="page-title">@yield('page_title')</div>
            <div class="topbar-actions">
                @yield('actions')
            </div>
        </div>
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html>
