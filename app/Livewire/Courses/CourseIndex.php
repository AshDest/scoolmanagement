<?php
// Composant Livewire: liste/recherche/pagination des cours avec suppression.
namespace App\Livewire\Courses;

use App\Models\Course;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CourseIndex extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    #[On('course-saved')]
    public function refreshList(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        $this->authorize('delete', Course::findOrFail($id));
        Course::findOrFail($id)->delete();
        session()->flash('status', 'Cours supprimé.');
        $this->resetPage();
    }

    public function render()
    {
        $query = Course::query()
            ->when($this->search !== '', function ($q) {
                $s = '%'.$this->search.'%';
                $q->where('code','like',$s)->orWhere('title','like',$s);
            })
            ->orderBy('code');

        return view('livewire.courses.course-index', [
            'courses' => $query->paginate(10),
        ]);
    }
}
