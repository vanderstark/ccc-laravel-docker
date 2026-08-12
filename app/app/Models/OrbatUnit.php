<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrbatUnit extends Model
{
    protected $fillable = [
        'session_id', 'satker', 'nama_unit', 'jenis',
        'kekuatan', 'status', 'latitude', 'longitude', 'detail',
    ];

    protected $casts = [
        'detail' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public const SATKER = [
        'ai' => 'Analisis Informasi',
        'reserse' => 'Reserse',
        'brimob' => 'Brimob',
        'lantas' => 'Lantas',
        'sabhara' => 'Sabhara',
        'binmas' => 'Binmas',
        'manajemen_konflik' => 'Manajemen Konflik',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ExerciseSession::class, 'session_id');
    }
}