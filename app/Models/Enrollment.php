<?php
// Modèle: inscription d'un étudiant à une ouverture de cours.
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = ['student_id','course_offering_id','status','meta'];
    protected $casts = ['meta' => 'array'];

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function courseOffering(): BelongsTo { return $this->belongsTo(CourseOffering::class); }
    public function grade(): HasOne { return $this->hasOne(Grade::class); }
}
