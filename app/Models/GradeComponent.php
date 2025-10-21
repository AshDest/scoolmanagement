<?php
// Modèle: valeurs des composantes (CW/QZ/EX) pour une inscription.
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeComponent extends Model
{
    use HasFactory;

    protected $fillable = ['enrollment_id','cw','qz','ex','meta'];
    protected $casts = ['cw' => 'decimal:2','qz' => 'decimal:2','ex' => 'decimal:2','meta' => 'array'];

    public function enrollment(): BelongsTo { return $this->belongsTo(Enrollment::class); }
}
