<?php
// Modèle: profil académique d'un étudiant (lié à user et classe), auto-inscription aux sessions de sa classe.
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id','class_id','first_name','last_name','dob','registration_number','extra'
    ];
    protected $casts = [
        'dob' => 'date',
        'extra' => 'array',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function class(): BelongsTo { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function enrollments(): HasMany { return $this->hasMany(Enrollment::class); }

    protected static function booted(): void
    {
        static::saved(function (Student $student) {
            if (!$student->class_id) return;

            // Récupère tous les cours liés à la classe de l'étudiant
            $courseIds = $student->class?->courses()->pluck('courses.id');
            if ($courseIds?->isEmpty()) return;

            // Récupère toutes les sessions de ces cours
            $offeringIds = CourseOffering::whereIn('course_id', $courseIds)->pluck('id');
            if ($offeringIds->isEmpty()) return;

            // Crée les inscriptions manquantes
            foreach ($offeringIds as $oid) {
                Enrollment::firstOrCreate(
                    ['student_id' => $student->id, 'course_offering_id' => $oid],
                    ['status' => 'enrolled', 'meta' => null]
                );
            }
        });
    }
}

// Imports requis
//use App\Models\CourseOffering;
//use App\Models\Enrollment;
