<?php
// Composant Livewire: liste/recherche/pagination des utilisateurs avec suppression.
namespace App\Livewire\Users;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
#[Layout('layouts.app')]
class UserIndex extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';
    public string $search = '';
    public ?string $roleFilter = null;
    #[On('user-saved')]
    public function refreshList(): void { $this->resetPage(); }
    public function delete(int $id): void
    {
        $user = User::findOrFail($id);
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            return;
        }
        $user->delete();
        session()->flash('status', 'Utilisateur supprimé avec succès.');
        $this->resetPage();
    }
    public function render()
    {
        $query = User::query()
            ->with('roles')
            ->when($this->search !== '', function ($q) {
                $s = '%'.$this->search.'%';
                $q->where(function($w) use ($s) {
                    $w->where('name','like',$s)
                        ->orWhere('email','like',$s);
                });
            })
            ->when($this->roleFilter, fn($q) => $q->role($this->roleFilter))
            ->orderBy('name');
        return view('livewire.users.user-index', [
            'users' => $query->paginate(10),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }
}
