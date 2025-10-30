{{-- Vue: affiche le profil détaillé d'un étudiant, inscriptions et notes. --}}
<div>
    <h1 class="h3 mb-3">Profil étudiant</h1>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-person me-2 text-primary"></i>Informations personnelles
                    </h5>
                    <div class="mb-2"><strong>Nom:</strong> {{ $student->last_name }} {{ $student->first_name }}</div>
                    <div class="mb-2"><strong>Matricule:</strong> {{ $student->registration_number }}</div>
                    <div class="mb-2"><strong>Classe:</strong> {{ $student->class?->name ?? '-' }}</div>
                    <div class="mb-2"><strong>Date de naissance:</strong> {{ optional($student->dob)->format('d/m/Y') }}</div>
                    @if($student->address)
                    <div class="mb-2"><strong>Adresse:</strong> {{ $student->address }}</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-person-badge me-2 text-primary"></i>Informations du tuteur
                    </h5>
                    @if($student->tutor_name || $student->tutor_phone)
                        @if($student->tutor_name)
                        <div class="mb-2"><strong>Nom du tuteur:</strong> {{ $student->tutor_name }}</div>
                        @endif
                        @if($student->tutor_phone)
                        <div class="mb-2"><strong>Numéro du tuteur:</strong> {{ $student->tutor_phone }}</div>
                        @endif
                    @else
                        <div class="text-muted">Aucune information de tuteur disponible</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <h2 class="h5">Inscriptions et notes</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Cours</th><th>Session</th><th>Statut</th><th>Note</th><th>Lettre</th></tr></thead>
            <tbody>
            @forelse($student->enrollments as $en)
                <tr>
                    <td>{{ $en->courseOffering->course->title }}</td>
                    <td>{{ $en->courseOffering->term }}</td>
                    <td>{{ $en->status }}</td>
                    <td>{{ $en->grade?->score ?? '-' }}</td>
                    <td>{{ $en->grade?->letter ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Aucune inscription</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
