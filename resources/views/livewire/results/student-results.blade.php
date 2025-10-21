{{-- Résultats globaux: ajout badges Passe/Échoue par cours et résumé final. --}}
@php
    $seuil = 50; // seuil de passage (modifiable si besoin)
    $scores = $student->enrollments->pluck('grade.score')->filter()->map(fn($v) => (float)$v);
    $moyenne = $scores->count() ? round($scores->avg(), 2) : null;
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-header bg-body d-flex align-items-center gap-2">
        <i class="bi bi-graph-up text-success"></i>
        <h5 class="mb-0">Résultats de {{ $student->last_name }} {{ $student->first_name }}</h5>
        @if(!is_null($moyenne))
            <span class="ms-auto">
        Moyenne: <span class="badge {{ $moyenne >= $seuil ? 'text-bg-success' : 'text-bg-danger' }}">{{ $moyenne }}</span>
        @if($moyenne >= $seuil)
                    <span class="badge text-bg-success-subtle border text-success ms-2">Passe</span>
                @else
                    <span class="badge text-bg-danger-subtle border text-danger ms-2">Échoue</span>
                @endif
      </span>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Cours</th><th>Session</th><th>Score</th><th>Lettre</th><th>Décision</th></tr></thead>
            <tbody>
            @foreach($student->enrollments as $en)
                @php
                    $score = $en->grade?->score;
                    $decision = is_null($score) ? null : ($score >= $seuil ? 'Passe' : 'Échoue');
                @endphp
                <tr>
                    <td>{{ $en->courseOffering->course->title }}</td>
                    <td><span class="badge text-bg-light border">{{ $en->courseOffering->term }}</span></td>
                    <td>{{ $score ?? '—' }}</td>
                    <td>{{ $en->grade?->letter ?? '—' }}</td>
                    <td>
                        @if(is_null($score))
                            <span class="text-muted">—</span>
                        @elseif($score >= $seuil)
                            <span class="badge rounded-pill text-bg-success-subtle border text-success">Passe</span>
                        @else
                            <span class="badge rounded-pill text-bg-danger-subtle border text-danger">Échoue</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
