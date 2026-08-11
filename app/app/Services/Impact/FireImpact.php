<?php
namespace App\Services\Impact;

class FireImpact extends AbstractImpact
{
    public function calculate(string $code, array $input): array
    {
        return match ($code) {
            'forest_fire'      => $this->forestFire($input),
            'building_fire'    => $this->gen($input, 'kebakaran_gedung', 0.0006, 0.005, 1.3, 0.2, 1.0),
            'settlement_fire'  => $this->gen($input, 'kebakaran_permukiman', 0.0008, 0.006, 1.5, 0.45, 1.1),
            default            => $this->gen($input, 'kebakaran', 0.0005, 0.004, 1.0, 0.3, 1.0),
        };
    }

    private function forestFire(array $input): array
    {
        $areaHa = (float) ($input['fire_area_ha'] ?? 500.0);
        $wind = (float) ($input['fire_wind_speed_kmh'] ?? 20.0);
        $fuel = (string) ($input['fire_fuel_type'] ?? 'forest');
        $fuelMult = ['peat' => 1.6, 'forest' => 1.2, 'mineral' => 0.8, 'urban' => 0.7][$fuel] ?? 1.0;
        $areaSev = min(1.0, log10(max($areaHa, 1) + 1) / 6);
        $windMult = 1.0 + $wind / 100;
        $sev = min(1.0, $areaSev * $fuelMult * $windMult);

        $pop = $this->population($input);
        $affected = (int) ($pop * min(0.85, 0.1 + $sev * 0.5));
        $smokeDeath = 0.0005 + 0.002 * $sev * $fuelMult;
        $deaths = (int) ($affected * $smokeDeath);
        $injured = (int) ($affected * $smokeDeath * 8);
        $displaced = (int) ($affected * min(0.7, 0.3 + $sev * 0.35));
        $bc = $this->buildingCount($input);
        $damaged = (int) ($bc * min(0.5, $sev * 0.35));
        $destroyed = (int) ($damaged * min(0.4, $sev * 0.3));
        $forestLoss = $areaHa * 5000 * $fuelMult;
        $buildingLoss = $damaged * 30000 + $destroyed * 80000;
        $evacCost = $displaced * 50;
        $econ = round(($forestLoss + $buildingLoss + $evacCost) / 1_000_000, 1);

        $impact = ['area_ha' => $areaHa, 'wind_kmh' => $wind, 'fuel_type' => $fuel,
                   'smoke_radius_km' => round($wind * $sev * 2, 1), 'severity' => round($sev, 3)];
        return [$impact, $affected, $deaths, $injured, $displaced, $damaged, $destroyed, $econ, $sev];
    }

    private function gen(array $input, string $type, float $drBase, float $irBase,
        float $dmgMult, float $dispRatio, float $sevMult): array
    {
        $s = $this->severity($input) * $sevMult;
        $density = max((float) ($input['infrastructure_density'] ?? 0.5), 0.1) * 1.2 + 0.4;
        $pop = $this->population($input);
        $affected = (int) ($pop * min(0.95, 0.1 + $s * 0.6));
        $deaths = (int) ($pop * $drBase * (1 + 3 * $s) * $density);
        $injured = (int) ($affected * $irBase * (1 + 2 * $s));
        $displaced = (int) ($affected * min(0.8, $dispRatio + $s * 0.3));
        $bc = $this->buildingCount($input);
        $damaged = (int) ($bc * min(0.7, 0.03 + $s * 0.4) * $dmgMult);
        $destroyed = (int) ($damaged * min(0.45, 0.1 + $s * 0.25));
        $econ = round(($damaged * 30000 + $destroyed * 80000) / 1_000_000, 1);
        $impact = ['severity' => round($s, 3)];
        return [$impact, $affected, $deaths, $injured, $displaced, $damaged, $destroyed, $econ, $s];
    }
}