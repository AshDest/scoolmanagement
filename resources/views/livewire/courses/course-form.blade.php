{{-- Vue: modal Bootstrap pour créer/éditer un cours avec erreurs de validation. --}}
<div wire:ignore.self class="modal fade" id="courseFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form wire:submit.prevent="save">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $id ? 'Modifier' : 'Créer' }} un cours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" wire:model.defer="code">
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model.defer="title">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3" wire:model.defer="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Crédits</label>
                        <input type="number" min="0" max="50" class="form-control @error('credits') is-invalid @enderror" wire:model.defer="credits">
                        @error('credits') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
        Livewire.on('close-course-modal', () => {
            const el = document.getElementById('courseFormModal');
            if (el) bootstrap.Modal.getInstance(el)?.hide();
        });
    });
</script>
