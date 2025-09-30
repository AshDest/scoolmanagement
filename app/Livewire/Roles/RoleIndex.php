<?php
// Composant Livewire: gestion simple des rôles Spatie et assignation aux utilisateurs.
namespace App\Http\Livewire\Roles;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class RoleIndex extends Component
{
    public string $email = '';
    public string $role = '';

    public function assign(): void
    {
        $this->validate([
            'email' => ['required','email','exists:users,email'],
            'role' => ['required','string','exists:roles,name'],
        ]);
        $user = User::where('email', $this->email)->firstOrFail();
        $user->syncRoles([$this->role]);
        session()->flash('status', "Rôle '{$this->role}' assigné à {$user->email}.");
        $this->reset(['email','role']);
    }

    public function render()
    {
        return view('livewire.roles.role-index', [
            'roles' => Role::orderBy('name')->get(),
            'users' => User::orderBy('email')->limit(20)->get(),
        ]);
    }
}
