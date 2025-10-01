{{-- Dashboard Étudiant: dernières inscriptions/notes. --}}
<div class="container-fluid px-0">
    @if(!$student)
        <div class="alert alert-warning">Aucun profil étudiant lié à ce compte.</div>
    @else
        <div class="row g-3">
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-body d-flex align-items-center gap-2">
                        <i class="bi bi-person-badge text-primary"></i>
                        <span class="fw-semibold">Mon profil</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-1"><strong>Nom:</strong> {{ $student->last_name }} {{ $student->first_name }}</div>
                        <div class="mb-1"><strong>Matricule:</strong> {{ $student->registration_number }}</div>
                        <div class="mb-1"><strong>Classe:</strong> {{ $student->class?->name ?? '—' }}</div>
                    </div>
                    <div class="card-footer bg-body">
                        <a class="btn btn-sm btn-outline-primary w-100" href="{{ route('students.profile', $student->id) }}">
                            <i class="bi bi-eye me-1"></i> Voir mon profil
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-body d-flex align-items-center gap-2">
                        <i class="bi bi-graph-up text-success"></i>
                        <span class="fw-semibold">Mes résultats récents</span>
                        <a href="{{ route('results.me') }}" class="ms-auto small">Voir tout</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Cours</th><th>Session</th><th>Score</th><th>Lettre</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($student->enrollments->take(8) as $en)
                                <tr>
                                    <td>{{ $en->courseOffering->course->title }}</td>
                                    <td><span class="badge text-bg-light border">{{ $en->courseOffering->term }}</span></td>
                                    <td>{{ $en->grade?->score ?? '—' }}</td>
                                    <td>{{ $en->grade?->letter ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4"><i class="bi bi-inbox me-2"></i>Aucun résultat</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
