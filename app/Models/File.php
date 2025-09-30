<?php
// Modèle: fichier uploadé générique lié à n'importe quel modèle (morph).
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['fileable_type','fileable_id','disk','path','original_name','size','mime_type','meta'];
    protected $casts = ['meta' => 'array'];

    public function fileable(): MorphTo { return $this->morphTo(); }
}
