<?php
// Composant Livewire: saisie/mise à jour d'une note pour une inscription.
namespace App\Livewire\Grades;

use App\Models\Enrollment;
use App\Models\Grade;
use Livewire\Attributes\On;
use Livewire\Component;

class GradeForm extends Component
{
    public ?int $enrollment_id = null;
    public ?float $score = null;
    public ?string $letter = null;

    #[On('open-grade-form')]
    public function load(int $enrollmentId): void
    {
        $this->resetValidation();
        $this->enrollment_id = $enrollmentId;
        $grade = Grade::where('enrollment_id',$enrollmentId)->first();
        $this->score = $grade?->score;
        $this->letter = $grade?->letter;
    }

    public function save(): void
    {
        $data = $this->validate([
            'enrollment_id' => ['required','exists:enrollments,id'],
            'score' => ['nullable','numeric','min:0','max:100'],
            'letter' => ['nullable','string','max:3'],
        ]);

        Grade::updateOrCreate(['enrollment_id' => $data['enrollment_id']], [
            'score' => $data['score'],
            'letter' => $data['letter'],
        ]);

        session()->flash('status', 'Note enregistrée.');
        $this->dispatch('grade-saved');
        $this->dispatch('close-grade-modal');
    }

    public function render() { return view('livewire.grades.grade-form'); }
}
