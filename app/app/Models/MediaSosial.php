<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaSosial extends Model
{
    protected $table = 'media_sosial';

    protected $fillable = ['simulation_id', 'platform', 'jenis_konten', 'judul', 'konten', 'sumber', 'sentimen', 'jangkauan', 'status', 'analisis'];

    protected $casts = [
        'analisis' => 'array',
    ];

    public function simulation(): BelongsTo
    {
        return $this->belongsTo(Simulation::class);
    }

    /** Auto-analisis sentimen & deteksi hoax/rumor saat create. */
    protected static function booted(): void
    {
        static::creating(function (MediaSosial $model) {
            $analisis = self::analyzeRumor($model->konten);
            $model->analisis = $analisis;
            $model->sentimen = $analisis['sentiment'];
            if ($model->jenis_konten === 'hoax' || $analisis['is_hoax']) {
                $model->status = 'hoax_terkonfirmasi';
            }
        });
    }

    /** Analisis sederhana: deteksi kata kunci hoax/rumor (offline, rule-based) */
    public static function analyzeRumor(string $text): array
    {
        $hoaxKeywords = ['hoax', 'rektif', 'bukti', 'nyata', 'terbukti salah', 'konspirasi', 'teori segitiga'];
        $rumorKeywords = ['dengar', 'kabar', 'bocorkan', 'bocor', 'ramai', 'gempar', 'viral'];
        $textLower = strtolower($text);

        $isHoax = false;
        foreach ($hoaxKeywords as $kw) {
            if (str_contains($textLower, $kw)) { $isHoax = true; break; }
        }
        $isRumor = false;
        foreach ($rumorKeywords as $kw) {
            if (str_contains($textLower, $kw)) { $isRumor = true; break; }
        }

        $sentiment = 'netral';
        $negatif = ['kematian', 'bencana', 'wafat', 'korban', 'genting', 'gentrinya', 'tolong', 'bantuan', 'darurat'];
        foreach ($negatif as $kw) {
            if (str_contains($textLower, $kw)) { $sentiment = 'negatif'; break; }
        }

        return [
            'is_hoax' => $isHoax,
            'is_rumor' => $isRumor,
            'sentiment' => $sentiment,
            'urgency' => $isRumor || $isHoax ? 'tinggi' : 'rendah',
        ];
    }
}
