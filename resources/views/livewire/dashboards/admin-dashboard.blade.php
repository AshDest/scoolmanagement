{{-- Dashboard Admin: tuiles KPI + listes récentes. --}}
<div class="container-fluid px-0">
    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="display-6 text-primary"><i class="bi bi-people-fill"></i></div>
                    <div>
                        <div class="text-muted small">Utilisateurs</div>
                        <div class="h4 mb-0">{{ $metrics['users'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="display-6 text-success"><i class="bi bi-mortarboard-fill"></i></div>
                    <div>
                        <div class="text-muted small">Étudiants</div>
                        <div class="h4 mb-0">{{ $metrics['students'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="display-6 text-warning"><i class="bi bi-book-half"></i></div>
                    <div>
                        <div class="text-muted small">Cours</div>
                        <div class="h4 mb-0">{{ $metrics['courses'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="display-6 text-info"><i class="bi bi-journal-check"></i></div>
                    <div>
                        <div class="text-muted small">Inscriptions</div>
                        <div class="h4 mb-0">{{ $metrics['enrollments'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-body d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-primary"></i>
                    <span class="fw-semibold">Derniers étudiants</span>
                    <a href="{{ route('students.index') }}" class="ms-auto small">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($latestStudents as $s)
                            <a href="{{ route('students.profile', $s->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                                <span>{{ $s->last_name }} {{ $s->first_name }}</span>
                                <span class="badge text-bg-light border">{{ $s->registration_number }}</span>
                            </a>
                        @empty
                            <div class="p-3 text-muted">Aucun étudiant.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-body d-flex align-items-center gap-2">
                    <i class="bi bi-book-half text-warning"></i>
                    <span class="fw-semibold">Derniers cours</span>
                    <a href="{{ route('courses.index') }}" class="ms-auto small">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($latestCourses as $c)
                            <div class="list-group-item d-flex justify-content-between">
                                <span>{{ $c->title }}</span>
                                <span class="badge rounded-pill text-bg-primary-subtle border text-primary">{{ $c->code }}</span>
                            </div>
                        @empty
                            <div class="p-3 text-muted">Aucun cours.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
