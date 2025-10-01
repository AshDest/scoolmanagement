<?php
// Dashboard Student: résumé des inscriptions et dernières notes.
namespace App\Livewire\Dashboards;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StudentDashboard extends Component
{
    public function render()
    {
        $student = Auth::user()?->student?->load([
            'enrollments.courseOffering.course',
            'enrollments.grade',
        ]);

        return view('livewire.dashboards.student-dashboard', compact('student'));
    }
}
