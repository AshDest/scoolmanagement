{{-- Topbar: dropdown profil fiable, notifications, toggle thème (affecte aussi la sidebar). --}}
<header class="topbar px-3 py-2">
    <div class="container-fluid px-0 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <button class="icon-btn d-lg-none" id="btnSidebarToggle" title="Menu" type="button" aria-label="Ouvrir le menu">
                <i class="bi bi-list"></i>
            </button>
            <span class="d-none d-lg-inline fw-semibold small text-muted">{{ config('app.name','SchoolManager') }}</span>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button class="icon-btn position-relative" type="button" title="Notifications" aria-label="Notifications">
                <i class="bi bi-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;">!</span>
            </button>

            <button class="icon-btn" id="btnThemeToggle" type="button" title="Thème" aria-label="Changer de thème">
                <i class="bi bi-sun-fill d-inline theme-icon-light"></i>
                <i class="bi bi-moon-stars-fill d-none theme-icon-dark"></i>
            </button>

            @auth
                <div class="dropdown">
                    <button
                        class="btn btn-outline-secondary d-flex align-items-center gap-2 px-2 py-1 rounded-3 dropdown-toggle"
                        id="profileDropdown"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        aria-haspopup="true"
                        title="Profil"
                    >
                        <img class="avatar" src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode(auth()->user()->name ?? 'U') }}" alt="avatar">
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="profileDropdown" style="min-width: 260px;">
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
                        <li class="px-2 pb-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2" type="submit">
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Sidebar mobile toggle
        const btn = document.getElementById('btnSidebarToggle');
        const sidebar = document.getElementById('appSidebar');
        if (btn && sidebar) btn.addEventListener('click', () => sidebar.classList.toggle('show'));

        // Theme toggle (affecte aussi la sidebar via CSS variables dans le layout)
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
