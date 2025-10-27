<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    /**
     * Liste de toutes les classes
     */
    public function index(Request $request)
    {
        $query = SchoolClass::withCount('students');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $classes = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $classes->map(function ($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'code' => $class->code,
                    'level' => $class->level,
                    'students_count' => $class->students_count,
                ];
            }),
            'meta' => [
                'current_page' => $classes->currentPage(),
                'last_page' => $classes->lastPage(),
                'per_page' => $classes->perPage(),
                'total' => $classes->total(),
            ]
        ], 200);
    }

    /**
     * Créer une nouvelle classe
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:classes,code',
            'level' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $class = SchoolClass::create($request->only(['name', 'code', 'level', 'meta']));

        return response()->json([
            'success' => true,
            'message' => 'Classe créée avec succès',
            'data' => $class
        ], 201);
    }

    /**
     * Afficher une classe
     */
    public function show($id)
    {
        $class = SchoolClass::withCount('students')->find($id);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Classe non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $class
        ], 200);
    }

    /**
     * Mettre à jour une classe
     */
    public function update(Request $request, $id)
    {
        $class = SchoolClass::find($id);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Classe non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|unique:classes,code,' . $id,
            'level' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $class->update($request->only(['name', 'code', 'level', 'meta']));

        return response()->json([
            'success' => true,
            'message' => 'Classe mise à jour avec succès',
            'data' => $class
        ], 200);
    }

    /**
     * Supprimer une classe
     */
    public function destroy($id)
    {
        $class = SchoolClass::find($id);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Classe non trouvée'
            ], 404);
        }

        $class->delete();

        return response()->json([
            'success' => true,
            'message' => 'Classe supprimée avec succès'
        ], 200);
    }

    /**
     * Étudiants d'une classe
     */
    public function students($id)
    {
        $class = SchoolClass::with('students.user')->find($id);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Classe non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $class->students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'registration_number' => $student->registration_number,
                    'email' => $student->user->email,
                ];
            })
        ], 200);
    }

    /**
     * Cours d'une classe
     */
    public function courses($id)
    {
        $class = SchoolClass::with('courses')->find($id);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Classe non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $class->courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'code' => $course->code,
                    'title' => $course->title,
                    'credits' => $course->credits,
                ];
            })
        ], 200);
    }
}
