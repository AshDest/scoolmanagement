<?php
// Composant Livewire: liste des enseignants (users avec rôle teacher).
namespace App\Http\Livewire\Teachers;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TeacherIndex extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    #[On('teacher-saved')]
    public function refreshList(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        if ($user->hasRole('teacher')) {
            $user->delete();
            session()->flash('status', 'Enseignant supprimé.');
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = User::role('teacher')
            ->when($this->search !== '', function ($q) {
                $s = '%'.$this->search.'%';
                $q->where('name','like',$s)->orWhere('email','like',$s);
            })
            ->orderBy('name');

        return view('livewire.teachers.teacher-index', [
            'teachers' => $query->paginate(10),
        ]);
    }
}
