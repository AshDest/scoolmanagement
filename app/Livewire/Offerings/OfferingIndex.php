<?php
// Composant Livewire: liste/recherche/pagination des sessions (course_offerings) + suppression et ouverture du formulaire.
namespace App\Livewire\Offerings;

use App\Models\CourseOffering;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class OfferingIndex extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    #[On('offering-saved')]
    public function refreshList(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        $o = CourseOffering::findOrFail($id);
        $o->delete();
        session()->flash('status', 'Session supprimée.');
        $this->resetPage();
    }

    public function render()
    {
        $query = CourseOffering::query()
            ->with('course')
            ->when($this->search !== '', function ($q) {
                $s = '%'.$this->search.'%';
                $q->where('term','like',$s)->orWhereHas('course', fn($w)=>$w->where('title','like',$s)->orWhere('code','like',$s));
            })
            ->orderByDesc('id');

        return view('livewire.offerings.offering-index', [
            'offerings' => $query->paginate(10),
        ]);
    }
}
