{{-- Listing des inscriptions: groupé par étudiant, cours listés dans une seule colonne. --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-body d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clipboard-data text-primary"></i>
            <h5 class="mb-0">Inscriptions</h5>
            <span class="badge bg-secondary-subtle text-secondary border rounded-pill">{{ $rows->total() }}</span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <select class="form-select form-select-sm" wire:model="classId" style="min-width:200px;">
                <option value="">Toutes les classes</option>
                @foreach($classes as $cl)
                    <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                @endforeach
            </select>
            <select class="form-select form-select-sm" wire:model="offeringId" style="min-width:280px;">
                <option value="">Toutes les sessions</option>
                @foreach($offerings as $o)
                    <option value="{{ $o->id }}">{{ $o->course->code }} - {{ $o->course->title }} ({{ $o->term }})</option>
                @endforeach
            </select>
            <div class="input-group input-group-sm" style="min-width:240px;">
                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Rechercher étudiant (nom/matricule)..." wire:model.debounce.300ms="search">
            </div>
        </div>
    </div>

    @if (session('status')) <div class="card-body pt-3 pb-0"><div class="alert alert-info">{{ session('status') }}</div></div> @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Étudiant</th>
                <th>Matricule</th>
                <th>Classe</th>
                <th>Cours (sessions)</th>
            </tr>
            </thead>
            <tbody>
            @forelse($rows as $stu)
                <tr>
                    <td><span class="badge text-bg-light border">{{ $stu->id }}</span></td>
                    <td class="fw-semibold">{{ $stu->last_name }} {{ $stu->first_name }}</td>
                    <td>{{ $stu->registration_number }}</td>
                    <td>{{ $stu->class?->name ?? '—' }}</td>
                    <td>
                        @php
                            $ens = $stu->enrollments;
                        @endphp
                        @if($ens->isEmpty())
                            <span class="text-muted">Aucune session</span>
                        @else
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($ens as $en)
                                    <span class="badge text-bg-light border" title="{{ $en->courseOffering?->course?->title }}">
                    {{ $en->courseOffering?->course?->code }} ({{ $en->courseOffering?->term }})
                  </span>
                                @endforeach
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-inbox me-2"></i>Aucune inscription</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-body">
        {{ $rows->links() }}
    </div>
</div>
