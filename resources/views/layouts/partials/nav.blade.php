{{-- Partial: barre de navigation principale avec liens par module et auth. --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name','SchoolManager') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbarContent" class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('students.index') }}">Étudiants</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('courses.index') }}">Cours</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('teachers.index') }}">Enseignants</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('grades.index') }}">Notes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('roles.index') }}">Rôles</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('results.me') }}">Mes résultats</a></li>
                @endauth
            </ul>
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Inscription</a></li>
                @else
                    <li class="nav-item"><span class="navbar-text me-2">{{ auth()->user()->name }}</span></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-light btn-sm">Déconnexion</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
