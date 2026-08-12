<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovementLog extends Model
{
    protected $fillable = [
        'session_id', 'entity_type', 'entity_id',
        'latitude', 'longitude', 't_plus_sec',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ExerciseSession::class, 'session_id');
    }
}