{{-- Modal session: sélectionner le cours, saisir term/room. --}}
<div wire:ignore.self class="modal fade" id="offeringFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header bg-light-subtle">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bi bi-calendar3 text-primary"></i>
                    {{ $id ? 'Modifier une session' : 'Créer une session' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cours</label>
                        <select class="form-select @error('course_id') is-invalid @enderror" wire:model.defer="course_id">
                            <option value="">Sélectionner un cours</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->code }} — {{ $c->title }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Term (ex: 2024-2025 S1)</label>
                        <input type="text" class="form-control @error('term') is-invalid @enderror" wire:model.defer="term" placeholder="2024-2025 S1">
                        @error('term') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Salle</label>
                        <input type="text" class="form-control @error('room') is-invalid @enderror" wire:model.defer="room" placeholder="A1">
                        @error('room') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
        Livewire.on('close-offering-modal', () => {
            const el = document.getElementById('offeringFormModal');
            if (el) bootstrap.Modal.getOrCreateInstance(el).hide();
        });
    });
</script>
