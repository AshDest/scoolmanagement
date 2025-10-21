<?php
// Composant Livewire: définit la pondération CW/QZ/EX par cours (somme = 100).
namespace App\Livewire\Grading;

use App\Models\Course;
use App\Models\GradeScheme;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class GradeSchemeIndex extends Component
{
    public ?int $course_id = null;
    public int $cw_weight = 20;
    public int $qz_weight = 30;
    public int $ex_weight = 50;

    public function mount(): void
    {
        $first = Course::first();
        $this->course_id = $first?->id;
        $this->loadScheme();
    }

    public function updatedCourseId(): void
    {
        $this->loadScheme();
    }

    private function loadScheme(): void
    {
        if (!$this->course_id) return;
        $s = GradeScheme::where('course_id',$this->course_id)->first();
        if ($s) {
            $this->cw_weight = (int)$s->cw_weight;
            $this->qz_weight = (int)$s->qz_weight;
            $this->ex_weight = (int)$s->ex_weight;
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'course_id' => ['required','exists:courses,id'],
            'cw_weight' => ['required','integer','min:0','max:100'],
            'qz_weight' => ['required','integer','min:0','max:100'],
            'ex_weight' => ['required','integer','min:0','max:100'],
        ]);
        if ($data['cw_weight'] + $data['qz_weight'] + $data['ex_weight'] !== 100) {
            $this->addError('cw_weight', 'La somme des pondérations doit être égale à 100.');
            return;
        }

        GradeScheme::updateOrCreate(
            ['course_id' => $this->course_id],
            $data
        );

        session()->flash('status','Schéma de cotation enregistré.');
    }

    public function render()
    {
        return view('livewire.grading.grade-scheme-index', [
            'courses' => Course::orderBy('code')->get(['id','code','title']),
        ]);
    }
}
