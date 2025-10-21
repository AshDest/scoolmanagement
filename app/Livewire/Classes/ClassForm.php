<?php
// Composant Livewire: formulaire création/édition d'une classe (nom, niveau, meta JSON simple).
namespace App\Livewire\Classes;

use App\Models\SchoolClass;
use Livewire\Attributes\On;
use Livewire\Component;

class ClassForm extends Component
{
    public ?int $id = null;
    public string $name = '';
    public ?string $level = null;
    public array $meta = [];

    #[On('open-class-form')]
    public function load(?int $id): void
    {
        $this->resetValidation();
        if ($id) {
            $c = SchoolClass::findOrFail($id);
            $this->id = $c->id;
            $this->name = $c->name;
            $this->level = $c->level;
            $this->meta = $c->meta ?? [];
        } else {
            $this->reset(['id','name','level','meta']);
            $this->name = '';
            $this->level = null;
            $this->meta = [];
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required','string','max:255'],
            'level' => ['nullable','string','max:255'],
        ]);
        $data['meta'] = $this->meta ?: null;

        SchoolClass::updateOrCreate(['id' => $this->id], $data);

        session()->flash('status', 'Classe enregistrée.');
        $this->dispatch('class-saved');
        $this->dispatch('close-class-modal');
    }

    public function render()
    {
        return view('livewire.classes.class-form');
    }
}
