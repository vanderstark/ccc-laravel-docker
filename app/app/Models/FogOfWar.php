<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FogOfWar extends Model
{
    protected $table = 'fog_of_war';

    protected $fillable = ['session_id', 'satker', 'layer', 'enabled', 'visibility_scope'];

    protected $casts = [
        'enabled' => 'boolean',
        'visibility_scope' => 'array',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ExerciseSession::class, 'session_id');
    }
}