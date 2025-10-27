<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\CourseOffering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Liste de toutes les présences
     */
    public function index(Request $request)
    {
        $query = Attendance::with(['student', 'courseOffering.course']);

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('course_offering_id')) {
            $query->where('course_offering_id', $request->course_offering_id);
        }

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        $perPage = $request->get('per_page', 15);
        $attendances = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $attendances->map(function($attendance) {
                return [
                    'id' => $attendance->id,
                    'date' => $attendance->date,
                    'status' => $attendance->status,
                    'student' => [
                        'id' => $attendance->student->id,
                        'name' => $attendance->student->first_name . ' ' . $attendance->student->last_name,
                    ],
                    'course' => [
                        'code' => $attendance->courseOffering->course->code,
                        'title' => $attendance->courseOffering->course->title,
                    ],
                ];
            }),
            'meta' => [
                'current_page' => $attendances->currentPage(),
                'last_page' => $attendances->lastPage(),
                'per_page' => $attendances->perPage(),
                'total' => $attendances->total(),
            ]
        ], 200);
    }

    /**
     * Créer une nouvelle présence
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'course_offering_id' => 'required|exists:course_offerings,id',
            'date' => 'required|date',
            'status' => 'required|string|in:present,absent,late,excused',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $attendance = Attendance::create($request->only(['student_id', 'course_offering_id', 'date', 'status', 'notes']));
        $attendance->load(['student', 'courseOffering.course']);

        return response()->json([
            'success' => true,
            'message' => 'Présence enregistrée avec succès',
            'data' => $attendance
        ], 201);
    }

    /**
     * Afficher une présence
     */
    public function show($id)
    {
        $attendance = Attendance::with(['student', 'courseOffering.course'])->find($id);

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Présence non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $attendance
        ], 200);
    }

    /**
     * Mettre à jour une présence
     */
    public function update(Request $request, $id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Présence non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|string|in:present,absent,late,excused',
            'date' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $attendance->update($request->only(['date', 'status', 'notes']));

        return response()->json([
            'success' => true,
            'message' => 'Présence mise à jour avec succès',
            'data' => $attendance
        ], 200);
    }

    /**
     * Supprimer une présence
     */
    public function destroy($id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Présence non trouvée'
            ], 404);
        }

        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Présence supprimée avec succès'
        ], 200);
    }

    /**
     * Enregistrement en masse des présences
     */
    public function bulkStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.course_offering_id' => 'required|exists:course_offerings,id',
            'attendances.*.date' => 'required|date',
            'attendances.*.status' => 'required|string|in:present,absent,late,excused',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $attendances = [];
        foreach ($request->attendances as $data) {
            $attendances[] = Attendance::create($data);
        }

        return response()->json([
            'success' => true,
            'message' => count($attendances) . ' présences enregistrées',
            'data' => $attendances
        ], 201);
    }

    /**
     * Présences d'un étudiant
     */
    public function byStudent($studentId)
    {
        $attendances = Attendance::where('student_id', $studentId)
            ->with('courseOffering.course')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attendances->map(function($attendance) {
                return [
                    'id' => $attendance->id,
                    'date' => $attendance->date,
                    'status' => $attendance->status,
                    'course' => [
                        'code' => $attendance->courseOffering->course->code,
                        'title' => $attendance->courseOffering->course->title,
                    ],
                ];
            })
        ], 200);
    }

    /**
     * Présences d'un cours
     */
    public function byCourse($courseId)
    {
        $attendances = Attendance::whereHas('courseOffering', function($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->with(['student', 'courseOffering'])->get();

        return response()->json([
            'success' => true,
            'data' => $attendances->map(function($attendance) {
                return [
                    'id' => $attendance->id,
                    'date' => $attendance->date,
                    'status' => $attendance->status,
                    'student' => [
                        'id' => $attendance->student->id,
                        'name' => $attendance->student->first_name . ' ' . $attendance->student->last_name,
                    ],
                ];
            })
        ], 200);
    }
}

