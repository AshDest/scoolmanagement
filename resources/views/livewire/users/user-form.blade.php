{{-- Form Utilisateur: modal avec gestion des rôles et mot de passe. --}}
<div wire:ignore.self class="modal fade" id="userFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow">
            <div class="modal-header bg-light-subtle">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus text-primary"></i>
                    {{ $id ? 'Modifier un utilisateur' : 'Créer un utilisateur' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nom complet</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   wire:model.defer="name" placeholder="Jean Dupont">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   wire:model.defer="email" placeholder="jean.dupont@example.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        @if($id)
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="changePassword" 
                                       wire:model.live="changePassword">
                                <label class="form-check-label" for="changePassword">
                                    Changer le mot de passe
                                </label>
                            </div>
                        </div>
                        @endif
                        @if(!$id || $changePassword)
                        <div class="col-md-6">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   wire:model.defer="password" placeholder="••••••••">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Minimum 8 caractères</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   wire:model.defer="password_confirmation" placeholder="••••••••">
                            @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        @endif
                        <div class="col-12">
                            <label class="form-label">Rôles</label>
                            <div class="border rounded p-3">
                                @foreach($roles as $role)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="role_{{ $role->id }}" 
                                               value="{{ $role->name }}"
                                               wire:model.defer="selectedRoles">
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('selectedRoles') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light-subtle">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">
                        <i class="bi bi-x-circle me-1"></i>Annuler
                    </button>
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2-circle me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('close-user-modal', () => {
            const el = document.getElementById('userFormModal');
            if (el) bootstrap.Modal.getOrCreateInstance(el).hide();
        });
    });
</script>
