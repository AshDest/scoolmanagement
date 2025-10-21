{{-- Liste Cours: affiche classes/enseignants affectés (badges), garde recherche/suppression existantes. --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-body d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-book-half text-primary"></i>
            <h5 class="mb-0">Cours</h5>
            <span class="badge bg-secondary-subtle text-secondary border rounded-pill">{{ $courses->total() }}</span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <div class="input-group input-group-sm" style="min-width:240px;">
                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Rechercher code/titre..." wire:model.debounce.300ms="search">
            </div>
            <button
                class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                onclick="
          window.Livewire.dispatch('open-course-form', { id: null });
          const el = document.getElementById('courseFormModal');
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
                <th>Code</th>
                <th>Titre</th>
                <th>Classes</th>
                <th>Enseignants</th>
                <th class="text-end" style="width:160px">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($courses as $c)
                <tr>
                    <td><span class="badge text-bg-light border">{{ $c->id }}</span></td>
                    <td class="fw-semibold">{{ $c->code }}</td>
                    <td>{{ $c->title }}</td>
                    <td>
                        @php $cls = $c->classes()->orderBy('name')->pluck('name')->all(); @endphp
                        @forelse($cls as $n)
                            <span class="badge rounded-pill text-bg-primary-subtle border text-primary">{{ $n }}</span>
                        @empty
                            <span class="text-muted">—</span>
                        @endforelse
                    </td>
                    <td>
                        @php $tch = $c->teachers()->orderBy('name')->pluck('name')->all(); @endphp
                        @forelse($tch as $n)
                            <span class="badge text-bg-light border">{{ $n }}</span>
                        @empty
                            <span class="text-muted">—</span>
                        @endforelse
                    </td>
                    <td class="text-end">
                        <button
                            class="btn btn-sm btn-outline-secondary"
                            title="Modifier"
                            onclick="
                window.Livewire.dispatch('open-course-form', { id: {{ $c->id }} });
                const el = document.getElementById('courseFormModal');
                bootstrap.Modal.getOrCreateInstance(el).show();
              "
                        >
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" title="Supprimer" wire:click="delete({{ $c->id }})"
                                onclick="return confirm('Supprimer ce cours ?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
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
        {{ $courses->links() }}
    </div>

    @livewire('courses.course-form')
</div>
