<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScenarioPackage extends Model
{
    protected $fillable = [
        'nama', 'versi', 'kode', 'description',
        'objectives', 'manifest', 'status',
    ];

    protected $casts = [
        'objectives' => 'array',
        'manifest' => 'array',
    ];

    public const STATUS = ['draft', 'active', 'archived'];

    // Build manifest from scenario package structure (dokumen TFG 5.4)
    public static function buildManifest(array $data): array
    {
        return [
            'nama' => $data['nama'] ?? null,
            'versi' => $data['versi'] ?? 'v1.0',
            'durasi_menit' => $data['durasi_menit'] ?? 120,
            'objectives' => $data['objectives'] ?? [],
            'difficulty' => $data['difficulty'] ?? 'sedang',
            'files' => [
                'manifest.json' => true,
                'roe.md' => true,
                'brief_*.md' => true,
                'orbat_blue.json' => true,
                'orbat_faktor.json' => true,
                'start_positions.geojson' => true,
                'injects.csv' => true,
                'scoring.json' => true,
            ],
        ];
    }
}