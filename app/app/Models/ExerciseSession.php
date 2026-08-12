<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExerciseSession extends Model
{
    protected $fillable = [
        'nama', 'kode', 'simulation_id', 'preset_id',
        'status', 'objectives', 'roe', 'durasi_menit',
        't_plus_detik', 'mulai_pada', 'akhir_pada', 'created_by',
    ];

    protected $casts = [
        'objectives' => 'array',
        'mulai_pada' => 'datetime',
        'akhir_pada' => 'datetime',
    ];

    public const STATUS = ['draft', 'briefing', 'running', 'paused', 'ended'];

    // States
    public function simulation(): BelongsTo
    {
        return $this->belongsTo(Simulation::class);
    }

    public function preset(): BelongsTo
    {
        return $this->belongsTo(Preset::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function injects(): HasMany
    {
        return $this->hasMany(Inject::class, 'session_id');
    }

    public function orbatUnits(): HasMany
    {
        return $this->hasMany(OrbatUnit::class, 'session_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderBoard::class, 'session_id');
    }

    public function decisionLogs(): HasMany
    {
        return $this->hasMany(DecisionLog::class, 'session_id');
    }

    public function movementLogs(): HasMany
    {
        return $this->hasMany(MovementLog::class, 'session_id');
    }

    public function fogOfWar(): HasMany
    {
        return $this->hasMany(FogOfWar::class, 'session_id');
    }

    // Helper: tick T+ timer (called via API polling / live sync)
    public function tickTimer(): void
    {
        if ($this->status === 'running' && $this->mulai_pada) {
            $elapsed = now()->diffInSeconds($this->mulai_pada);
            $this->t_plus_detik = $elapsed;
            $this->saveQuietly();
        }
    }

    public function canTransition(string $to): bool
    {
        $allowed = [
            'draft'    => ['briefing'],
            'briefing' => ['running', 'draft'],
            'running'  => ['paused', 'ended'],
            'paused'   => ['running', 'ended'],
            'ended'    => [],
        ];

        return in_array($to, $allowed[$this->status] ?? []);
    }
}