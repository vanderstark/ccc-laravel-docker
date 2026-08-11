<?php
namespace App\Services\Impact;

use App\Services\Impact\Contracts\ImpactInterface;

abstract class AbstractImpact implements ImpactInterface
{
    /**
     * Jumlah bangunan estimasi berdasarkan area & kepadatan infrastruktur.
     * Asumsi: 1 bangunan per 0.02 km² (rata-rata Indonesia).
     */
    protected function buildingCount(array $input): int
    {
        $areaKm2 = (float) ($input['area_km2'] ?? 50.0);
        $density = max((float) ($input['infrastructure_density'] ?? 0.5), 0.1);
        return (int) ($areaKm2 / 0.02 * $density);
    }

    /**
     * Severity generik dari severity_scale (0-1) atau default.
     */
    protected function severity(array $input, float $default = 0.5): float
    {
        $s = (float) ($input['severity_scale'] ?? $default);
        return max(0.0, min(1.0, $s));
    }

    /**
     * Population terdampak dengan batas.
     */
    protected function population(array $input): int
    {
        return (int) ($input['population'] ?? 500000);
    }

    /**
     * Susun array hasil standar.
     */
    protected function wrap(string $type, array $impact, int $affected, int $deaths,
        int $injured, int $displaced, int $damaged, int $destroyed,
        float $economicM, float $severity): array
    {
        $impact['type'] = $type;
        return [$impact, $affected, $deaths, $injured, $displaced,
                $damaged, $destroyed, round($economicM, 1), $severity];
    }
}