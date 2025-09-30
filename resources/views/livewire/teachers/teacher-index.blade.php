{{-- Vue: liste des enseignants (users role=teacher) avec CRUD. --}}
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Enseignants</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#teacherFormModal" wire:click="$emit('open-teacher-form', null)">Nouveau</button>
    </div>
    @if (session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif
    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Rechercher..." wire:model.debounce.400ms="search">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead><tr><th>#</th><th>Nom</th><th>Email</th><th></th></tr></thead>
            <tbody>
            @forelse($teachers as $t)
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->name }}</td>
                    <td>{{ $t->email }}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#teacherFormModal" wire:click="$emit('open-teacher-form', {{ $t->id }})">Modifier</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $t->id }})" onclick="return confirm('Supprimer cet enseignant ?')">Supprimer</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Aucun résultat</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $teachers->links() }}
    @livewire('teachers.teacher-form')
</div>
