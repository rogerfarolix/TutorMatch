<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Système de Matchmaking Tuteur-Élève')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 280px;
            --navbar-height: 60px;
        }

        body {
            font-size: 14px;
        }

        /* Navbar fixe */
        .navbar-custom {
            height: var(--navbar-height);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
        }

        /* Sidebar fixe */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            height: calc(100vh - var(--navbar-height));
            width: var(--sidebar-width);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
            z-index: 1025;
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        /* Navigation links */
        .nav-link {
            color: white !important;
            transition: all 0.3s;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.25rem;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.15);
            transform: translateX(5px);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 20px;
            min-height: calc(100vh - var(--navbar-height));
            transition: margin-left 0.3s ease-in-out;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Page header */
        .page-header {
            background: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e9ecef;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Cards */
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            transition: transform 0.2s;
            border-radius: 12px;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }

        .badge-score {
            font-size: 0.9em;
        }

        .compatibility-bar {
            height: 6px;
            border-radius: 3px;
            background: linear-gradient(90deg, #ff4757, #ffa502, #2ed573);
        }

        /* Boutons */
        .btn-logout {
            background: rgba(255,255,255,0.1);
            color: white;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            border-color: rgba(255,255,255,0.3);
        }

        .sidebar-toggle {
            color: white;
            background: none;
            border: none;
            font-size: 1.2rem;
            padding: 0.5rem;
        }

        .sidebar-toggle:hover {
            color: #f8f9fa;
        }

        /* Overlay pour mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1020;
            display: none;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Profile dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-radius: 10px;
        }

        .dropdown-item {
            padding: 0.75rem 1.25rem;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-brand {
                font-size: 1rem;
            }

            .page-header {
                padding: 1rem 0;
                margin-bottom: 1rem;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 15px;
            }

            .navbar-custom {
                padding: 0.25rem 0.5rem;
            }

            .card {
                margin-bottom: 1rem;
            }
        }

        /* Scrollbar personnalisée pour le sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <!-- Bouton toggle sidebar (visible sur mobile) -->
            <button class="sidebar-toggle d-lg-none me-3" type="button" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                <span class="d-none d-sm-inline">TutorMatch</span>
            </a>

            <!-- User dropdown -->
            <div class="dropdown">
                <button class="btn btn-logout dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-2"></i>
                    <span class="d-none d-sm-inline">{{ Auth::user()->name ?? 'Utilisateur' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault();">
                            <i class="fas fa-user me-2"></i>Mon Profil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Overlay pour mobile -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="p-3">

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                        <i class="fas fa-user-graduate me-2"></i>
                        Élèves
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tutors.*') ? 'active' : '' }}" href="{{ route('tutors.index') }}">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Tuteurs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('matches.*') ? 'active' : '' }}" href="{{ route('matches.index') }}">
                        <i class="fas fa-heart me-2"></i>
                        Matchs
                    </a>
                </li>
            </ul>

            <!-- Bouton déconnexion dans sidebar (visible seulement sur mobile) -->
            <div class="mt-4 d-lg-none">
                <hr style="border-color: rgba(255,255,255,0.2);">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-logout w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main class="main-content" id="mainContent">
        <!-- Page header -->
        <div class="page-header">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h1 class="h2 mb-0">@yield('page-title', 'Dashboard')</h1>
                <div class="btn-toolbar">
                    @yield('page-actions')
                </div>
            </div>
        </div>

        <!-- Messages d'alerte -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Erreurs de validation :</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Contenu de la page -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const mainContent = document.getElementById('mainContent');

            if (window.innerWidth <= 768) {
                // Mode mobile
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                // Mode desktop
                sidebar.classList.toggle('sidebar-hidden');
                mainContent.classList.toggle('expanded');
            }
        }

        // Fermer le sidebar si on clique à l'extérieur sur mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.sidebar-toggle');
            const overlay = document.querySelector('.sidebar-overlay');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(event.target) &&
                !toggle.contains(event.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });

        // Gérer le redimensionnement de la fenêtre
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const mainContent = document.getElementById('mainContent');

            if (window.innerWidth > 768) {
                // Mode desktop
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                if (sidebar.classList.contains('sidebar-hidden')) {
                    mainContent.classList.add('expanded');
                } else {
                    mainContent.classList.remove('expanded');
                }
            } else {
                // Mode mobile
                sidebar.classList.remove('sidebar-hidden');
                mainContent.classList.remove('expanded');
            }
        });

        // Animation au scroll pour masquer/afficher le navbar sur mobile
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar-custom');

        window.addEventListener('scroll', function() {
            if (window.innerWidth <= 768) {
                let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scroll vers le bas
                    navbar.style.transform = 'translateY(-100%)';
                } else {
                    // Scroll vers le haut
                    navbar.style.transform = 'translateY(0)';
                }
                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
