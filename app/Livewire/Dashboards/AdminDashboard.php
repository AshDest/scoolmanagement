<?php
// Dashboard Admin: tuiles de synthèse et raccourcis d'administration.
namespace App\Livewire\Dashboards;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminDashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboards.admin-dashboard', [
            'metrics' => [
                'users' => User::count(),
                'students' => Student::count(),
                'courses' => Course::count(),
                'enrollments' => Enrollment::count(),
            ],
            'latestStudents' => Student::latest()->limit(5)->get(),
            'latestCourses' => Course::latest()->limit(5)->get(),
        ]);
    }
}
