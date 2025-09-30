<?php
// Composant Livewire: visualisation d'un profil étudiant avec inscriptions et notes.
namespace App\Http\Livewire\Students;

use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StudentProfile extends Component
{
    public int $studentId;

    public function mount(int $studentId): void
    {
        $this->studentId = $studentId;
    }

    public function render()
    {
        $student = Student::with([
            'class',
            'enrollments.courseOffering.course',
            'enrollments.grade',
        ])->findOrFail($this->studentId);

        return view('livewire.students.student-profile', compact('student'));
    }
}
