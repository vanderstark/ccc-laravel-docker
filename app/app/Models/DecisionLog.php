<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DecisionLog extends Model
{
    protected $fillable = [
        'session_id', 'user_id', 'satker',
        'keputusan', 'pic', 't_plus_sec',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ExerciseSession::class, 'session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}