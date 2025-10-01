{{-- Vue: modal Bootstrap pour créer/éditer un étudiant avec erreurs de validation. --}}
<div wire:ignore.self class="modal fade" id="studentFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form wire:submit.prevent="save">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $id ? 'Modifier' : 'Créer' }} un étudiant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prénom</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" wire:model.defer="first_name">
                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" wire:model.defer="last_name">
                            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Classe</label>
                            <select class="form-select @error('class_id') is-invalid @enderror" wire:model.defer="class_id">
                                <option value="">--</option>
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
                            <input type="text" class="form-control @error('registration_number') is-invalid @enderror" wire:model.defer="registration_number">
                            @error('registration_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Extra (JSON léger)</label>
                            <input type="text" class="form-control" placeholder="note libre..." wire:model.defer="extra.note">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Annuler</button>
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('close-student-modal', () => {
            const el = document.getElementById('studentFormModal');
            if (el) {
                const m = bootstrap.Modal.getInstance(el) ?? bootstrap.Modal.getOrCreateInstance(el);
                m.hide();
            }
        });
    });
</script>
