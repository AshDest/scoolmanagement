<?php
// Composant Livewire: création/édition d'une session (CourseOffering). À la création, les inscriptions auto se feront via le hook du modèle.
namespace App\Livewire\Offerings;

use App\Models\Course;
use App\Models\CourseOffering;
use Livewire\Attributes\On;
use Livewire\Component;

class OfferingForm extends Component
{
    public ?int $id = null;
    public ?int $course_id = null;
    public string $term = '';
    public ?string $room = null;

    #[On('open-offering-form')]
    public function load(?int $id): void
    {
        $this->resetValidation();
        if ($id) {
            $o = CourseOffering::findOrFail($id);
            $this->id = $o->id;
            $this->course_id = $o->course_id;
            $this->term = $o->term;
            $this->room = $o->room;
        } else {
            $this->reset(['id','course_id','term','room']);
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'course_id' => ['required','exists:courses,id'],
            'term' => ['required','string','max:255'],
            'room' => ['nullable','string','max:255'],
        ]);

        CourseOffering::updateOrCreate(['id' => $this->id], $data);

        session()->flash('status','Session enregistrée.');
        $this->dispatch('offering-saved');
        $this->dispatch('close-offering-modal');
    }

    public function render()
    {
        return view('livewire.offerings.offering-form', [
            'courses' => Course::orderBy('code')->get(['id','code','title']),
        ]);
    }
}
