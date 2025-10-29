<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\GradeController;
use App\Http\Controllers\API\EnrollmentController;
use App\Http\Controllers\API\TeacherController;
use App\Http\Controllers\API\ClassController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AssignmentController;
use App\Http\Controllers\API\CourseOfferingController;

/*
|--------------------------------------------------------------------------
| API Routes - School Management Mobile App
|--------------------------------------------------------------------------
|
| Toutes les routes API pour l'application mobile
| Authentification via Laravel Sanctum
|
*/

// Routes publiques (sans authentification)
Route::prefix('v1')->group(function () {
    // Authentification
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
});

// Routes protégées (avec authentification Sanctum)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Auth - Profil utilisateur
    Route::get('profile', [AuthController::class, 'profile']);
    Route::put('profile', [AuthController::class, 'updateProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    // Dashboard - Statistiques selon le rôle
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);

    // Students - Gestion des étudiants
    Route::apiResource('students', StudentController::class)->names([
        'index' => 'api.students.index',
        'store' => 'api.students.store',
        'show' => 'api.students.show',
        'update' => 'api.students.update',
        'destroy' => 'api.students.destroy',
    ]);
    Route::get('students/{student}/enrollments', [StudentController::class, 'enrollments'])->name('api.students.enrollments');
    Route::get('students/{student}/grades', [StudentController::class, 'grades'])->name('api.students.grades');
    Route::get('students/{student}/attendance', [StudentController::class, 'attendance'])->name('api.students.attendance');
    Route::get('students/{student}/results', [StudentController::class, 'results'])->name('api.students.results');

    // Courses - Gestion des cours
    Route::apiResource('courses', CourseController::class)->names([
        'index' => 'api.courses.index',
        'store' => 'api.courses.store',
        'show' => 'api.courses.show',
        'update' => 'api.courses.update',
        'destroy' => 'api.courses.destroy',
    ]);
    Route::get('courses/{course}/offerings', [CourseController::class, 'offerings'])->name('api.courses.offerings');
    Route::get('courses/{course}/students', [CourseController::class, 'students'])->name('api.courses.students');
    Route::get('courses/{course}/teachers', [CourseController::class, 'teachers'])->name('api.courses.teachers');

    // Course Offerings - Sessions de cours
    Route::apiResource('course-offerings', CourseOfferingController::class)->names([
        'index' => 'api.course-offerings.index',
        'store' => 'api.course-offerings.store',
        'show' => 'api.course-offerings.show',
        'update' => 'api.course-offerings.update',
        'destroy' => 'api.course-offerings.destroy',
    ]);
    Route::get('course-offerings/{offering}/enrollments', [CourseOfferingController::class, 'enrollments'])->name('api.course-offerings.enrollments');
    Route::get('course-offerings/{offering}/grades', [CourseOfferingController::class, 'grades'])->name('api.course-offerings.grades');

    // Grades - Gestion des notes
    Route::apiResource('grades', GradeController::class)->names([
        'index' => 'api.grades.index',
        'store' => 'api.grades.store',
        'show' => 'api.grades.show',
        'update' => 'api.grades.update',
        'destroy' => 'api.grades.destroy',
    ]);
    Route::post('grades/bulk', [GradeController::class, 'bulkStore'])->name('api.grades.bulk');
    Route::get('grades/student/{student}', [GradeController::class, 'byStudent'])->name('api.grades.by-student');
    Route::get('grades/course/{course}', [GradeController::class, 'byCourse'])->name('api.grades.by-course');

    // Enrollments - Inscriptions
    Route::apiResource('enrollments', EnrollmentController::class)->names([
        'index' => 'api.enrollments.index',
        'store' => 'api.enrollments.store',
        'show' => 'api.enrollments.show',
        'update' => 'api.enrollments.update',
        'destroy' => 'api.enrollments.destroy',
    ]);
    Route::post('enrollments/bulk', [EnrollmentController::class, 'bulkEnroll'])->name('api.enrollments.bulk');
    Route::put('enrollments/{enrollment}/status', [EnrollmentController::class, 'updateStatus'])->name('api.enrollments.update-status');

    // Teachers - Enseignants
    Route::apiResource('teachers', TeacherController::class)->names([
        'index' => 'api.teachers.index',
        'store' => 'api.teachers.store',
        'show' => 'api.teachers.show',
        'update' => 'api.teachers.update',
        'destroy' => 'api.teachers.destroy',
    ]);
    Route::get('teachers/{teacher}/courses', [TeacherController::class, 'courses'])->name('api.teachers.courses');
    Route::get('teachers/{teacher}/students', [TeacherController::class, 'students'])->name('api.teachers.students');

    // Classes - Classes scolaires
    Route::apiResource('classes', ClassController::class)->names([
        'index' => 'api.classes.index',
        'store' => 'api.classes.store',
        'show' => 'api.classes.show',
        'update' => 'api.classes.update',
        'destroy' => 'api.classes.destroy',
    ]);
    Route::get('classes/{class}/students', [ClassController::class, 'students'])->name('api.classes.students');
    Route::get('classes/{class}/courses', [ClassController::class, 'courses'])->name('api.classes.courses');

    // Attendance - Présences
    Route::apiResource('attendances', AttendanceController::class)->names([
        'index' => 'api.attendances.index',
        'store' => 'api.attendances.store',
        'show' => 'api.attendances.show',
        'update' => 'api.attendances.update',
        'destroy' => 'api.attendances.destroy',
    ]);
    Route::post('attendances/bulk', [AttendanceController::class, 'bulkStore'])->name('api.attendances.bulk');
    Route::get('attendances/student/{student}', [AttendanceController::class, 'byStudent'])->name('api.attendances.by-student');
    Route::get('attendances/course/{course}', [AttendanceController::class, 'byCourse'])->name('api.attendances.by-course');

    // Assignments - Devoirs
    Route::apiResource('assignments', AssignmentController::class)->names([
        'index' => 'api.assignments.index',
        'store' => 'api.assignments.store',
        'show' => 'api.assignments.show',
        'update' => 'api.assignments.update',
        'destroy' => 'api.assignments.destroy',
    ]);
    Route::get('assignments/course/{course}', [AssignmentController::class, 'byCourse'])->name('api.assignments.by-course');
    Route::get('assignments/student/{student}', [AssignmentController::class, 'byStudent'])->name('api.assignments.by-student');
    Route::post('assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('api.assignments.submit');
});

