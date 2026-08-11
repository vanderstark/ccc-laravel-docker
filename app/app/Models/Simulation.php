<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Simulation extends Model
{
    protected $fillable = [
        'user_id', 'disaster_type_id', 'war_id', 'preset_id',
        'location', 'lat', 'lon', 'population', 'area_km2', 'area_type',
        'infrastructure_density', 'params',
        'classification', 'alert_level',
        'affected_population', 'estimated_casualties', 'estimated_deaths',
        'estimated_injured', 'displaced', 'damaged_buildings', 'destroyed_buildings',
        'economic_damage_usd', 'impact_detail', 'resources', 'actions',
    ];

    protected $casts = [
        'params' => 'array',
        'impact_detail' => 'array',
        'resources' => 'array',
        'actions' => 'array',
    ];

    public function disasterType(): BelongsTo
    {
        return $this->belongsTo(DisasterType::class);
    }

    public function war(): BelongsTo
    {
        return $this->belongsTo(War::class);
    }

    public function preset(): BelongsTo
    {
        return $this->belongsTo(Preset::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}