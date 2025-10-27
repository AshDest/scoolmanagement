<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Liste de tous les enseignants
     */
    public function index(Request $request)
    {
        $query = User::role('teacher')->with('roles');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $teachers = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $teachers->map(function($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'email' => $teacher->email,
                    'profile' => $teacher->profile,
                ];
            }),
            'meta' => [
                'current_page' => $teachers->currentPage(),
                'last_page' => $teachers->lastPage(),
                'per_page' => $teachers->perPage(),
                'total' => $teachers->total(),
            ]
        ], 200);
    }

    /**
     * Afficher un enseignant
     */
    public function show($id)
    {
        $teacher = User::role('teacher')->find($id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Enseignant non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'profile' => $teacher->profile,
            ]
        ], 200);
    }

    /**
     * Cours d'un enseignant
     */
    public function courses($id)
    {
        $teacher = User::role('teacher')->find($id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Enseignant non trouvé'
            ], 404);
        }

        $courses = \DB::table('courses')
            ->join('course_teacher', 'courses.id', '=', 'course_teacher.course_id')
            ->where('course_teacher.teacher_id', $id)
            ->select('courses.*')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $courses
        ], 200);
    }

    /**
     * Étudiants d'un enseignant
     */
    public function students($id)
    {
        $teacher = User::role('teacher')->find($id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Enseignant non trouvé'
            ], 404);
        }

        // Récupérer tous les étudiants inscrits aux cours de cet enseignant
        $students = \DB::table('students')
            ->join('enrollments', 'students.id', '=', 'enrollments.student_id')
            ->join('course_offerings', 'enrollments.course_offering_id', '=', 'course_offerings.id')
            ->join('courses', 'course_offerings.course_id', '=', 'courses.id')
            ->join('course_teacher', 'courses.id', '=', 'course_teacher.course_id')
            ->where('course_teacher.teacher_id', $id)
            ->select('students.*')
            ->distinct()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ], 200);
    }
}

