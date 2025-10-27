<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Liste de tous les cours
     */
    public function index(Request $request)
    {
        $query = Course::with(['classes', 'teachers']);

        // Filtres
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $courses = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $courses->map(function($course) {
                return [
                    'id' => $course->id,
                    'code' => $course->code,
                    'title' => $course->title,
                    'description' => $course->description,
                    'credits' => $course->credits,
                    'classes_count' => $course->classes->count(),
                    'teachers_count' => $course->teachers->count(),
                ];
            }),
            'meta' => [
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'per_page' => $courses->perPage(),
                'total' => $courses->total(),
            ]
        ], 200);
    }

    /**
     * Créer un nouveau cours
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:courses,code',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::create($request->only(['code', 'title', 'description', 'credits', 'meta']));

        return response()->json([
            'success' => true,
            'message' => 'Cours créé avec succès',
            'data' => $course
        ], 201);
    }

    /**
     * Afficher un cours
     */
    public function show($id)
    {
        $course = Course::with(['classes', 'teachers', 'offerings'])->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Cours non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $course->id,
                'code' => $course->code,
                'title' => $course->title,
                'description' => $course->description,
                'credits' => $course->credits,
                'meta' => $course->meta,
                'classes' => $course->classes->map(function($class) {
                    return [
                        'id' => $class->id,
                        'name' => $class->name,
                        'code' => $class->code,
                    ];
                }),
                'teachers' => $course->teachers->map(function($teacher) {
                    return [
                        'id' => $teacher->id,
                        'name' => $teacher->name,
                        'email' => $teacher->email,
                    ];
                }),
                'offerings_count' => $course->offerings->count(),
            ]
        ], 200);
    }

    /**
     * Mettre à jour un cours
     */
    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Cours non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|string|unique:courses,code,' . $id,
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'sometimes|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $course->update($request->only(['code', 'title', 'description', 'credits', 'meta']));

        return response()->json([
            'success' => true,
            'message' => 'Cours mis à jour avec succès',
            'data' => $course
        ], 200);
    }

    /**
     * Supprimer un cours
     */
    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Cours non trouvé'
            ], 404);
        }

        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cours supprimé avec succès'
        ], 200);
    }

    /**
     * Sessions d'un cours
     */
    public function offerings($id)
    {
        $course = Course::with('offerings')->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Cours non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $course->offerings->map(function($offering) {
                return [
                    'id' => $offering->id,
                    'year' => $offering->year,
                    'term' => $offering->term,
                    'schedule' => $offering->schedule,
                ];
            })
        ], 200);
    }

    /**
     * Étudiants inscrits au cours
     */
    public function students($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Cours non trouvé'
            ], 404);
        }

        // Récupérer les étudiants via les offerings et enrollments
        $students = \DB::table('students')
            ->join('enrollments', 'students.id', '=', 'enrollments.student_id')
            ->join('course_offerings', 'enrollments.course_offering_id', '=', 'course_offerings.id')
            ->where('course_offerings.course_id', $id)
            ->select('students.*')
            ->distinct()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ], 200);
    }

    /**
     * Enseignants d'un cours
     */
    public function teachers($id)
    {
        $course = Course::with('teachers')->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Cours non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $course->teachers->map(function($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'email' => $teacher->email,
                ];
            })
        ], 200);
    }
}

