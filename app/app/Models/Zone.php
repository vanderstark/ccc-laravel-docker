<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zone extends Model
{
    protected $fillable = [
        'simulation_id', 'organization_id', 'nama', 'jenis',
        'warna', 'geometry', 'keterangan',
    ];

    protected $casts = [
        'geometry' => 'array',
    ];

    public function simulation(): BelongsTo
    {
        return $this->belongsTo(Simulation::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
