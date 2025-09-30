{{-- Vue: liste des notes avec filtres et bouton d'édition. --}}
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Notes</h1>
    </div>
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <select class="form-select" wire:model="offeringId">
                <option value="">Toutes les sessions</option>
                @foreach($offerings as $o)
                    <option value="{{ $o->id }}">{{ $o->course->code }} - {{ $o->course->title }} ({{ $o->term }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Rechercher étudiant..." wire:model.debounce.400ms="search">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead><tr><th>Étudiant</th><th>Cours</th><th>Session</th><th>Score</th><th>Lettre</th><th></th></tr></thead>
            <tbody>
            @forelse($rows as $en)
                <tr>
                    <td>{{ $en->student->last_name }} {{ $en->student->first_name }}</td>
                    <td>{{ $en->courseOffering->course->title }}</td>
                    <td>{{ $en->courseOffering->term }}</td>
                    <td>{{ $en->grade?->score ?? '-' }}</td>
                    <td>{{ $en->grade?->letter ?? '-' }}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#gradeFormModal"
                                wire:click="$emit('open-grade-form', {{ $en->id }})">Saisir/Modifier</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Aucun enregistrement</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $rows->links() }}

    @livewire('grades.grade-form')
</div><div>
    {{-- In work, do what you enjoy. --}}
</div>
