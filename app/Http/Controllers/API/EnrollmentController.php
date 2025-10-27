<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\CourseOffering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    /**
     * Liste de toutes les inscriptions
     */
    public function index(Request $request)
    {
        $query = Enrollment::with(['student', 'courseOffering.course']);

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 15);
        $enrollments = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $enrollments->map(function($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'status' => $enrollment->status,
                    'student' => [
                        'id' => $enrollment->student->id,
                        'name' => $enrollment->student->first_name . ' ' . $enrollment->student->last_name,
                    ],
                    'course_offering' => [
                        'id' => $enrollment->courseOffering->id,
                        'year' => $enrollment->courseOffering->year,
                        'term' => $enrollment->courseOffering->term,
                        'course' => [
                            'code' => $enrollment->courseOffering->course->code,
                            'title' => $enrollment->courseOffering->course->title,
                        ]
                    ]
                ];
            }),
            'meta' => [
                'current_page' => $enrollments->currentPage(),
                'last_page' => $enrollments->lastPage(),
                'per_page' => $enrollments->perPage(),
                'total' => $enrollments->total(),
            ]
        ], 200);
    }

    /**
     * Créer une nouvelle inscription
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'course_offering_id' => 'required|exists:course_offerings,id',
            'status' => 'nullable|string|in:enrolled,completed,dropped,failed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier si l'inscription existe déjà
        $exists = Enrollment::where('student_id', $request->student_id)
            ->where('course_offering_id', $request->course_offering_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'L\'étudiant est déjà inscrit à cette session'
            ], 422);
        }

        $enrollment = Enrollment::create([
            'student_id' => $request->student_id,
            'course_offering_id' => $request->course_offering_id,
            'status' => $request->status ?? 'enrolled',
            'meta' => $request->meta ?? null,
        ]);

        $enrollment->load(['student', 'courseOffering.course']);

        return response()->json([
            'success' => true,
            'message' => 'Inscription créée avec succès',
            'data' => $enrollment
        ], 201);
    }

    /**
     * Afficher une inscription
     */
    public function show($id)
    {
        $enrollment = Enrollment::with(['student', 'courseOffering.course', 'grade'])->find($id);

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Inscription non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $enrollment
        ], 200);
    }

    /**
     * Mettre à jour une inscription
     */
    public function update(Request $request, $id)
    {
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Inscription non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|string|in:enrolled,completed,dropped,failed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $enrollment->update($request->only(['status', 'meta']));

        return response()->json([
            'success' => true,
            'message' => 'Inscription mise à jour avec succès',
            'data' => $enrollment
        ], 200);
    }

    /**
     * Supprimer une inscription
     */
    public function destroy($id)
    {
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Inscription non trouvée'
            ], 404);
        }

        $enrollment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inscription supprimée avec succès'
        ], 200);
    }

    /**
     * Inscription en masse
     */
    public function bulkEnroll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'course_offering_id' => 'required|exists:course_offerings,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $enrollments = [];
        foreach ($request->student_ids as $studentId) {
            $enrollment = Enrollment::firstOrCreate(
                [
                    'student_id' => $studentId,
                    'course_offering_id' => $request->course_offering_id,
                ],
                [
                    'status' => 'enrolled',
                    'meta' => null,
                ]
            );
            $enrollments[] = $enrollment;
        }

        return response()->json([
            'success' => true,
            'message' => count($enrollments) . ' inscriptions créées',
            'data' => $enrollments
        ], 201);
    }

    /**
     * Mettre à jour le statut d'une inscription
     */
    public function updateStatus(Request $request, $id)
    {
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Inscription non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:enrolled,completed,dropped,failed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $enrollment->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès',
            'data' => $enrollment
        ], 200);
    }
}

