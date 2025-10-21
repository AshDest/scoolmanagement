<?php
// Modèle: schéma de pondération des notes pour un cours (CW/QZ/EX).
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeScheme extends Model
{
    use HasFactory;

    protected $fillable = ['course_id','cw_weight','qz_weight','ex_weight','meta'];
    protected $casts = ['meta' => 'array'];

    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
}
