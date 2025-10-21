{{-- Saisie des composantes: groupée par étudiant, ses cours/sessions listés avec inputs CW/QZ/EX. --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-body d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clipboard-check text-primary"></i>
            <h5 class="mb-0">Cotation</h5>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <select class="form-select form-select-sm" wire:model="offeringId" style="min-width:260px;">
                <option value="">Toutes les sessions</option>
                @foreach($offerings as $o)
                    <option value="{{ $o->id }}">{{ $o->course->code }} - {{ $o->course->title }} ({{ $o->term }})</option>
                @endforeach
            </select>
            <div class="input-group input-group-sm" style="min-width:240px;">
                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Rechercher étudiant..." wire:model.debounce.300ms="search">
            </div>
        </div>
    </div>

    @if (session('status')) <div class="card-body pt-3 pb-0"><div class="alert alert-success">{{ session('status') }}</div></div> @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th>Étudiant</th>
                <th>Classe</th>
                <th>Cours (session)</th>
                <th style="width:100px">CW</th>
                <th style="width:100px">QZ</th>
                <th style="width:100px">EX</th>
                <th>Final</th>
                <th class="text-end" style="width:140px">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($rowsPage as $stu)
                @php $first = true; @endphp
                @forelse($stu->enrollments as $en)
                    <tr>
                        @if($first)
                            <td rowspan="{{ max(1, $stu->enrollments->count()) }}" class="align-top">
                                <div class="fw-semibold">{{ $stu->last_name }} {{ $stu->first_name }}</div>
                                <div class="small text-muted">{{ $stu->registration_number }}</div>
                            </td>
                            <td rowspan="{{ max(1, $stu->enrollments->count()) }}" class="align-top">
                                {{ $stu->class?->name ?? '—' }}
                            </td>
                            @php $first = false; @endphp
                        @endif
                        <td>
              <span class="badge text-bg-light border" title="{{ $en->courseOffering->course->title }}">
                {{ $en->courseOffering->course->code }} ({{ $en->courseOffering->term }})
              </span>
                        </td>
                        <td>
                            <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm @error("rows.$en->id.cw") is-invalid @enderror"
                                   wire:model.lazy="rows.{{ $en->id }}.cw" placeholder="0..100"
                                   value="{{ $rows[$en->id]['cw'] ?? $en->components?->cw }}">
                            @error("rows.$en->id.cw") <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </td>
                        <td>
                            <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm @error("rows.$en->id.qz") is-invalid @enderror"
                                   wire:model.lazy="rows.{{ $en->id }}.qz" placeholder="0..100"
                                   value="{{ $rows[$en->id]['qz'] ?? $en->components?->qz }}">
                            @error("rows.$en->id.qz") <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </td>
                        <td>
                            <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm @error("rows.$en->id.ex") is-invalid @enderror"
                                   wire:model.lazy="rows.{{ $en->id }}.ex" placeholder="0..100"
                                   value="{{ $rows[$en->id]['ex'] ?? $en->components?->ex }}">
                            @error("rows.$en->id.ex") <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </td>
                        <td>
                            @if(!is_null($en->grade?->score))
                                <span class="badge rounded-pill text-bg-success-subtle border text-success">{{ $en->grade->score }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-primary" wire:click="saveRow({{ $en->id }})">
                                <i class="bi bi-check2-circle me-1"></i> Enregistrer
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="fw-semibold">{{ $stu->last_name }} {{ $stu->first_name }}</td>
                        <td>{{ $stu->class?->name ?? '—' }}</td>
                        <td colspan="5" class="text-muted">Aucune session</td>
                        <td></td>
                    </tr>
                @endforelse
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4"><i class="bi bi-inbox me-2"></i>Aucune donnée</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-body">
        {{ $rowsPage->links() }}
    </div>
</div>
