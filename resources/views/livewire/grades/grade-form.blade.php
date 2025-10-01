{{-- Vue: modal Bootstrap pour saisir une note + écoute de fermeture. --}}
<div wire:ignore.self class="modal fade" id="gradeFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form wire:submit.prevent="save">
                <div class="modal-header">
                    <h5 class="modal-title">Saisie de note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" wire:model="enrollment_id">
                    <div class="mb-3">
                        <label class="form-label">Score (0-100)</label>
                        <input type="number" min="0" max="100" step="0.01" class="form-control @error('score') is-invalid @enderror" wire:model.defer="score">
                        @error('score') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lettre</label>
                        <input type="text" class="form-control @error('letter') is-invalid @enderror" wire:model.defer="letter" placeholder="A, B+, ...">
                        @error('letter') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
        Livewire.on('close-grade-modal', () => {
            const el = document.getElementById('gradeFormModal');
            if (el) {
                const m = bootstrap.Modal.getInstance(el) ?? bootstrap.Modal.getOrCreateInstance(el);
                m.hide();
            }
        });
    });
</script>
