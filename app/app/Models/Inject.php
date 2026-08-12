<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inject extends Model
{
    protected $fillable = [
        'session_id', 'kode', 'title', 'message', 'visible_to',
        't_plus_sec', 'map_effect', 'map_effect_data',
        'requires_action', 'fail_effect', 'status', 'delivered_at',
    ];

    protected $casts = [
        'map_effect_data' => 'array',
        'delivered_at' => 'datetime',
    ];

    public const STATUS = ['queued', 'delivered', 'resolved', 'skipped'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ExerciseSession::class, 'session_id');
    }
}