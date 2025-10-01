{{-- Liste Étudiants: card, header avec actions, filtres soignés, table compacte. --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-body d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-people-fill text-primary"></i>
            <h5 class="mb-0">Étudiants</h5>
            <span class="badge bg-secondary-subtle text-secondary border rounded-pill">{{ $students->total() }}</span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <div class="input-group input-group-sm" style="min-width:240px;">
                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Rechercher..." wire:model.debounce.300ms="search">
            </div>
            <div>
                <select class="form-select form-select-sm" wire:model="classId">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <button
                class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                onclick="
          window.Livewire.dispatch('open-student-form', { id: null });
          const el = document.getElementById('studentFormModal');
          bootstrap.Modal.getOrCreateInstance(el).show();
        "
            >
                <i class="bi bi-plus-circle"></i> Nouveau
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th style="width:72px">#</th>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Classe</th>
                <th class="text-end" style="width:180px">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($students as $s)
                <tr>
                    <td><span class="badge text-bg-light border">{{ $s->id }}</span></td>
                    <td class="fw-semibold">{{ $s->registration_number }}</td>
                    <td>{{ $s->last_name }} {{ $s->first_name }}</td>
                    <td>
                        @if($s->class)
                            <span class="badge rounded-pill text-bg-primary-subtle border text-primary">{{ $s->class->name }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-info" href="{{ route('students.profile', $s->id) }}">
                            <i class="bi bi-person-badge"></i>
                        </a>
                        <button
                            class="btn btn-sm btn-outline-secondary"
                            title="Modifier"
                            onclick="
                window.Livewire.dispatch('open-student-form', { id: {{ $s->id }} });
                const el = document.getElementById('studentFormModal');
                bootstrap.Modal.getOrCreateInstance(el).show();
              "
                        >
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" title="Supprimer" wire:click="delete({{ $s->id }})"
                                onclick="return confirm('Supprimer cet étudiant ?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="bi bi-inbox me-2"></i>Aucun résultat
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if (session('status'))
        <div class="card-footer bg-body">
            <div class="alert alert-success mb-0 py-2">{{ session('status') }}</div>
        </div>
    @endif

    <div class="card-footer bg-body">
        {{ $students->links() }}
    </div>

    @livewire('students.student-form')
</div>
