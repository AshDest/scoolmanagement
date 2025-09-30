{{-- Vue: affiche le profil détaillé d'un étudiant, inscriptions et notes. --}}
<div>
    <h1 class="h3 mb-3">Profil étudiant</h1>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Nom:</strong> {{ $student->last_name }} {{ $student->first_name }}</div>
            <div><strong>Matricule:</strong> {{ $student->registration_number }}</div>
            <div><strong>Classe:</strong> {{ $student->class?->name ?? '-' }}</div>
            <div><strong>Date de naissance:</strong> {{ optional($student->dob)->format('d/m/Y') }}</div>
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
