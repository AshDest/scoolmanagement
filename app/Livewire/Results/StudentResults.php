<?php
// Composant Livewire: affiche la synthèse des résultats d'un étudiant connecté ou choisi.
namespace App\Livewire\Results;

use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StudentResults extends Component
{
    public ?int $studentId = null;

    public function mount(?int $studentId = null): void
    {
        $this->studentId = $studentId ?? auth()->user()?->student?->id;
        abort_if(!$this->studentId, 404);
    }

    public function render()
    {
        $student = Student::with('enrollments.courseOffering.course','enrollments.grade')->findOrFail($this->studentId);
        return view('livewire.results.student-results', compact('student'));
    }
}
