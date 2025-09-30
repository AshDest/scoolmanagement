<?php
// Modèle: profil académique d'un étudiant (lié à user et classe).
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
}
