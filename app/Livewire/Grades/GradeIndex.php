<?php
// Composant Livewire: liste des notes filtrées par cours/session et étudiant.
namespace App\Http\Livewire\Grades;

use App\Models\CourseOffering;
use App\Models\Enrollment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class GradeIndex extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public ?int $offeringId = null;
    public string $search = '';

    #[On('grade-saved')]
    public function refreshList(): void { $this->resetPage(); }

    public function render()
    {
        $query = Enrollment::query()
            ->with(['student','courseOffering.course','grade'])
            ->when($this->offeringId, fn($q) => $q->where('course_offering_id',$this->offeringId))
            ->when($this->search !== '', function($q){
                $s = '%'.$this->search.'%';
                $q->whereHas('student', fn($w) => $w->where('first_name','like',$s)->orWhere('last_name','like',$s)->orWhere('registration_number','like',$s));
            })
            ->orderByDesc('id');

        return view('livewire.grades.grade-index', [
            'rows' => $query->paginate(10),
            'offerings' => CourseOffering::with('course')->orderByDesc('id')->get(),
        ]);
    }
}
