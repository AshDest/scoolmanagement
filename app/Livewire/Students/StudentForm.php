<?php
// Composant Livewire: formulaire création/édition d'un étudiant avec validation.
namespace App\Http\Livewire\Students;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class StudentForm extends Component
{
    public ?int $id = null;
    public ?int $class_id = null;
    public string $first_name = '';
    public string $last_name = '';
    public ?string $dob = null;
    public string $registration_number = '';
    public array $extra = [];

    #[On('open-student-form')]
    public function load(?int $id): void
    {
        $this->resetValidation();
        if ($id) {
            $s = Student::findOrFail($id);
            $this->id = $s->id;
            $this->class_id = $s->class_id;
            $this->first_name = $s->first_name;
            $this->last_name = $s->last_name;
            $this->dob = optional($s->dob)->format('Y-m-d');
            $this->registration_number = $s->registration_number;
            $this->extra = $s->extra ?? [];
        } else {
            $this->reset(['id','class_id','first_name','last_name','dob','registration_number','extra']);
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'class_id' => ['nullable','exists:classes,id'],
            'dob' => ['nullable','date'],
            'registration_number' => ['required','string','max:255', Rule::unique('students','registration_number')->ignore($this->id)],
        ]);
        $data['extra'] = $this->extra ?: null;

        Student::updateOrCreate(['id' => $this->id], $data);

        session()->flash('status', 'Étudiant enregistré.');
        $this->dispatch('student-saved');
        $this->dispatch('close-student-modal');
    }

    public function render()
    {
        return view('livewire.students.student-form', [
            'classes' => SchoolClass::orderBy('name')->get(),
        ]);
    }
}
