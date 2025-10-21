{{-- Page de connexion: fond uni pleine hauteur, sans arrière-plan, et carte parfaitement centrée. --}}
@extends('layouts.app')

@section('content')
    <style>
        /* Masquer sidebar et topbar uniquement sur la page login */
        #appSidebar, .topbar { display: none !important; }

        /* Le wrapper passe en plein écran et centre la carte */
        .app-wrapper { grid-template-columns: 1fr !important; }
        .content {
            min-height: 100vh;
            padding: 0 !important;
            display: grid;
            place-items: center; /* centre vertical + horizontal */
        }

        /* Fond uni (thème-aware) qui remplit l'écran */
        .auth-bg {
            width: 100%;
            height: 100%;
            background: var(--bs-body-bg);
            display: grid;
            place-items: center; /* double sécurité pour le centrage */
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1rem 3rem rgba(0,0,0,.15);
            background: var(--bs-body-bg);
        }
        .auth-card .card-header {
            background: linear-gradient(135deg, rgba(13,110,253,.12), rgba(111,66,193,.12));
            border-bottom: 1px solid var(--bs-border-color);
        }
        .brand-badge {
            width: 52px; height: 52px; border-radius: 12px;
            display: grid; place-items: center;
            background: var(--bs-primary);
            color: #fff; font-size: 1.25rem;
            box-shadow: 0 6px 14px rgba(13,110,253,.25);
        }
        .input-group-text { background: transparent; }
        .form-control::placeholder { opacity: .6; }
        .social-btn { border-radius: .75rem; }
        .auth-footer { opacity: .8; }
    </style>

    <div class="auth-bg">
        <div class="auth-card card">
            <div class="card-header p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="brand-badge">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <div class="small text-muted mb-1">{{ config('app.name','SchoolManager') }}</div>
                        <h5 class="mb-0">Bienvenue</h5>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle me-1"></i> Oups…</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" novalidate class="needs-validation">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                   placeholder="exemple@domaine.com">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label d-flex justify-content-between">
                            <span>Mot de passe</span>
                            @if (Route::has('password.request'))
                                <a class="small" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                            @endif
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password"
                                   placeholder="••••••••">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <button class="btn btn-outline-secondary" type="button" onclick="
              const i = document.getElementById('password');
              i.type = i.type === 'password' ? 'text' : 'password';
              this.querySelector('i').classList.toggle('bi-eye');
              this.querySelector('i').classList.toggle('bi-eye-slash');
            ">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Se souvenir de moi</label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Se connecter</span>
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <div class="text-muted small mb-2">Ou continuer avec</div>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-outline-secondary social-btn" type="button" disabled><i class="bi bi-google"></i></button>
                        <button class="btn btn-outline-secondary social-btn" type="button" disabled><i class="bi bi-github"></i></button>
                        <button class="btn btn-outline-secondary social-btn" type="button" disabled><i class="bi bi-facebook"></i></button>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-body p-4">
                <div class="d-flex justify-content-between align-items-center auth-footer">
                    @if (Route::has('register'))
                        <div>Pas de compte ? <a href="{{ route('register') }}">Créer un compte</a></div>
                    @else
                        <div></div>
                    @endif
                    <div class="small text-muted">© {{ date('Y') }} {{ config('app.name','SchoolManager') }}</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Validation bootstrap (optionnel)
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
@endsection
