{{-- Vue: affiche la liste des étudiants avec filtres et actions CRUD. --}}
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Étudiants</h1>
        <button
            class="btn btn-primary"
            onclick="
        window.Livewire.dispatch('open-student-form', { id: null });
        const el = document.getElementById('studentFormModal');
        const m = bootstrap.Modal.getOrCreateInstance(el);
        m.show();
      "
        >
            Nouveau
        </button>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Rechercher..." wire:model.debounce.400ms="search">
        </div>
        <div class="col-md-4">
            <select class="form-select" wire:model="classId">
                <option value="">Toutes les classes</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
            <tr>
                <th>#</th><th>Matricule</th><th>Nom</th><th>Classe</th><th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($students as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ $s->registration_number }}</td>
                    <td>{{ $s->last_name }} {{ $s->first_name }}</td>
                    <td>{{ $s->class?->name ?? '-' }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-info" href="{{ route('students.profile', $s->id) }}">Profil</a>
                        <button
                            class="btn btn-sm btn-outline-secondary"
                            onclick="
                window.Livewire.dispatch('open-student-form', { id: {{ $s->id }} });
                const el = document.getElementById('studentFormModal');
                const m = bootstrap.Modal.getOrCreateInstance(el);
                m.show();
              "
                        >
                            Modifier
                        </button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $s->id }})"
                                onclick="return confirm('Supprimer cet étudiant ?')">Supprimer</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Aucun résultat</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $students->links() }}

    @livewire('students.student-form')
</div>
