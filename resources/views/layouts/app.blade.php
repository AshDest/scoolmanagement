{{-- Layout principal: structure avec sidebar et topbar, thème clair/sombre affectant la sidebar. --}}
    <!doctype html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SchoolManager') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/sass/app.scss','resources/js/app.js'])
    @livewireStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        html, body { height: 100%; }
        .app-wrapper { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }

        /* Sidebar: styles pilotés par CSS variables selon le thème */
        .sidebar {
            --sb-bg: var(--bs-dark);
            --sb-text: #e9ecef;
            --sb-text-muted: #cfd8dc;
            --sb-hover: rgba(255,255,255,.08);
            --sb-active: rgba(255,255,255,.12);
            background: var(--sb-bg);
            color: var(--sb-text);
        }
        [data-bs-theme="light"] .sidebar {
            --sb-bg: #ffffff;
            --sb-text: #212529;
            --sb-text-muted: #6c757d;
            --sb-hover: rgba(0,0,0,.04);
            --sb-active: rgba(13,110,253,.12);
            border-right: 1px solid var(--bs-border-color);
        }
        .sidebar .brand { font-weight: 700; letter-spacing: .2px; color: var(--sb-text); }
        .sidebar a { color: var(--sb-text-muted); text-decoration: none; }
        .sidebar .nav-link { display: flex; align-items: center; gap: .75rem; padding: .65rem .9rem; border-radius: .5rem; }
        .sidebar .nav-link:hover { color: var(--sb-text); background: var(--sb-hover); }
        .sidebar .nav-link.active { color: var(--sb-text); background: var(--sb-active); }
        .sidebar .icon { width: 1.25rem; height: 1.25rem; display: inline-flex; align-items: center; justify-content: center; }

        .content { background: var(--bs-body-bg); }

        /* Topbar “glass” */
        .topbar {
            position: sticky; top: 0; z-index: 1030;
            backdrop-filter: saturate(180%) blur(10px);
            background-color: color-mix(in srgb, var(--bs-body-bg) 75%, transparent);
            border-bottom: 1px solid var(--bs-border-color);
            box-shadow: 0 2px 10px rgba(0,0,0,.04);
        }
        .icon-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 10px;
            color: var(--bs-body-color); border: 1px solid var(--bs-border-color);
            background: var(--bs-body-bg);
        }
        .icon-btn:hover { background: var(--bs-secondary-bg); }
        .avatar { width: 28px; height: 28px; border-radius: 50%; object-fit: cover; background: var(--bs-secondary-bg); }
        .dropdown-menu { border-radius: .75rem; padding: .5rem; }
        .dropdown-item { border-radius: .5rem; }
        .dropdown-item:active { background: var(--bs-primary); color: #fff; }

        @media (max-width: 991.98px) {
            .app-wrapper { grid-template-columns: 1fr; }
            .sidebar { position: fixed; z-index: 1040; width: 260px; height: 100vh; left: -260px; top: 0; transition: left .25s ease; }
            .sidebar.show { left: 0; }
        }
    </style>
</head>
<body>
<div class="app-wrapper">
    @include('layouts.partials.sidebar')

    <div class="content d-flex flex-column">
        <header class="topbar px-3 py-2">
            <div class="container-fluid px-0 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <button class="icon-btn d-lg-none" id="btnSidebarToggle" title="Menu">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="d-none d-lg-inline fw-semibold small text-muted">{{ config('app.name','SchoolManager') }}</span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="icon-btn position-relative" type="button" title="Notifications">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;">!</span>
                    </button>

                    <button class="icon-btn" id="btnThemeToggle" type="button" title="Thème">
                        <i class="bi bi-sun-fill d-inline theme-icon-light"></i>
                        <i class="bi bi-moon-stars-fill d-none theme-icon-dark"></i>
                    </button>

                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary d-flex align-items-center gap-2 px-2 py-1 rounded-3"
                                    data-bs-toggle="dropdown" aria-expanded="false" title="Profil">
                                <img class="avatar" src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode(auth()->user()->name ?? 'U') }}" alt="avatar">
                                <i class="bi bi-caret-down-fill small"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li class="px-3 py-2">
                                    <div class="small text-muted">{{ auth()->user()->email }}</div>
                                    <div class="fw-semibold text-truncate" style="max-width:200px">{{ auth()->user()->name }}</div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('results.me') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> Mon tableau de bord
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('roles.index') }}">
                                        <i class="bi bi-shield-lock me-2"></i> Rôles et accès
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="px-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2">
                                            <i class="bi bi-box-arrow-right"></i>
                                            <span>Déconnexion</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth

                    @guest
                        <a class="btn btn-primary d-flex align-items-center gap-2" href="{{ route('login') }}" title="Se connecter">
                            <i class="bi bi-box-arrow-in-right"></i><span class="d-none d-md-inline">Connexion</span>
                        </a>
                    @endguest
                </div>
            </div>
        </header>

        <main class="p-3 p-md-4">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Sidebar mobile toggle
        const btn = document.getElementById('btnSidebarToggle');
        const sidebar = document.getElementById('appSidebar');
        if (btn && sidebar) btn.addEventListener('click', () => sidebar.classList.toggle('show'));

        // Theme toggle (affecte aussi la sidebar via CSS variables)
        const themeBtn = document.getElementById('btnThemeToggle');
        const html = document.documentElement;
        const lightIcon = document.querySelector('.theme-icon-light');
        const darkIcon  = document.querySelector('.theme-icon-dark');

        function applyTheme(next) {
            html.setAttribute('data-bs-theme', next);
            localStorage.setItem('bs-theme', next);
            const isLight = next === 'light';
            if (lightIcon && darkIcon) {
                lightIcon.classList.toggle('d-none', !isLight);
                darkIcon.classList.toggle('d-none', isLight);
            }
        }
        const savedTheme = localStorage.getItem('bs-theme') || 'light';
        applyTheme(savedTheme);

        if (themeBtn) {
            themeBtn.addEventListener('click', () => {
                const current = html.getAttribute('data-bs-theme') || 'light';
                applyTheme(current === 'light' ? 'dark' : 'light');
            });
        }
    });
</script>
@livewireScripts
</body>
</html>
