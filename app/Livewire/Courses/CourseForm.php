<?php
// Composant Livewire: gestion cours + affectation classes/enseignants + synchro inscriptions après affectation.
namespace App\Livewire\Courses;

use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class CourseForm extends Component
{
    public ?int $id = null;
    public string $code = '';
    public string $title = '';
    public ?string $description = null;
    public int $credits = 0;

    public array $class_ids = [];
    public array $teacher_ids = [];

    #[On('open-course-form')]
    public function load(?int $id): void
    {
        $this->resetValidation();
        if ($id) {
            $c = Course::with(['classes:id','teachers:id'])->findOrFail($id);
            $this->id = $c->id;
            $this->code = $c->code;
            $this->title = $c->title;
            $this->description = $c->description;
            $this->credits = (int) $c->credits;
            $this->class_ids = $c->classes->pluck('id')->all();
            $this->teacher_ids = $c->teachers->pluck('id')->all();
        } else {
            $this->reset(['id','code','title','description','credits','class_ids','teacher_ids']);
            $this->credits = 0;
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'code' => ['required','string','max:50', Rule::unique('courses','code')->ignore($this->id)],
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'credits' => ['required','integer','min:0','max:50'],
            'class_ids' => ['array'],
            'class_ids.*' => ['integer','exists:classes,id'],
            'teacher_ids' => ['array'],
            'teacher_ids.*' => ['integer','exists:users,id'],
        ]);

        $course = Course::updateOrCreate(['id' => $this->id], $data);

        // Récupérer anciennes classes liées pour détecter ce qui est ajouté
        $oldClassIds = $course->classes()->pluck('classes.id')->all();

        // sync pivots
        $course->classes()->sync($this->class_ids ?? []);
        $course->teachers()->sync($this->teacher_ids ?? []);

        // Déterminer les classes nouvellement ajoutées
        $newClassIds = $course->classes()->pluck('classes.id')->all();
        $added = array_values(array_diff($newClassIds, $oldClassIds));

        // Pour chaque classe nouvellement liée, inscrire tous ses étudiants aux sessions existantes de ce cours
        if (!empty($added)) {
            foreach (SchoolClass::whereIn('id', $added)->get() as $class) {
                $class->enrollAllStudentsToCourseIds([$course->id]);
            }
        }

        session()->flash('status', 'Cours enregistré.');
        $this->dispatch('course-saved');
        $this->dispatch('close-course-modal');
    }

    public function render()
    {
        return view('livewire.courses.course-form', [
            'allClasses' => SchoolClass::orderBy('name')->get(['id','name']),
            'allTeachers' => User::role('teacher')->orderBy('name')->get(['id','name']),
        ]);
    }
}
