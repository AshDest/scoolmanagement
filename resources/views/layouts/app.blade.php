{{-- Layout principal: structure HTML, nav/footer, Livewire et Bootstrap. --}}
    <!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SchoolManager') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/sass/app.scss','resources/js/app.js'])
    @livewireStyles
</head>
<body>
@include('partials.nav')

<main class="container py-3">
    {{ $slot ?? '' }}
    @yield('content')
</main>

<footer class="border-top py-3">
    <div class="container text-muted small">
        © {{ date('Y') }} {{ config('app.name','SchoolManager') }}
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
@livewireScripts
</body>
</html>
