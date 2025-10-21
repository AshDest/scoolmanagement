<?php
// Modèle: matière enseignée, avec meta JSON et soft deletes + relations classes/teachers.
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code','title','description','credits','meta'];
    protected $casts = ['meta' => 'array'];

    public function offerings(): HasMany { return $this->hasMany(CourseOffering::class); }

    // Nouvelles relations
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'course_class', 'course_id', 'class_id')->withTimestamps();
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_teacher', 'course_id', 'teacher_id')->withTimestamps();
    }

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
