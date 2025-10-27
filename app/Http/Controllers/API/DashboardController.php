<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Course;
use App\Models\User;
use App\Models\Grade;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard selon le rôle de l'utilisateur
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('teacher')) {
            return $this->teacherDashboard($user);
        } elseif ($user->hasRole('student')) {
            return $this->studentDashboard($user);
        }

        return response()->json([
            'success' => false,
            'message' => 'Rôle non reconnu'
        ], 403);
    }

    /**
     * Dashboard Admin
     */
    private function adminDashboard()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_courses' => Course::count(),
            'total_teachers' => User::role('teacher')->count(),
            'total_enrollments' => Enrollment::count(),
            'total_grades' => Grade::count(),
        ];

        // Statistiques récentes
        $recentStudents = Student::with('user')->latest()->take(5)->get();
        $recentEnrollments = Enrollment::with(['student', 'courseOffering.course'])
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'recent_students' => $recentStudents->map(function($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->first_name . ' ' . $student->last_name,
                        'email' => $student->user->email,
                        'registration_number' => $student->registration_number,
                        'created_at' => $student->created_at,
                    ];
                }),
                'recent_enrollments' => $recentEnrollments->map(function($enrollment) {
                    return [
                        'student' => $enrollment->student->first_name . ' ' . $enrollment->student->last_name,
                        'course' => $enrollment->courseOffering->course->title,
                        'status' => $enrollment->status,
                        'created_at' => $enrollment->created_at,
                    ];
                }),
            ]
        ], 200);
    }

    /**
     * Dashboard Teacher
     */
    private function teacherDashboard($teacher)
    {
        // Récupérer les cours de l'enseignant
        $courses = \DB::table('courses')
            ->join('course_teacher', 'courses.id', '=', 'course_teacher.course_id')
            ->where('course_teacher.teacher_id', $teacher->id)
            ->select('courses.*')
            ->get();

        $courseIds = $courses->pluck('id');

        // Nombre d'étudiants total
        $totalStudents = \DB::table('students')
            ->join('enrollments', 'students.id', '=', 'enrollments.student_id')
            ->join('course_offerings', 'enrollments.course_offering_id', '=', 'course_offerings.id')
            ->whereIn('course_offerings.course_id', $courseIds)
            ->distinct('students.id')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_courses' => $courses->count(),
                    'total_students' => $totalStudents,
                ],
                'courses' => $courses,
            ]
        ], 200);
    }

    /**
     * Dashboard Student
     */
    private function studentDashboard($user)
    {
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Profil étudiant non trouvé'
            ], 404);
        }

        $student->load(['enrollments.courseOffering.course', 'enrollments.grade', 'class']);

        // Calculer la moyenne générale
        $gradesWithScores = $student->enrollments->filter(fn($e) => $e->grade !== null);
        $totalCredits = $gradesWithScores->sum(fn($e) => $e->courseOffering->course->credits);
        $totalPoints = $gradesWithScores->sum(function($e) {
            return $e->courseOffering->course->credits * $e->grade->score;
        });
        $gpa = $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'registration_number' => $student->registration_number,
                    'class' => $student->class ? [
                        'name' => $student->class->name,
                        'code' => $student->class->code,
                    ] : null,
                ],
                'stats' => [
                    'total_enrollments' => $student->enrollments->count(),
                    'completed_courses' => $student->enrollments->where('status', 'completed')->count(),
                    'current_courses' => $student->enrollments->where('status', 'enrolled')->count(),
                    'gpa' => $gpa,
                ],
                'recent_grades' => $gradesWithScores->take(5)->map(function($enrollment) {
                    return [
                        'course' => $enrollment->courseOffering->course->title,
                        'score' => $enrollment->grade->score,
                        'letter' => $enrollment->grade->letter,
                    ];
                }),
            ]
        ], 200);
    }

    /**
     * Statistiques détaillées
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        // Statistiques par mois
        $enrollmentsByMonth = Enrollment::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();

        // Distribution des notes
        $gradeDistribution = Grade::selectRaw('letter, COUNT(*) as count')
            ->groupBy('letter')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'enrollments_by_month' => $enrollmentsByMonth,
                'grade_distribution' => $gradeDistribution,
            ]
        ], 200);
    }
}

