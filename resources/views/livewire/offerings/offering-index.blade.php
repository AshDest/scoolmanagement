{{-- Sessions: liste avec recherche et modal de création/édition. --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-body d-flex justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-calendar3 text-primary"></i>
            <h5 class="mb-0">Sessions (Course offerings)</h5>
            <span class="badge bg-secondary-subtle text-secondary border rounded-pill">{{ $offerings->total() }}</span>
        </div>
        <div class="d-flex gap-2">
            <div class="input-group input-group-sm" style="min-width:260px;">
                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Rechercher (cours/term)..." wire:model.debounce.300ms="search">
            </div>
            <button class="btn btn-sm btn-primary"
                    onclick="
          window.Livewire.dispatch('open-offering-form', { id: null });
          const el = document.getElementById('offeringFormModal');
          bootstrap.Modal.getOrCreateInstance(el).show();
        ">
                <i class="bi bi-plus-circle"></i> Nouvelle
            </button>
        </div>
    </div>

    @if (session('status')) <div class="card-body pt-3 pb-0"><div class="alert alert-success">{{ session('status') }}</div></div> @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>#</th><th>Cours</th><th>Term</th><th>Salle</th><th class="text-end" style="width:160px">Actions</th></tr></thead>
            <tbody>
            @forelse($offerings as $o)
                <tr>
                    <td><span class="badge text-bg-light border">{{ $o->id }}</span></td>
                    <td>{{ $o->course?->code }} — {{ $o->course?->title }}</td>
                    <td><span class="badge text-bg-light border">{{ $o->term }}</span></td>
                    <td>{{ $o->room ?? '—' }}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary"
                                onclick="
                window.Livewire.dispatch('open-offering-form', { id: {{ $o->id }} });
                const el = document.getElementById('offeringFormModal');
                bootstrap.Modal.getOrCreateInstance(el).show();
              ">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $o->id }})" onclick="return confirm('Supprimer cette session ?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-inbox me-2"></i>Aucune session</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-body">
        {{ $offerings->links() }}
    </div>

    @livewire('offerings.offering-form')
</div>
