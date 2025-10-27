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
    Route::apiResource('students', StudentController::class);
    Route::get('students/{student}/enrollments', [StudentController::class, 'enrollments']);
    Route::get('students/{student}/grades', [StudentController::class, 'grades']);
    Route::get('students/{student}/attendance', [StudentController::class, 'attendance']);
    Route::get('students/{student}/results', [StudentController::class, 'results']);

    // Courses - Gestion des cours
    Route::apiResource('courses', CourseController::class);
    Route::get('courses/{course}/offerings', [CourseController::class, 'offerings']);
    Route::get('courses/{course}/students', [CourseController::class, 'students']);
    Route::get('courses/{course}/teachers', [CourseController::class, 'teachers']);

    // Course Offerings - Sessions de cours
    Route::apiResource('course-offerings', CourseOfferingController::class);
    Route::get('course-offerings/{offering}/enrollments', [CourseOfferingController::class, 'enrollments']);
    Route::get('course-offerings/{offering}/grades', [CourseOfferingController::class, 'grades']);

    // Grades - Gestion des notes
    Route::apiResource('grades', GradeController::class);
    Route::post('grades/bulk', [GradeController::class, 'bulkStore']);
    Route::get('grades/student/{student}', [GradeController::class, 'byStudent']);
    Route::get('grades/course/{course}', [GradeController::class, 'byCourse']);

    // Enrollments - Inscriptions
    Route::apiResource('enrollments', EnrollmentController::class);
    Route::post('enrollments/bulk', [EnrollmentController::class, 'bulkEnroll']);
    Route::put('enrollments/{enrollment}/status', [EnrollmentController::class, 'updateStatus']);

    // Teachers - Enseignants
    Route::apiResource('teachers', TeacherController::class);
    Route::get('teachers/{teacher}/courses', [TeacherController::class, 'courses']);
    Route::get('teachers/{teacher}/students', [TeacherController::class, 'students']);

    // Classes - Classes scolaires
    Route::apiResource('classes', ClassController::class);
    Route::get('classes/{class}/students', [ClassController::class, 'students']);
    Route::get('classes/{class}/courses', [ClassController::class, 'courses']);

    // Attendance - Présences
    Route::apiResource('attendances', AttendanceController::class);
    Route::post('attendances/bulk', [AttendanceController::class, 'bulkStore']);
    Route::get('attendances/student/{student}', [AttendanceController::class, 'byStudent']);
    Route::get('attendances/course/{course}', [AttendanceController::class, 'byCourse']);

    // Assignments - Devoirs
    Route::apiResource('assignments', AssignmentController::class);
    Route::get('assignments/course/{course}', [AssignmentController::class, 'byCourse']);
    Route::get('assignments/student/{student}', [AssignmentController::class, 'byStudent']);
    Route::post('assignments/{assignment}/submit', [AssignmentController::class, 'submit']);
});

