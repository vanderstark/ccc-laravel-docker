<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AarSession extends Model
{
    protected $fillable = [
        'user_id', 'simulation_id', 'leadership_assessment_id', 'tahap', 'judul', 'konten', 'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function simulation(): BelongsTo { return $this->belongsTo(Simulation::class); }
    public function assessment(): BelongsTo { return $this->belongsTo(LeadershipAssessment::class); }
}
