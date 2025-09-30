{{-- Vue: tableau des résultats globaux d'un étudiant. --}}
<div>
    <h1 class="h3 mb-3">Résultats de {{ $student->last_name }} {{ $student->first_name }}</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>Cours</th><th>Session</th><th>Score</th><th>Lettre</th></tr></thead>
            <tbody>
            @foreach($student->enrollments as $en)
                <tr>
                    <td>{{ $en->courseOffering->course->title }}</td>
                    <td>{{ $en->courseOffering->term }}</td>
                    <td>{{ $en->grade?->score ?? '-' }}</td>
                    <td>{{ $en->grade?->letter ?? '-' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
