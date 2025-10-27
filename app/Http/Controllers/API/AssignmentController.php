<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Student;
use App\Models\CourseOffering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    /**
     * Liste de tous les devoirs
     */
    public function index(Request $request)
    {
        $query = Assignment::with('courseOffering.course');

        if ($request->has('course_offering_id')) {
            $query->where('course_offering_id', $request->course_offering_id);
        }

        $perPage = $request->get('per_page', 15);
        $assignments = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $assignments->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'description' => $assignment->description,
                    'due_date' => $assignment->due_date,
                    'max_score' => $assignment->max_score,
                    'course' => [
                        'code' => $assignment->courseOffering->course->code,
                        'title' => $assignment->courseOffering->course->title,
                    ],
                ];
            }),
            'meta' => [
                'current_page' => $assignments->currentPage(),
                'last_page' => $assignments->lastPage(),
                'per_page' => $assignments->perPage(),
                'total' => $assignments->total(),
            ]
        ], 200);
    }

    /**
     * Créer un nouveau devoir
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_offering_id' => 'required|exists:course_offerings,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'max_score' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $assignment = Assignment::create($request->only([
            'course_offering_id', 'title', 'description', 'due_date', 'max_score', 'instructions'
        ]));
        $assignment->load('courseOffering.course');

        return response()->json([
            'success' => true,
            'message' => 'Devoir créé avec succès',
            'data' => $assignment
        ], 201);
    }

    /**
     * Afficher un devoir
     */
    public function show($id)
    {
        $assignment = Assignment::with('courseOffering.course')->find($id);

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Devoir non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $assignment
        ], 200);
    }

    /**
     * Mettre à jour un devoir
     */
    public function update(Request $request, $id)
    {
        $assignment = Assignment::find($id);

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Devoir non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|date',
            'max_score' => 'sometimes|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $assignment->update($request->only([
            'title', 'description', 'due_date', 'max_score', 'instructions'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Devoir mis à jour avec succès',
            'data' => $assignment
        ], 200);
    }

    /**
     * Supprimer un devoir
     */
    public function destroy($id)
    {
        $assignment = Assignment::find($id);

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Devoir non trouvé'
            ], 404);
        }

        $assignment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Devoir supprimé avec succès'
        ], 200);
    }

    /**
     * Devoirs d'un cours
     */
    public function byCourse($courseId)
    {
        $assignments = Assignment::whereHas('courseOffering', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->with('courseOffering')->get();

        return response()->json([
            'success' => true,
            'data' => $assignments
        ], 200);
    }

    /**
     * Devoirs d'un étudiant
     */
    public function byStudent($studentId)
    {
        $student = Student::with('enrollments.courseOffering')->find($studentId);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], 404);
        }

        $offeringIds = $student->enrollments->pluck('course_offering_id');
        $assignments = Assignment::whereIn('course_offering_id', $offeringIds)
            ->with('courseOffering.course')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $assignments->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'description' => $assignment->description,
                    'due_date' => $assignment->due_date,
                    'max_score' => $assignment->max_score,
                    'course' => [
                        'code' => $assignment->courseOffering->course->code,
                        'title' => $assignment->courseOffering->course->title,
                    ],
                ];
            })
        ], 200);
    }

    /**
     * Soumettre un devoir (pour les étudiants)
     */
    public function submit(Request $request, $id)
    {
        $assignment = Assignment::find($id);

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Devoir non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'submission_content' => 'required|string',
            'files' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // TODO: Implémenter la logique de soumission de devoir
        // Créer une table 'assignment_submissions' si nécessaire

        return response()->json([
            'success' => true,
            'message' => 'Devoir soumis avec succès'
        ], 200);
    }
}
