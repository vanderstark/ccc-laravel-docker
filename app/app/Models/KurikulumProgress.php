<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KurikulumProgress extends Model
{
    protected $table = 'kurikulum_progress';

    protected $fillable = [
        'user_id', 'kurikulum_level_id', 'kurikulum_mapping_id',
        'leadership_assessment_id', 'status', 'skor', 'mulai', 'selesai', 'catatan'
    ];

    protected $casts = [
        'mulai' => 'date',
        'selesai' => 'date',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function level(): BelongsTo { return $this->belongsTo(KurikulumLevel::class, 'kurikulum_level_id'); }
    public function mapping(): BelongsTo { return $this->belongsTo(KurikulumMapping::class, 'kurikulum_mapping_id'); }
    public function assessment(): BelongsTo { return $this->belongsTo(LeadershipAssessment::class, 'leadership_assessment_id'); }
}