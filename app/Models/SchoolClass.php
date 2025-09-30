<?php
// Modèle: représente une classe (groupe d'élèves) avec meta JSON.
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';
    protected $fillable = ['name','level','meta'];
    protected $casts = ['meta' => 'array'];

    public function students(): HasMany { return $this->hasMany(Student::class, 'class_id'); }
}
