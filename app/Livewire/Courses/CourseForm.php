<?php
// Composant Livewire: remplace $emit par dispatch pour fermeture/rafraîchissement.
namespace App\Livewire\Courses;

use App\Models\Course;
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

    #[On('open-course-form')]
    public function load(?int $id): void
    {
        $this->resetValidation();
        if ($id) {
            $c = Course::findOrFail($id);
            $this->id = $c->id;
            $this->code = $c->code;
            $this->title = $c->title;
            $this->description = $c->description;
            $this->credits = (int) $c->credits;
        } else {
            $this->reset(['id','code','title','description','credits']);
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
        ]);

        Course::updateOrCreate(['id' => $this->id], $data);

        session()->flash('status', 'Cours enregistré.');
        $this->dispatch('course-saved');
        $this->dispatch('close-course-modal');
    }

    public function render() { return view('livewire.courses.course-form'); }
}
