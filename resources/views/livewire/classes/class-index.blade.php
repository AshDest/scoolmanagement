{{-- Liste Classes: card, recherche, pagination, modal de création/édition. --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-body d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-building text-primary"></i>
            <h5 class="mb-0">Classes</h5>
            <span class="badge bg-secondary-subtle text-secondary border rounded-pill">{{ $classes->total() }}</span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <div class="input-group input-group-sm" style="min-width:240px;">
                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Rechercher nom/niveau..." wire:model.debounce.300ms="search">
            </div>
            <button
                class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                onclick="
          window.Livewire.dispatch('open-class-form', { id: null });
          const el = document.getElementById('classFormModal');
          bootstrap.Modal.getOrCreateInstance(el).show();
        "
            >
                <i class="bi bi-plus-circle"></i> Nouvelle
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th style="width:72px">#</th>
                <th>Nom</th>
                <th>Niveau</th>
                <th>Méta</th>
                <th class="text-end" style="width:160px">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($classes as $c)
                <tr>
                    <td><span class="badge text-bg-light border">{{ $c->id }}</span></td>
                    <td class="fw-semibold">{{ $c->name }}</td>
                    <td>{{ $c->level ?? '—' }}</td>
                    <td>
                        @if($c->meta)
                            <span class="badge text-bg-light border">JSON</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <button
                            class="btn btn-sm btn-outline-secondary"
                            title="Modifier"
                            onclick="
                window.Livewire.dispatch('open-class-form', { id: {{ $c->id }} });
                const el = document.getElementById('classFormModal');
                bootstrap.Modal.getOrCreateInstance(el).show();
              "
                        >
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" title="Supprimer" wire:click="delete({{ $c->id }})"
                                onclick="return confirm('Supprimer cette classe ?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-inbox me-2"></i>Aucune classe</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if (session('status'))
        <div class="card-footer bg-body">
            <div class="alert alert-info mb-0 py-2">{{ session('status') }}</div>
        </div>
    @endif

    <div class="card-footer bg-body">
        {{ $classes->links() }}
    </div>

    @livewire('classes.class-form')
</div>
