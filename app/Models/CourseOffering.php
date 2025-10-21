<?php
// Modèle: ouverture de cours (session). À la création, inscrit les étudiants des classes liées au cours.
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseOffering extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['course_id','term','room','schedule'];
    protected $casts = ['schedule' => 'array'];

    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function enrollments(): HasMany { return $this->hasMany(Enrollment::class); }

    protected static function booted(): void
    {
        static::created(function (CourseOffering $offering) {
            $course = $offering->course()->with('classes.students')->first();
            if (!$course) return;

            // Pour chacune des classes liées au cours, inscrire leurs étudiants à cette nouvelle session
            foreach ($course->classes as $class) {
                foreach ($class->students as $student) {
                    Enrollment::firstOrCreate(
                        ['student_id' => $student->id, 'course_offering_id' => $offering->id],
                        ['status' => 'enrolled', 'meta' => null]
                    );
                }
            }
        });
    }
}

// Imports requis
//use App\Models\Course;
//use App\Models\Enrollment;
