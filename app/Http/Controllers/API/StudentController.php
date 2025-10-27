<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Liste de tous les étudiants
     */
    public function index(Request $request)
    {
        $query = Student::with(['user', 'class']);

        // Filtres
        if ($request->has('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $students = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $students->map(function($student) {
                return [
                    'id' => $student->id,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'registration_number' => $student->registration_number,
                    'dob' => $student->dob,
                    'user' => [
                        'id' => $student->user->id,
                        'name' => $student->user->name,
                        'email' => $student->user->email,
                    ],
                    'class' => $student->class ? [
                        'id' => $student->class->id,
                        'name' => $student->class->name,
                        'code' => $student->class->code,
                    ] : null,
                ];
            }),
            'meta' => [
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total(),
            ]
        ], 200);
    }

    /**
     * Créer un nouvel étudiant
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'registration_number' => 'required|string|unique:students,registration_number',
            'dob' => 'required|date',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('student');

        // Créer l'étudiant
        $student = Student::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'registration_number' => $request->registration_number,
            'dob' => $request->dob,
            'class_id' => $request->class_id,
            'extra' => $request->extra ?? [],
        ]);

        $student->load(['user', 'class']);

        return response()->json([
            'success' => true,
            'message' => 'Étudiant créé avec succès',
            'data' => $student
        ], 201);
    }

    /**
     * Afficher un étudiant
     */
    public function show($id)
    {
        $student = Student::with(['user', 'class', 'enrollments.courseOffering.course'])->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'registration_number' => $student->registration_number,
                'dob' => $student->dob,
                'extra' => $student->extra,
                'user' => [
                    'id' => $student->user->id,
                    'name' => $student->user->name,
                    'email' => $student->user->email,
                ],
                'class' => $student->class ? [
                    'id' => $student->class->id,
                    'name' => $student->class->name,
                    'code' => $student->class->code,
                ] : null,
                'enrollments_count' => $student->enrollments->count(),
            ]
        ], 200);
    }

    /**
     * Mettre à jour un étudiant
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'registration_number' => 'sometimes|string|unique:students,registration_number,' . $id,
            'dob' => 'sometimes|date',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $student->update($request->only([
            'first_name', 'last_name', 'registration_number', 'dob', 'class_id', 'extra'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Étudiant mis à jour avec succès',
            'data' => $student->load(['user', 'class'])
        ], 200);
    }

    /**
     * Supprimer un étudiant
     */
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], 404);
        }

        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Étudiant supprimé avec succès'
        ], 200);
    }

    /**
     * Inscriptions d'un étudiant
     */
    public function enrollments($id)
    {
        $student = Student::with(['enrollments.courseOffering.course'])->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student->enrollments->map(function($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'status' => $enrollment->status,
                    'course_offering' => [
                        'id' => $enrollment->courseOffering->id,
                        'year' => $enrollment->courseOffering->year,
                        'term' => $enrollment->courseOffering->term,
                        'course' => [
                            'id' => $enrollment->courseOffering->course->id,
                            'code' => $enrollment->courseOffering->course->code,
                            'title' => $enrollment->courseOffering->course->title,
                            'credits' => $enrollment->courseOffering->course->credits,
                        ]
                    ]
                ];
            })
        ], 200);
    }

    /**
     * Notes d'un étudiant
     */
    public function grades($id)
    {
        $student = Student::with(['enrollments.grade', 'enrollments.courseOffering.course'])->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], 404);
        }

        $grades = $student->enrollments->filter(function($enrollment) {
            return $enrollment->grade !== null;
        })->map(function($enrollment) {
            return [
                'course' => [
                    'code' => $enrollment->courseOffering->course->code,
                    'title' => $enrollment->courseOffering->course->title,
                    'credits' => $enrollment->courseOffering->course->credits,
                ],
                'grade' => [
                    'id' => $enrollment->grade->id,
                    'score' => $enrollment->grade->score,
                    'letter' => $enrollment->grade->letter,
                ],
                'year' => $enrollment->courseOffering->year,
                'term' => $enrollment->courseOffering->term,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $grades
        ], 200);
    }

    /**
     * Présences d'un étudiant
     */
    public function attendance($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], 404);
        }

        // TODO: Implémenter la logique des présences
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Fonctionnalité en cours de développement'
        ], 200);
    }

    /**
     * Résultats scolaires d'un étudiant
     */
    public function results($id)
    {
        $student = Student::with(['enrollments.grade', 'enrollments.courseOffering.course'])->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], 404);
        }

        $results = $student->enrollments->groupBy(function($enrollment) {
            return $enrollment->courseOffering->year . '-' . $enrollment->courseOffering->term;
        })->map(function($enrollments, $key) {
            [$year, $term] = explode('-', $key);

            $courses = $enrollments->map(function($enrollment) {
                return [
                    'course' => [
                        'code' => $enrollment->courseOffering->course->code,
                        'title' => $enrollment->courseOffering->course->title,
                        'credits' => $enrollment->courseOffering->course->credits,
                    ],
                    'grade' => $enrollment->grade ? [
                        'score' => $enrollment->grade->score,
                        'letter' => $enrollment->grade->letter,
                    ] : null,
                ];
            });

            $totalCredits = $enrollments->sum(fn($e) => $e->courseOffering->course->credits);
            $totalPoints = $enrollments->filter(fn($e) => $e->grade)->sum(function($e) {
                return $e->courseOffering->course->credits * $e->grade->score;
            });
            $gpa = $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0;

            return [
                'year' => $year,
                'term' => $term,
                'courses' => $courses,
                'total_credits' => $totalCredits,
                'gpa' => $gpa,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $results->values()
        ], 200);
    }
}

