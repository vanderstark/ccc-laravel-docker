<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadershipAssessment extends Model
{
    protected $fillable = [
        'user_id', 'simulation_id', 'war_id', 'scenario_type', 'scenario_name',
        'skor_keputusan', 'skor_kecepatan', 'skor_kolaborasi', 'skor_komunikasi',
        'skor_integritas', 'skor_risiko', 'skor_total', 'grade', 'catatan', 'detail_penilaian',
    ];

    protected $casts = [
        'detail_penilaian' => 'array',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function simulation(): BelongsTo { return $this->belongsTo(Simulation::class); }

    public function hitungTotal(): void
    {
        $avg = (floatval($this->skor_keputusan) + floatval($this->skor_kecepatan) +
                floatval($this->skor_kolaborasi) + floatval($this->skor_komunikasi) +
                floatval($this->skor_integritas) + floatval($this->skor_risiko)) / 6;
        $this->skor_total = round($avg, 2);
        $this->grade = $avg >= 90 ? 'A' : ($avg >= 80 ? 'B' : ($avg >= 70 ? 'C' : ($avg >= 60 ? 'D' : 'E')));
        $this->save();
    }
}
