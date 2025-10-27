<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseOfferingController extends Controller
{
    /**
     * Liste de toutes les sessions de cours
     */
    public function index(Request $request)
    {
        $query = CourseOffering::with('course');

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        if ($request->has('term')) {
            $query->where('term', $request->term);
        }

        $perPage = $request->get('per_page', 15);
        $offerings = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $offerings->map(function($offering) {
                return [
                    'id' => $offering->id,
                    'year' => $offering->year,
                    'term' => $offering->term,
                    'schedule' => $offering->schedule,
                    'course' => [
                        'id' => $offering->course->id,
                        'code' => $offering->course->code,
                        'title' => $offering->course->title,
                        'credits' => $offering->course->credits,
                    ],
                ];
            }),
            'meta' => [
                'current_page' => $offerings->currentPage(),
                'last_page' => $offerings->lastPage(),
                'per_page' => $offerings->perPage(),
                'total' => $offerings->total(),
            ]
        ], 200);
    }

    /**
     * Créer une nouvelle session de cours
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'year' => 'required|integer|min:2000',
            'term' => 'required|string',
            'schedule' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $offering = CourseOffering::create($request->only(['course_id', 'year', 'term', 'schedule']));
        $offering->load('course');

        return response()->json([
            'success' => true,
            'message' => 'Session de cours créée avec succès',
            'data' => $offering
        ], 201);
    }

    /**
     * Afficher une session de cours
     */
    public function show($id)
    {
        $offering = CourseOffering::with(['course', 'enrollments.student'])->find($id);

        if (!$offering) {
            return response()->json([
                'success' => false,
                'message' => 'Session de cours non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $offering->id,
                'year' => $offering->year,
                'term' => $offering->term,
                'schedule' => $offering->schedule,
                'course' => $offering->course,
                'enrollments_count' => $offering->enrollments->count(),
            ]
        ], 200);
    }

    /**
     * Mettre à jour une session de cours
     */
    public function update(Request $request, $id)
    {
        $offering = CourseOffering::find($id);

        if (!$offering) {
            return response()->json([
                'success' => false,
                'message' => 'Session de cours non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'year' => 'sometimes|integer|min:2000',
            'term' => 'sometimes|string',
            'schedule' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $offering->update($request->only(['year', 'term', 'schedule']));

        return response()->json([
            'success' => true,
            'message' => 'Session de cours mise à jour avec succès',
            'data' => $offering
        ], 200);
    }

    /**
     * Supprimer une session de cours
     */
    public function destroy($id)
    {
        $offering = CourseOffering::find($id);

        if (!$offering) {
            return response()->json([
                'success' => false,
                'message' => 'Session de cours non trouvée'
            ], 404);
        }

        $offering->delete();

        return response()->json([
            'success' => true,
            'message' => 'Session de cours supprimée avec succès'
        ], 200);
    }

    /**
     * Inscriptions d'une session
     */
    public function enrollments($id)
    {
        $offering = CourseOffering::with(['enrollments.student.user'])->find($id);

        if (!$offering) {
            return response()->json([
                'success' => false,
                'message' => 'Session de cours non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $offering->enrollments->map(function($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'status' => $enrollment->status,
                    'student' => [
                        'id' => $enrollment->student->id,
                        'name' => $enrollment->student->first_name . ' ' . $enrollment->student->last_name,
                        'registration_number' => $enrollment->student->registration_number,
                        'email' => $enrollment->student->user->email,
                    ],
                ];
            })
        ], 200);
    }

    /**
     * Notes d'une session
     */
    public function grades($id)
    {
        $offering = CourseOffering::with(['enrollments.grade', 'enrollments.student'])->find($id);

        if (!$offering) {
            return response()->json([
                'success' => false,
                'message' => 'Session de cours non trouvée'
            ], 404);
        }

        $grades = $offering->enrollments->filter(fn($e) => $e->grade !== null)->map(function($enrollment) {
            return [
                'student' => [
                    'id' => $enrollment->student->id,
                    'name' => $enrollment->student->first_name . ' ' . $enrollment->student->last_name,
                ],
                'grade' => [
                    'score' => $enrollment->grade->score,
                    'letter' => $enrollment->grade->letter,
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $grades
        ], 200);
    }
}

