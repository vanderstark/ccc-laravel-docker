<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preset extends Model
{
    protected $fillable = [
        'code', 'nama', 'deskripsi', 'lat', 'lon', 'zoom',
        'population', 'area_km2', 'disaster_types', 'param_overrides',
    ];

    protected $casts = [
        'disaster_types' => 'array',
        'param_overrides' => 'array',
    ];
}