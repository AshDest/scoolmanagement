{{-- Form Cours: ajout multi-sélection classes et enseignants. --}}
<div wire:ignore.self class="modal fade" id="courseFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content shadow">
            <div class="modal-header bg-light-subtle">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bi bi-bookmark-plus text-primary"></i>
                    {{ $id ? 'Modifier un cours' : 'Créer un cours' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" wire:model.defer="code" placeholder="MAT101">
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model.defer="title" placeholder="Mathématiques 1">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3" wire:model.defer="description" placeholder="Description du cours..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Crédits</label>
                        <input type="number" min="0" max="50" class="form-control @error('credits') is-invalid @enderror" wire:model.defer="credits">
                        @error('credits') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Classes concernées</label>
                        <select multiple class="form-select @error('class_ids.*') is-invalid @enderror" wire:model.defer="class_ids">
                            @foreach($allClasses as $cl)
                                <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Maintenir Ctrl/⌘ pour sélection multiple.</div>
                        @error('class_ids.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Enseignants affectés</label>
                        <select multiple class="form-select @error('teacher_ids.*') is-invalid @enderror" wire:model.defer="teacher_ids">
                            @foreach($allTeachers as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Maintenir Ctrl/⌘ pour sélection multiple.</div>
                        @error('teacher_ids.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
        Livewire.on('close-course-modal', () => {
            const el = document.getElementById('courseFormModal');
            if (el) bootstrap.Modal.getOrCreateInstance(el).hide();
        });
    });
</script>
