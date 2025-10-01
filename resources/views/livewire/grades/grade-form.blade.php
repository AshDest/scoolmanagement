{{-- Form Note: modal compacte, inputs resserrés. --}}
<div wire:ignore.self class="modal fade" id="gradeFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content shadow">
            <div class="modal-header bg-light-subtle">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square text-primary"></i>
                    Saisie de note
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <input type="hidden" wire:model="enrollment_id">
                    <div class="mb-3">
                        <label class="form-label">Score (0-100)</label>
                        <input type="number" min="0" max="100" step="0.01" class="form-control @error('score') is-invalid @enderror" wire:model.defer="score" placeholder="Ex: 15.5">
                        @error('score') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Lettre</label>
                        <input type="text" class="form-control @error('letter') is-invalid @enderror" wire:model.defer="letter" placeholder="A, B+, ...">
                        @error('letter') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
        Livewire.on('close-grade-modal', () => {
            const el = document.getElementById('gradeFormModal');
            if (el) bootstrap.Modal.getOrCreateInstance(el).hide();
        });
    });
</script>
