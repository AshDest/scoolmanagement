<?php
// Dashboard Teacher: focus sur cours enseignés, sessions et saisie des notes.
namespace App\Livewire\Dashboards;

use App\Models\CourseOffering;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TeacherDashboard extends Component
{
    public function render()
    {
        // Hypothèse: l'attribution prof->offering n'est pas encore en base.
        // Affiche simplement les offres récentes pour guider vers la saisie de notes.
        return view('livewire.dashboards.teacher-dashboard', [
            'offerings' => CourseOffering::with('course')->latest()->limit(8)->get(),
        ]);
    }
}
