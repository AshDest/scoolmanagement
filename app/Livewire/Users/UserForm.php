<?php
// Composant Livewire: formulaire création/édition d'un utilisateur avec gestion des rôles.
namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserForm extends Component
{
    public ?int $id = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public array $selectedRoles = [];
    public bool $changePassword = false;

    #[On('open-user-form')]
    public function load(?int $id): void
    {
        $this->resetValidation();
        $this->changePassword = false;

        if ($id) {
            $user = User::with('roles')->findOrFail($id);
            $this->id = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->password = '';
            $this->password_confirmation = '';
            $this->selectedRoles = $user->roles->pluck('name')->toArray();
        } else {
            $this->reset(['id','name','email','password','password_confirmation','selectedRoles']);
        }
    }

    public function save(): void
    {
        $rules = [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($this->id)],
            'selectedRoles' => ['array'],
        ];

        // Validation du mot de passe seulement si c'est une création ou si on veut le changer
        if (!$this->id || $this->changePassword) {
            $rules['password'] = ['required','string','min:8','confirmed'];
        }

        $data = $this->validate($rules);

        if ($this->id) {
            // Mise à jour
            $user = User::findOrFail($this->id);
            $user->name = $data['name'];
            $user->email = $data['email'];

            if ($this->changePassword && $this->password) {
                $user->password = Hash::make($this->password);
            }

            $user->save();
        } else {
            // Création
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($this->password),
            ]);
        }

        // Synchroniser les rôles
        $user->syncRoles($this->selectedRoles);

        session()->flash('status', 'Utilisateur enregistré avec succès.');
        $this->dispatch('user-saved');
        $this->dispatch('close-user-modal');
    }

    public function render()
    {
        return view('livewire.users.user-form', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }
}

