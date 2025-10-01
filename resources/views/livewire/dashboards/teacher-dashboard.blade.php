{{-- Dashboard Enseignant: sessions récentes et accès rapide aux notes. --}}
<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-body d-flex align-items-center gap-2">
            <i class="bi bi-person-workspace text-primary"></i>
            <span class="fw-semibold">Mes sessions (récents)</span>
            <a href="{{ route('grades.index') }}" class="ms-auto small">Saisir des notes</a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @forelse($offerings as $o)
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span class="badge rounded-pill text-bg-primary-subtle border text-primary">{{ $o->course->code }}</span>
                                    <span class="badge text-bg-light border">{{ $o->term }}</span>
                                </div>
                                <div class="mt-2 fw-semibold">{{ $o->course->title }}</div>
                                <div class="small text-muted">Salle: {{ $o->room ?? '—' }}</div>
                            </div>
                            <div class="card-footer bg-body">
                                <a class="btn btn-sm btn-outline-primary w-100" href="{{ route('grades.index') }}">
                                    <i class="bi bi-journal-check me-1"></i> Gérer les notes
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-muted">Aucune session récente.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
