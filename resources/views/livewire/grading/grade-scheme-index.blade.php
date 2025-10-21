{{-- Schéma de cotation: définir CW/QZ/EX (somme 100) par cours. --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-body d-flex align-items-center gap-2">
        <i class="bi bi-sliders text-primary"></i>
        <h5 class="mb-0">Schéma de cotation</h5>
    </div>
    <div class="card-body">
        @if (session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Cours</label>
                <select class="form-select" wire:model="course_id">
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}">{{ $c->code }} — {{ $c->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12"></div>

            <div class="col-12 col-md-4">
                <label class="form-label">Travaux en classe (%)</label>
                <input type="number" min="0" max="100" class="form-control @error('cw_weight') is-invalid @enderror" wire:model.defer="cw_weight">
                @error('cw_weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Interrogations (%)</label>
                <input type="number" min="0" max="100" class="form-control @error('qz_weight') is-invalid @enderror" wire:model.defer="qz_weight">
                @error('qz_weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Examens (%)</label>
                <input type="number" min="0" max="100" class="form-control @error('ex_weight') is-invalid @enderror" wire:model.defer="ex_weight">
                @error('ex_weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
    <div class="card-footer bg-body">
        <button class="btn btn-primary" wire:click="save">
            <i class="bi bi-check2-circle me-1"></i> Enregistrer
        </button>
    </div>
</div>
