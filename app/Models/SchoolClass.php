<?php
// Modèle: classe scolaire, ajoute utilitaire pour inscrire ses étudiants aux sessions d'une liste de cours.
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';
    protected $fillable = ['name','level','meta'];
    protected $casts = ['meta' => 'array'];

    public function students(): HasMany { return $this->hasMany(Student::class, 'class_id'); }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_class', 'class_id', 'course_id')->withTimestamps();
    }

    // Inscrit tous les étudiants de cette classe aux sessions (offres) des cours donnés
    public function enrollAllStudentsToCourseIds(array $courseIds): void
    {
        if (empty($courseIds)) return;

        $offeringIds = CourseOffering::whereIn('course_id', $courseIds)->pluck('id');
        if ($offeringIds->isEmpty()) return;

        $studentIds = $this->students()->pluck('id');
        foreach ($studentIds as $sid) {
            foreach ($offeringIds as $oid) {
                Enrollment::firstOrCreate(
                    ['student_id' => $sid, 'course_offering_id' => $oid],
                    ['status' => 'enrolled', 'meta' => null]
                );
            }
        }
    }
}

// Imports requis
//use App\Models\Course;
//use App\Models\CourseOffering;
//use App\Models\Enrollment;
