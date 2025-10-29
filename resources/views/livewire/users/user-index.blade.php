{{-- Liste Utilisateurs: card, header avec actions, filtres, table avec rôles. --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-body d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-circle text-primary"></i>
            <h5 class="mb-0">Utilisateurs</h5>
            <span class="badge bg-secondary-subtle text-secondary border rounded-pill">{{ $users->total() }}</span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <div class="input-group input-group-sm" style="min-width:240px;">
                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Rechercher..." wire:model.live.debounce.300ms="search">
            </div>
            <div>
                <select class="form-select form-select-sm" wire:model.live="roleFilter">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            <button
                class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                onclick="
                    window.Livewire.dispatch('open-user-form', { id: null });
                    const el = document.getElementById('userFormModal');
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
                <th>Rôles</th>
                <th class="text-end" style="width:120px">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td><span class="badge text-bg-light border">{{ $user->id }}</span></td>
                    <td class="fw-semibold">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @forelse($user->roles as $role)
                            <span class="badge rounded-pill text-bg-info-subtle border text-info me-1">
                                {{ ucfirst($role->name) }}
                            </span>
                        @empty
                            <span class="text-muted">Aucun rôle</span>
                        @endforelse
                    </td>
                    <td class="text-end">
                        <button
                            class="btn btn-sm btn-outline-secondary"
                            title="Modifier"
                            onclick="
                                window.Livewire.dispatch('open-user-form', { id: {{ $user->id }} });
                                const el = document.getElementById('userFormModal');
                                bootstrap.Modal.getOrCreateInstance(el).show();
                            "
                        >
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        @if($user->id !== auth()->id())
                        <button 
                            class="btn btn-sm btn-outline-danger" 
                            title="Supprimer" 
                            wire:click="delete({{ $user->id }})"
                            onclick="return confirm('Supprimer cet utilisateur ?')"
                        >
                            <i class="bi bi-trash"></i>
                        </button>
                        @endif
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
    @if (session('error'))
        <div class="card-footer bg-body">
            <div class="alert alert-danger mb-0 py-2">{{ session('error') }}</div>
        </div>
    @endif
    <div class="card-footer bg-body">
        {{ $users->links() }}
    </div>
    @livewire('users.user-form')
</div>
