<?php
// Modèle: ouverture d'un cours pour une session donnée (term).
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
}
