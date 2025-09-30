<?php
// Routes web: pages Livewire protégées par auth et liens vers modules.
use App\Livewire\Courses\CourseIndex;
use App\Livewire\Grades\GradeIndex;
use App\Livewire\Results\StudentResults;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Students\StudentIndex;
use App\Livewire\Students\StudentProfile;
use App\Livewire\Teachers\TeacherIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect()->route('students.index'); });

Auth::routes(); // laravel/ui bootstrap --auth

Route::middleware(['auth'])->group(function () {
    Route::get('/students', StudentIndex::class)->name('students.index');
    Route::get('/students/{studentId}', StudentProfile::class)->name('students.profile');

    Route::get('/courses', CourseIndex::class)->name('courses.index');

    Route::get('/teachers', TeacherIndex::class)->name('teachers.index');

    Route::get('/grades', GradeIndex::class)->name('grades.index');

    Route::get('/roles', RoleIndex::class)->name('roles.index');

    Route::get('/results/me', StudentResults::class)->name('results.me');
});
