{{-- Form Classe: modal claire, JSON libre pour méta. --}}
<div wire:ignore.self class="modal fade" id="classFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header bg-light-subtle">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bi bi-building text-primary"></i>
                    {{ $id ? 'Modifier une classe' : 'Créer une classe' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name" placeholder="Ex: 3A, Terminale S">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Niveau</label>
                        <input type="text" class="form-control @error('level') is-invalid @enderror" wire:model.defer="level" placeholder="Primary / Middle / Secondary">
                        @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Méta (JSON léger)</label>
                        <input type="text" class="form-control" wire:model.defer="meta.note" placeholder="Note libre (stockée en JSON)">
                    </div>
                </div>
                <div class="modal-footer bg-light-subtle">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button"><i class="bi bi-x-circle me-1"></i>Annuler</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-1"></i>Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('close-class-modal', () => {
            const el = document.getElementById('classFormModal');
            if (el) bootstrap.Modal.getOrCreateInstance(el).hide();
        });
    });
</script>
