<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GradeController extends Controller
{
    /**
     * Liste de toutes les notes
     */
    public function index(Request $request)
    {
        $query = Grade::with(['enrollment.student', 'enrollment.courseOffering.course']);

        if ($request->has('student_id')) {
            $query->whereHas('enrollment', function($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }

        $perPage = $request->get('per_page', 15);
        $grades = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $grades->map(function($grade) {
                return [
                    'id' => $grade->id,
                    'score' => $grade->score,
                    'letter' => $grade->letter,
                    'student' => [
                        'id' => $grade->enrollment->student->id,
                        'name' => $grade->enrollment->student->first_name . ' ' . $grade->enrollment->student->last_name,
                    ],
                    'course' => [
                        'code' => $grade->enrollment->courseOffering->course->code,
                        'title' => $grade->enrollment->courseOffering->course->title,
                    ],
                ];
            }),
            'meta' => [
                'current_page' => $grades->currentPage(),
                'last_page' => $grades->lastPage(),
                'per_page' => $grades->perPage(),
                'total' => $grades->total(),
            ]
        ], 200);
    }

    /**
     * Créer une nouvelle note
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enrollment_id' => 'required|exists:enrollments,id',
            'score' => 'required|numeric|min:0|max:100',
            'letter' => 'nullable|string|max:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $grade = Grade::create($request->only(['enrollment_id', 'score', 'letter', 'meta']));
        $grade->load(['enrollment.student', 'enrollment.courseOffering.course']);

        return response()->json([
            'success' => true,
            'message' => 'Note créée avec succès',
            'data' => $grade
        ], 201);
    }

    /**
     * Afficher une note
     */
    public function show($id)
    {
        $grade = Grade::with(['enrollment.student', 'enrollment.courseOffering.course'])->find($id);

        if (!$grade) {
            return response()->json([
                'success' => false,
                'message' => 'Note non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $grade
        ], 200);
    }

    /**
     * Mettre à jour une note
     */
    public function update(Request $request, $id)
    {
        $grade = Grade::find($id);

        if (!$grade) {
            return response()->json([
                'success' => false,
                'message' => 'Note non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'score' => 'sometimes|numeric|min:0|max:100',
            'letter' => 'nullable|string|max:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $grade->update($request->only(['score', 'letter', 'meta']));

        return response()->json([
            'success' => true,
            'message' => 'Note mise à jour avec succès',
            'data' => $grade
        ], 200);
    }

    /**
     * Supprimer une note
     */
    public function destroy($id)
    {
        $grade = Grade::find($id);

        if (!$grade) {
            return response()->json([
                'success' => false,
                'message' => 'Note non trouvée'
            ], 404);
        }

        $grade->delete();

        return response()->json([
            'success' => true,
            'message' => 'Note supprimée avec succès'
        ], 200);
    }

    /**
     * Création en masse de notes
     */
    public function bulkStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grades' => 'required|array',
            'grades.*.enrollment_id' => 'required|exists:enrollments,id',
            'grades.*.score' => 'required|numeric|min:0|max:100',
            'grades.*.letter' => 'nullable|string|max:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $grades = [];
        foreach ($request->grades as $gradeData) {
            $grades[] = Grade::create($gradeData);
        }

        return response()->json([
            'success' => true,
            'message' => count($grades) . ' notes créées avec succès',
            'data' => $grades
        ], 201);
    }

    /**
     * Notes d'un étudiant spécifique
     */
    public function byStudent($studentId)
    {
        $grades = Grade::whereHas('enrollment', function($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })->with(['enrollment.courseOffering.course'])->get();

        return response()->json([
            'success' => true,
            'data' => $grades->map(function($grade) {
                return [
                    'id' => $grade->id,
                    'score' => $grade->score,
                    'letter' => $grade->letter,
                    'course' => [
                        'code' => $grade->enrollment->courseOffering->course->code,
                        'title' => $grade->enrollment->courseOffering->course->title,
                        'credits' => $grade->enrollment->courseOffering->course->credits,
                    ],
                    'year' => $grade->enrollment->courseOffering->year,
                    'term' => $grade->enrollment->courseOffering->term,
                ];
            })
        ], 200);
    }

    /**
     * Notes d'un cours spécifique
     */
    public function byCourse($courseId)
    {
        $grades = Grade::whereHas('enrollment.courseOffering', function($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->with(['enrollment.student'])->get();

        return response()->json([
            'success' => true,
            'data' => $grades->map(function($grade) {
                return [
                    'id' => $grade->id,
                    'score' => $grade->score,
                    'letter' => $grade->letter,
                    'student' => [
                        'id' => $grade->enrollment->student->id,
                        'name' => $grade->enrollment->student->first_name . ' ' . $grade->enrollment->student->last_name,
                        'registration_number' => $grade->enrollment->student->registration_number,
                    ],
                ];
            })
        ], 200);
    }
}

