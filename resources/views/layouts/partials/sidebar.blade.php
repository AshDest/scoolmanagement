{{-- Sidebar gauche: navigation principale par modules avec icônes, styles réactifs au thème via variables CSS. --}}
<aside id="appSidebar" class="sidebar p-3 d-flex flex-column">
    <div class="brand d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-mortarboard-fill"></i>
        <span>{{ config('app.name','SchoolManager') }}</span>
    </div>

    @auth
        <nav class="flex-grow-1">
            <ul class="nav nav-pills flex-column gap-1">
                {{-- Ajout du menu Dashboards --}}
                <li class="nav-item mt-2">
                    <div class="small text-uppercase text-muted px-2 mb-1">Dashboards</div>
                </li>
                @role('admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}" href="{{ route('dashboard.admin') }}">
                        <span class="icon"><i class="bi bi-speedometer2"></i></span>
                        <span>Admin</span>
                    </a>
                </li>
                @endrole
                @role('teacher')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard.teacher') ? 'active' : '' }}" href="{{ route('dashboard.teacher') }}">
                        <span class="icon"><i class="bi bi-speedometer"></i></span>
                        <span>Enseignant</span>
                    </a>
                </li>
                @endrole
                @role('student')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard.student') ? 'active' : '' }}" href="{{ route('dashboard.student') }}">
                        <span class="icon"><i class="bi bi-speedometer"></i></span>
                        <span>Étudiant</span>
                    </a>
                </li>
                @endrole
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                        <span class="icon"><i class="bi bi-people-fill"></i></span>
                        <span>Étudiants</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                        <span class="icon"><i class="bi bi-book-half"></i></span>
                        <span>Cours</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}" href="{{ route('teachers.index') }}">
                        <span class="icon"><i class="bi bi-person-workspace"></i></span>
                        <span>Enseignants</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('grades.*') ? 'active' : '' }}" href="{{ route('grades.index') }}">
                        <span class="icon"><i class="bi bi-journal-check"></i></span>
                        <span>Notes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                        <span class="icon"><i class="bi bi-shield-lock"></i></span>
                        <span>Rôles</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('results.*') ? 'active' : '' }}" href="{{ route('results.me') }}">
                        <span class="icon"><i class="bi bi-graph-up"></i></span>
                        <span>Mes résultats</span>
                    </a>
                </li>
            </ul>
        </nav>
    @endauth

    @guest
        <div class="mt-auto">
            <a class="btn btn-light w-100" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-in-right me-2"></i> Connexion
            </a>
        </div>
    @endguest
</aside>
