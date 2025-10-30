<?php
// Composant Livewire: formulaire création/édition d'un étudiant avec validation.
namespace App\Livewire\Students;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class StudentForm extends Component
{
    public ?int $id = null;
    public ?int $user_id = null;
    public ?int $class_id = null;
    public string $first_name = '';
    public string $last_name = '';
    public ?string $dob = null;
    public string $registration_number = '';
    public string $email = '';
    public string $address = '';
    public string $tutor_name = '';
    public string $tutor_phone = '';
    public array $extra = [];

    #[On('open-student-form')]
    public function load(?int $id): void
    {
        $this->resetValidation();
        if ($id) {
            $s = Student::findOrFail($id);
            $this->id = $s->id;
            $this->user_id = $s->user_id;
            $this->class_id = $s->class_id;
            $this->first_name = $s->first_name;
            $this->last_name = $s->last_name;
            $this->dob = optional($s->dob)->format('Y-m-d');
            $this->registration_number = $s->registration_number;
            $this->email = $s->user?->email ?? '';
            $this->address = $s->address ?? '';
            $this->tutor_name = $s->tutor_name ?? '';
            $this->tutor_phone = $s->tutor_phone ?? '';
            $this->extra = $s->extra ?? [];
        } else {
            $this->reset(['id','user_id','class_id','first_name','last_name','dob','registration_number','email','address','tutor_name','tutor_phone','extra']);
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'class_id' => ['nullable','exists:classes,id'],
            'dob' => ['nullable','date'],
            'registration_number' => ['required','string','max:255', Rule::unique('students','registration_number')->ignore($this->id)],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($this->user_id)],
            'address' => ['nullable','string','max:500'],
            'tutor_name' => ['nullable','string','max:255'],
            'tutor_phone' => ['nullable','string','max:20'],
        ]);
        $data['extra'] = $this->extra ?: null;

        // Si c'est une création (pas de $this->id)
        if (!$this->id) {
            // Créer d'abord le compte utilisateur
            $user = User::create([
                'name' => $this->first_name . ' ' . $this->last_name,
                'email' => $this->email,
                'password' => Hash::make('password123'), // Mot de passe par défaut
            ]);

            // Assigner le rôle étudiant
            $user->assignRole('student');

            // Ajouter l'user_id aux données
            $data['user_id'] = $user->id;
        } else {
            // Mise à jour de l'utilisateur existant si nécessaire
            if ($this->user_id) {
                $user = User::find($this->user_id);
                if ($user) {
                    $user->update([
                        'name' => $this->first_name . ' ' . $this->last_name,
                        'email' => $this->email,
                    ]);
                }
            }
        }

        Student::updateOrCreate(['id' => $this->id], $data);

        session()->flash('status', 'Étudiant enregistré.' . (!$this->id ? ' Mot de passe par défaut: password123' : ''));
        $this->dispatch('student-saved');
        $this->dispatch('close-student-modal');
    }

    public function render()
    {
        return view('livewire.students.student-form', [
            'classes' => SchoolClass::orderBy('name')->get(),
        ]);
    }
}
