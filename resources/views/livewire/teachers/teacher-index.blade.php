{{-- Liste Enseignants: card, header outils, table compacte. --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-body d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-workspace text-primary"></i>
            <h5 class="mb-0">Enseignants</h5>
            <span class="badge bg-secondary-subtle text-secondary border rounded-pill">{{ $teachers->total() }}</span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <div class="input-group input-group-sm" style="min-width:260px;">
                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Rechercher nom/email..." wire:model.debounce.300ms="search">
            </div>
            <button
                class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                onclick="
          window.Livewire.dispatch('open-teacher-form', { id: null });
          const el = document.getElementById('teacherFormModal');
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
                <th>Nom</th>
                <th>Email</th>
                <th class="text-end" style="width:140px">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($teachers as $t)
                <tr>
                    <td><span class="badge text-bg-light border">{{ $t->id }}</span></td>
                    <td class="fw-semibold">{{ $t->name }}</td>
                    <td class="text-muted">{{ $t->email }}</td>
                    <td class="text-end">
                        <button
                            class="btn btn-sm btn-outline-secondary"
                            title="Modifier"
                            onclick="
                window.Livewire.dispatch('open-teacher-form', { id: {{ $t->id }} });
                const el = document.getElementById('teacherFormModal');
                bootstrap.Modal.getOrCreateInstance(el).show();
              "
                        >
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" title="Supprimer" wire:click="delete({{ $t->id }})"
                                onclick="return confirm('Supprimer cet enseignant ?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
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
        {{ $teachers->links() }}
    </div>

    @livewire('teachers.teacher-form')
</div>
