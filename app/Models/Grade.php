<?php
// Modèle: note/évaluation rattachée à une inscription.
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['enrollment_id','score','letter','meta'];
    protected $casts = ['score' => 'decimal:2', 'meta' => 'array'];

    public function enrollment(): BelongsTo { return $this->belongsTo(Enrollment::class); }
}
