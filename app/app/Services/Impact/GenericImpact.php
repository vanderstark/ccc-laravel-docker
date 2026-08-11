<?php
namespace App\Services\Impact;

use App\Services\Impact\Contracts\ImpactInterface;

class GenericImpact extends AbstractImpact
{
    /**
     * Formula generik:
     *   affected = pop × min(0.9, 0.1 + severity × 0.6)
     *   deaths   = pop × 0.0005 × (1 + 3 × severity) × density
     *   injured = affected × 0.003 × (1 + 2 × severity)
     *   displaced = affected × (0.3 + severity × 0.3)
     *   damaged = building_count × (0.03 + severity × 0.4)
     *   destroyed = damaged × (0.1 + severity × 0.25)
     *   economic = (damaged × 30000 + destroyed × 80000) / 1_000_000
     */
    public function calculate(string $disasterCode, array $input): array
    {
        $severity = $this->severity($input);
        $pop = $this->population($input);
        $area = (float) ($input['area_km2'] ?? 50.0);
        $density = max((float) ($input['infrastructure_density'] ?? 0.5), 0.1);

        $affected = (int) ($pop * min(0.9, 0.1 + $severity * 0.6));
        $deathRate = 0.0005 * (1 + 3 * $severity) * $density;
        $deaths = (int) ($pop * $deathRate);
        $injured = (int) ($affected * 0.003 * (1 + 2 * $severity));
        $displaced = (int) ($affected * (0.3 + $severity * 0.3));
        $buildingCount = (int) ($area / 0.02 * max($density, 0.1));
        $damaged = (int) ($buildingCount * (0.03 + $severity * 0.4));
        $destroyed = (int) ($damaged * (0.1 + $severity * 0.25));
        $economic = round(($damaged * 30000 + $destroyed * 80000) / 1_000_000, 1);

        $impact = [
            'type' => $disasterCode,
            'severity' => round($severity, 3),
            'affected' => $affected,
            'deaths' => $deaths,
            'injured' => $injured,
            'displaced' => $displaced,
            'damaged' => $damaged,
            'destroyed' => $destroyed,
            'economic_damage_usd' => $economic,
        ];

        return [$impact, $affected, $deaths, $injured, $displaced,
                $damaged, $destroyed, $economic, $severity];
    }
}