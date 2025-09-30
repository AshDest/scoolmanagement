<?php
// Composant Livewire: formulaire création/édition d'un enseignant (user + rôle).
namespace App\Http\Livewire\Teachers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class TeacherForm extends Component
{
    public ?int $id = null;
    public string $name = '';
    public string $email = '';
    public ?string $password = null;

    #[On('open-teacher-form')]
    public function load(?int $id): void
    {
        $this->resetValidation();
        if ($id) {
            $u = User::findOrFail($id);
            $this->id = $u->id;
            $this->name = $u->name;
            $this->email = $u->email;
            $this->password = null;
        } else {
            $this->reset(['id','name','email','password']);
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($this->id)],
            'password' => [$this->id ? 'nullable' : 'required','string','min:6'],
        ]);

        if ($this->id) {
            $user = User::findOrFail($this->id);
            $user->fill(['name' => $data['name'], 'email' => $data['email']]);
            if (!empty($data['password'])) $user->password = Hash::make($data['password']);
            $user->save();
        } else {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'profile' => ['role' => 'teacher'],
            ]);
            $user->assignRole('teacher');
        }

        session()->flash('status', 'Enseignant enregistré.');
        $this->dispatch('teacher-saved');
        $this->dispatch('close-teacher-modal');
    }

    public function render() { return view('livewire.teachers.teacher-form'); }
}
