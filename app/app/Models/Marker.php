<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Marker extends Model
{
    protected $fillable = [
        'user_id', 'simulation_id', 'type', 'nama', 'kategori',
        'lat', 'lon', 'status', 'extra',
    ];

    protected $casts = [
        'extra' => 'array',
        'lat' => 'decimal:7',
        'lon' => 'decimal:7',
    ];

    public function simulation(): BelongsTo
    {
        return $this->belongsTo(Simulation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
