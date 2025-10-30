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
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.defer="email" placeholder="etudiant@example.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if(!$id)
                            <div class="form-text">Un compte sera créé avec mot de passe: <strong>password123</strong></div>
                            @endif
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
                            <hr class="my-3">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-geo-alt me-2"></i>Informations de contact
                            </h6>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Adresse</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" wire:model.defer="address" rows="2" placeholder="Adresse complète de l'étudiant"></textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <hr class="my-3">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-person-badge me-2"></i>Informations du tuteur
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nom du tuteur</label>
                            <input type="text" class="form-control @error('tutor_name') is-invalid @enderror" wire:model.defer="tutor_name" placeholder="Nom complet du tuteur">
                            @error('tutor_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Numéro du tuteur</label>
                            <input type="text" class="form-control @error('tutor_phone') is-invalid @enderror" wire:model.defer="tutor_phone" placeholder="+243 XXX XXX XXX">
                            @error('tutor_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <hr class="my-3">
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
