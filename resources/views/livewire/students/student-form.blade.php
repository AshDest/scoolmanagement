{{-- Form Étudiant: modal avec sous-titres et rangées équilibrées. --}}
<div wire:ignore.self class="modal fade" id="studentFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow">
            <div class="modal-header bg-light-subtle">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus text-primary"></i>
                    {{ $id ? 'Modifier un étudiant' : 'Créer un étudiant' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prénom</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" wire:model.defer="first_name" placeholder="Jean">
                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" wire:model.defer="last_name" placeholder="Dupont">
                            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Classe</label>
                            <select class="form-select @error('class_id') is-invalid @enderror" wire:model.defer="class_id">
                                <option value="">Sélectionner…</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de naissance</label>
                            <input type="date" class="form-control @error('dob') is-invalid @enderror" wire:model.defer="dob">
                            @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Matricule</label>
                            <input type="text" class="form-control @error('registration_number') is-invalid @enderror" wire:model.defer="registration_number" placeholder="REG-0001">
                            @error('registration_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Note interne</label>
                            <input type="text" class="form-control" placeholder="Information complémentaire…" wire:model.defer="extra.note">
                            <div class="form-text">Stocké en JSON.</div>
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
        Livewire.on('close-student-modal', () => {
            const el = document.getElementById('studentFormModal');
            if (el) bootstrap.Modal.getOrCreateInstance(el).hide();
        });
    });
</script>
