{{-- Vue: formulaire d'assignation de rôle + aperçu des utilisateurs. --}}
<div>
    <h1 class="h3 mb-3">Rôles & Autorisations</h1>
    @if (session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif

    <form class="row g-3 mb-4" wire:submit.prevent="assign">
        <div class="col-md-5">
            <label class="form-label">Email utilisateur</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.defer="email">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-5">
            <label class="form-label">Rôle</label>
            <select class="form-select @error('role') is-invalid @enderror" wire:model.defer="role">
                <option value="">--</option>
                @foreach($roles as $r)
                    <option value="{{ $r->name }}">{{ $r->name }}</option>
                @endforeach
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">Assigner</button>
        </div>
    </form>

    <h2 class="h5">Utilisateurs (aperçu)</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Email</th><th>Nom</th><th>Rôles</th></tr></thead>
            <tbody>
            @foreach($users as $u)
                <tr>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ implode(', ', $u->getRoleNames()->toArray()) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
