{{-- Vue: affiche la liste des cours avec recherche et actions CRUD. --}}
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Cours</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#courseFormModal" wire:click="$emit('open-course-form', null)">Nouveau</button>
    </div>
    @if (session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif
    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Rechercher..." wire:model.debounce.400ms="search">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead><tr><th>#</th><th>Code</th><th>Titre</th><th>Crédits</th><th></th></tr></thead>
            <tbody>
            @forelse($courses as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->code }}</td>
                    <td>{{ $c->title }}</td>
                    <td>{{ $c->credits }}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#courseFormModal" wire:click="$emit('open-course-form', {{ $c->id }})">Modifier</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $c->id }})" onclick="return confirm('Supprimer ce cours ?')">Supprimer</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Aucun résultat</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $courses->links() }}
    @livewire('courses.course-form')
</div>
