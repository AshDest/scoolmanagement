<?php
// Composant Livewire: listing complet + inscription UI + promotion contrôlée (moyenne).
namespace App\Livewire\Enrollments;

use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class EnrollmentIndex extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    // Filtres listing
    public ?int $classId = null;
    public ?int $offeringId = null;
    public string $search = '';

    // Formulaire d'inscription / promotion
    public ?int $studentId = null;
    public ?int $targetClassId = null;

    // Seuil de réussite pour promotion (0..100)
    protected int $promotionThreshold = 50;

    public function updatingClassId() { $this->resetPage(); }
    public function updatingOfferingId() { $this->resetPage(); }
    public function updatingSearch() { $this->resetPage(); }

    private function computeStudentAverage(int $studentId): ?float
    {
        $grades = Grade::whereHas('enrollment', fn($q)=>$q->where('student_id', $studentId))
            ->pluck('score')->filter()->values();

        if ($grades->count() === 0) return null;
        return round($grades->avg(), 2);
    }

    private function enrollToClassOfferings(Student $student, SchoolClass $class): void
    {
        $courseIds = $class->courses()->pluck('courses.id');
        if ($courseIds->isEmpty()) return;

        $offeringIds = CourseOffering::whereIn('course_id', $courseIds)->pluck('id');
        foreach ($offeringIds as $offId) {
            Enrollment::firstOrCreate(
                ['student_id' => $student->id, 'course_offering_id' => $offId],
                ['status' => 'enrolled', 'meta' => null]
            );
        }
    }

    public function assignClass(): void
    {
        $this->validate([
            'studentId' => ['required','exists:students,id'],
            'targetClassId' => ['required','exists:classes,id'],
        ]);

        $student = Student::with('class')->findOrFail($this->studentId);
        $target = SchoolClass::findOrFail($this->targetClassId);

        $isNew = $student->class_id === null;
        $isPromotion = (!$isNew && $student->class_id !== $target->id);

        if ($isPromotion) {
            $avg = $this->computeStudentAverage($student->id);
            if (is_null($avg) || $avg < $this->promotionThreshold) {
                session()->flash('status', "Promotion refusée: moyenne ".($avg ?? '—')." < {$this->promotionThreshold}.");
                return;
            }
        }

        $student->class_id = $target->id;
        $student->save();

        $this->enrollToClassOfferings($student, $target);

        session()->flash('status', $isNew
            ? "Nouvelle inscription effectuée."
            : ($isPromotion ? "Promotion réussie vers {$target->name}." : "Classe mise à jour.")
        );

        $this->reset(['studentId','targetClassId']);
        $this->resetPage();
    }

    public function render()
    {
        // On groupe par étudiant: on charge les Students avec leurs enrollments filtrés
        $studentsQuery = \App\Models\Student::query()
            ->with(['class'])
            ->when($this->classId, fn($q) => $q->where('class_id', $this->classId))
            ->when($this->search !== '', function($q){
                $s = '%'.$this->search.'%';
                $q->where(function($w) use ($s){
                    $w->where('first_name','like',$s)
                        ->orWhere('last_name','like',$s)
                        ->orWhere('registration_number','like',$s);
                });
            })
            ->orderBy('last_name')->orderBy('first_name');

        // Eager load des enrollments filtrés éventuellement par offeringId
        $studentsQuery->with(['enrollments' => function($q){
            $q->with(['courseOffering.course']);
            if ($this->offeringId) $q->where('course_offering_id', $this->offeringId);
        }]);

        return view('livewire.enrollments.enrollment-index', [
            'rows' => $studentsQuery->paginate(12), // rows = Students paginés
            'classes' => \App\Models\SchoolClass::orderBy('name')->get(['id','name']),
            'offerings' => \App\Models\CourseOffering::with('course')->orderByDesc('id')->get(['id','course_id','term']),
        ]);
    }
}
