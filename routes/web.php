<?php
// Routes web: pages Livewire protégées par auth et liens vers modules.
use App\Livewire\Courses\CourseIndex;
use App\Livewire\Grades\GradeIndex;
use App\Livewire\Results\StudentResults;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Students\StudentIndex;
use App\Livewire\Students\StudentProfile;
use App\Livewire\Teachers\TeacherIndex;
use App\Livewire\Dashboards\AdminDashboard;
use App\Livewire\Dashboards\TeacherDashboard;
use App\Livewire\Dashboards\StudentDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect()->route('students.index'); });


Route::get('/home', function () {
    $u = auth()->user();
    if ($u->hasRole('admin')) return redirect()->route('dashboard.admin');
    if ($u->hasRole('teacher')) return redirect()->route('dashboard.teacher');
    if ($u->hasRole('student')) return redirect()->route('dashboard.student');
    return redirect()->route('students.index');
})->middleware('auth')->name('home');

Route::middleware(['auth','ensure.role:admin'])->group(function () {
    Route::get('/students', StudentIndex::class)->name('students.index');
    Route::get('/students/{studentId}', StudentProfile::class)->name('students.profile');

    Route::get('/courses', CourseIndex::class)->name('courses.index');

    Route::get('/teachers', TeacherIndex::class)->name('teachers.index');

    Route::get('/grades', GradeIndex::class)->name('grades.index');

    Route::get('/roles', RoleIndex::class)->name('roles.index');

    Route::get('/results/me', StudentResults::class)->name('results.me');

    Route::get('/dashboard/admin', AdminDashboard::class)->name('dashboard.admin');
    Route::get('/dashboard/teacher', TeacherDashboard::class)->name('dashboard.teacher');
    Route::get('/dashboard/student', StudentDashboard::class)->name('dashboard.student');
});
