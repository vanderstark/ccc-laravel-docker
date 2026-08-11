<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DisasterType extends Model
{
    protected $fillable = [
        'code', 'nama', 'kategori', 'kelompok', 'deskripsi', 'param_demo',
    ];

    protected $casts = [
        'param_demo' => 'array',
    ];

    public function simulations(): HasMany
    {
        return $this->hasMany(Simulation::class);
    }
}