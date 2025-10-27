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

        /* Topbar "glass" */
        .topbar {
            position: sticky; top: 0; z-index: 1030;
            overflow: visible;
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
        .profile-dropdown { position: relative; }
        .profile-menu { border-radius: .75rem; padding: .5rem; z-index: 1060; box-shadow: 0 4px 12px rgba(0,0,0,.15); }
        .profile-menu.d-none { display: none !important; }
        .dropdown-item { border-radius: .5rem; cursor: pointer; }
        .dropdown-item:hover { background: var(--bs-secondary-bg); }
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
        @include('layouts.partials.topbar')
        <main class="p-3 p-md-4">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>
</div>

@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
