<?php
// Composant Livewire: saisie des composantes CW/QZ/EX par inscription et calcul de la note finale.
namespace App\Livewire\Grading;

use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\GradeComponent;
use App\Models\GradeScheme;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class GradeEntry extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public ?int $offeringId = null;
    public string $search = '';

    public array $rows = []; // temp storage for inline edit: [enrollment_id => ['cw'=>..,'qz'=>..,'ex'=>..]]

    public function updatingOfferingId() { $this->resetPage(); }
    public function updatingSearch() { $this->resetPage(); }

    private function computeFinal(?int $courseId, ?float $cw, ?float $qz, ?float $ex): ?float
    {
        if (!$courseId) return null;
        $scheme = GradeScheme::where('course_id',$courseId)->first();
        $wCW = $scheme?->cw_weight ?? 20;
        $wQZ = $scheme?->qz_weight ?? 30;
        $wEX = $scheme?->ex_weight ?? 50;
        $parts = [];
        if ($cw !== null) $parts[] = $cw * $wCW / 100;
        if ($qz !== null) $parts[] = $qz * $wQZ / 100;
        if ($ex !== null) $parts[] = $ex * $wEX / 100;
        if (empty($parts)) return null;
        return round(array_sum($parts), 2);
    }

    public function saveRow(int $enrollmentId): void
    {
        $this->validate([
            "rows.$enrollmentId.cw" => ['nullable','numeric','min:0','max:100'],
            "rows.$enrollmentId.qz" => ['nullable','numeric','min:0','max:100'],
            "rows.$enrollmentId.ex" => ['nullable','numeric','min:0','max:100'],
        ]);

        $en = Enrollment::with('courseOffering.course')->findOrFail($enrollmentId);
        $vals = $this->rows[$enrollmentId] ?? [];
        $cw = isset($vals['cw']) ? (float)$vals['cw'] : null;
        $qz = isset($vals['qz']) ? (float)$vals['qz'] : null;
        $ex = isset($vals['ex']) ? (float)$vals['ex'] : null;

        // Components
        GradeComponent::updateOrCreate(
            ['enrollment_id' => $enrollmentId],
            ['cw' => $cw, 'qz' => $qz, 'ex' => $ex]
        );

        // Final grade
        $final = $this->computeFinal($en->courseOffering->course?->id, $cw, $qz, $ex);
        Grade::updateOrCreate(
            ['enrollment_id' => $enrollmentId],
            ['score' => $final, 'letter' => null]
        );

        session()->flash('status', 'Cotation enregistrée.');
    }

    public function render()
    {
        // On charge des Students (pas des enrollments), et on joint leurs enrollments filtrés
        $studentsQuery = \App\Models\Student::query()
            ->when($this->search !== '', function($q){
                $s = '%'.$this->search.'%';
                $q->whereHas('enrollments', fn($w)=>$w) // assure qu'au moins une inscription existe si tu veux
                ->where(function($w) use ($s){
                    $w->where('first_name','like',$s)
                        ->orWhere('last_name','like',$s)
                        ->orWhere('registration_number','like',$s);
                });
            })
            ->orderBy('last_name')->orderBy('first_name');

        // eager load enrollments filtrés par offeringId si défini
        $studentsQuery->with(['class','enrollments' => function($q){
            $q->with(['courseOffering.course','components','grade']);
            if ($this->offeringId) $q->where('course_offering_id', $this->offeringId);
        }]);

        return view('livewire.grading.grade-entry', [
            'rowsPage' => $studentsQuery->paginate(10), // des Students paginés
            'offerings' => \App\Models\CourseOffering::with('course')->orderByDesc('id')->get(),
        ]);
    }
}
