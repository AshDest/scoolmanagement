<?php
// Composant Livewire: liste/recherche/pagination des classes avec suppression + ouverture du formulaire.
namespace App\Livewire\Classes;

use App\Models\SchoolClass;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ClassIndex extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    #[On('class-saved')]
    public function refreshList(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $c = SchoolClass::findOrFail($id);
        // Option: empêcher si des students y sont — à adapter selon votre règle
        if ($c->students()->exists()) {
            session()->flash('status', "Impossible de supprimer: des étudiants sont liés.");
            return;
        }
        $c->delete();
        session()->flash('status', 'Classe supprimée.');
        $this->resetPage();
    }

    public function render()
    {
        $query = SchoolClass::query()
            ->when($this->search !== '', function ($q) {
                $s = '%'.$this->search.'%';
                $q->where('name','like',$s)->orWhere('level','like',$s);
            })
            ->orderBy('name');

        return view('livewire.classes.class-index', [
            'classes' => $query->paginate(10),
        ]);
    }
}
