<?php
// Composant Livewire: liste/recherche/pagination des étudiants avec suppression.
namespace App\Http\Livewire\Students;

use App\Models\SchoolClass;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StudentIndex extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public ?int $classId = null;

    #[On('student-saved')]
    public function refreshList(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        $this->authorize('delete', Student::findOrFail($id));
        Student::findOrFail($id)->delete();
        session()->flash('status', 'Étudiant supprimé.');
        $this->resetPage();
    }

    public function render()
    {
        $query = Student::query()
            ->with('class')
            ->when($this->search !== '', function ($q) {
                $s = '%'.$this->search.'%';
                $q->where(function($w) use ($s) {
                    $w->where('first_name','like',$s)
                        ->orWhere('last_name','like',$s)
                        ->orWhere('registration_number','like',$s);
                });
            })
            ->when($this->classId, fn($q) => $q->where('class_id',$this->classId))
            ->orderBy('last_name')->orderBy('first_name');

        return view('livewire.students.student-index', [
            'students' => $query->paginate(10),
            'classes' => SchoolClass::orderBy('name')->get(),
        ]);
    }
}
