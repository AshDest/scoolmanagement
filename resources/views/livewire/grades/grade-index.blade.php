{{-- Liste Notes: card, filtres à gauche, actions à droite, table claire. --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-body d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-journal-check text-primary"></i>
            <h5 class="mb-0">Notes</h5>
            <span class="badge bg-secondary-subtle text-secondary border rounded-pill">{{ $rows->total() }}</span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <select class="form-select form-select-sm" wire:model="offeringId" style="min-width:260px;">
                <option value="">Toutes les sessions</option>
                @foreach($offerings as $o)
                    <option value="{{ $o->id }}">{{ $o->course->code }} - {{ $o->course->title }} ({{ $o->term }})</option>
                @endforeach
            </select>
            <div class="input-group input-group-sm" style="min-width:240px;">
                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Rechercher étudiant..." wire:model.debounce.300ms="search">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th>Étudiant</th>
                <th>Cours</th>
                <th>Session</th>
                <th>Score</th>
                <th>Lettre</th>
                <th class="text-end" style="width:160px">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($rows as $en)
                <tr>
                    <td class="fw-semibold">{{ $en->student->last_name }} {{ $en->student->first_name }}</td>
                    <td>{{ $en->courseOffering->course->title }}</td>
                    <td><span class="badge text-bg-light border">{{ $en->courseOffering->term }}</span></td>
                    <td>
                        @if($en->grade?->score !== null)
                            <span class="badge rounded-pill text-bg-success-subtle border text-success">{{ $en->grade->score }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $en->grade?->letter ?? '—' }}</td>
                    <td class="text-end">
                        <button
                            class="btn btn-sm btn-outline-secondary"
                            onclick="
                window.Livewire.dispatch('open-grade-form', {{ $en->id }});
                const el = document.getElementById('gradeFormModal');
                bootstrap.Modal.getOrCreateInstance(el).show();
              "
                        >
                            <i class="bi bi-pencil-square"></i> Saisir
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-inbox me-2"></i>Aucun enregistrement
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-body">
        {{ $rows->links() }}
    </div>

    @livewire('grades.grade-form')
</div>
