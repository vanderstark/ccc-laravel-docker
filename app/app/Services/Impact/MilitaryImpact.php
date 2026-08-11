<?php
namespace App\Services\Impact;

class MilitaryImpact extends AbstractImpact
{
    public function calculate(string $code, array $input): array
    {
        return match ($code) {
            'conflict'  => $this->conflict($input),
            'maritime'  => $this->maritime($input),
            'air'       => $this->air($input),
            'combined'  => $this->combined($input),
            default     => $this->gen($input, 'operasi_militer', 0.001, 0.01, 1.0, 0.2, 1.0),
        };
    }

    private function conflict(array $input): array
    {
        $intensity = (float) ($input['conflict_intensity'] ?? 0.5);
        $type = (string) ($input['conflict_type'] ?? 'conventional');
        $typeMult = ['conventional' => 1.0, 'insurgency' => 1.2, 'counter_insurgency' => 1.1, 'urban' => 1.3][$type] ?? 1.0;
        $sev = min(1.0, $intensity * $typeMult);

        $pop = $this->population($input);
        $affected = (int) ($pop * min(0.9, 0.2 + $sev * 0.5));
        $deaths = (int) ($affected * 0.01 * $sev);
        $injured = (int) ($affected * 0.04 * $sev);
        $displaced = (int) ($affected * min(0.8, 0.4 + $sev * 0.3));
        $bc = $this->buildingCount($input);
        $damaged = (int) ($bc * min(0.6, $sev * 0.5));
        $destroyed = (int) ($damaged * 0.3);
        $econ = round(($damaged * 50000 + $destroyed * 200000) / 1_000_000, 1);

        $impact = ['conflict_intensity' => $intensity, 'conflict_type' => $type,
                   'severity' => round($sev, 3)];
        return $this->wrap('konflik_darat', $impact, $affected, $deaths, $injured,
                           $displaced, $damaged, $destroyed, $econ, $sev);
    }

    private function maritime(array $input): array
    {
        $threat = (float) ($input['maritime_threat_level'] ?? 0.5);
        $units = (int) ($input['enemy_naval_units'] ?? 3);
        $sev = min(1.0, $threat + $units * 0.05);

        $pop = $this->population($input);
        $affected = (int) ($pop * min(0.7, 0.15 + $sev * 0.4));
        $deaths = (int) ($affected * 0.008 * $sev);
        $injured = (int) ($affected * 0.03 * $sev);
        $displaced = (int) ($affected * 0.3);
        $bc = $this->buildingCount($input);
        $damaged = (int) ($bc * min(0.4, $sev * 0.3));
        $destroyed = (int) ($damaged * 0.2);
        $econ = round(($damaged * 80000 + $destroyed * 300000 + $units * 5_000_000) / 1_000_000, 1);

        $impact = ['threat_level' => $threat, 'enemy_units' => $units, 'severity' => round($sev, 3)];
        return $this->wrap('konflik_laut', $impact, $affected, $deaths, $injured,
                           $displaced, $damaged, $destroyed, $econ, $sev);
    }

    private function air(array $input): array
    {
        $threat = (float) ($input['air_threat_level'] ?? 0.5);
        $units = (int) ($input['enemy_aircraft'] ?? 4);
        $sev = min(1.0, $threat + $units * 0.06);

        $pop = $this->population($input);
        $affected = (int) ($pop * min(0.6, 0.1 + $sev * 0.3));
        $deaths = (int) ($affected * 0.01 * $sev);
        $injured = (int) ($affected * 0.03 * $sev);
        $displaced = (int) ($affected * 0.25);
        $bc = $this->buildingCount($input);
        $damaged = (int) ($bc * min(0.5, $sev * 0.4));
        $destroyed = (int) ($damaged * 0.25);
        $econ = round(($damaged * 100000 + $destroyed * 400000 + $units * 15_000_000) / 1_000_000, 1);

        $impact = ['threat_level' => $threat, 'enemy_aircraft' => $units, 'severity' => round($sev, 3)];
        return $this->wrap('konflik_udara', $impact, $affected, $deaths, $injured,
                           $displaced, $damaged, $destroyed, $econ, $sev);
    }

    private function combined(array $input): array
    {
        $cInt = (float) ($input['conflict_intensity'] ?? 0.5);
        $mThreat = (float) ($input['maritime_threat_level'] ?? 0.5);
        $aThreat = (float) ($input['air_threat_level'] ?? 0.5);
        $sev = min(1.0, ($cInt + $mThreat + $aThreat) / 3);

        $pop = $this->population($input);
        $affected = (int) ($pop * min(0.8, 0.15 + $sev * 0.5));
        $deaths = (int) ($affected * 0.009 * $sev);
        $injured = (int) ($affected * 0.035 * $sev);
        $displaced = (int) ($affected * min(0.75, 0.35 + $sev * 0.3));
        $bc = $this->buildingCount($input);
        $damaged = (int) ($bc * min(0.55, $sev * 0.4));
        $destroyed = (int) ($damaged * 0.28);
        $econ = round(($damaged * 90000 + $destroyed * 350000) / 1_000_000, 1);

        $impact = ['conflict_intensity' => $cInt, 'maritime_threat' => $mThreat,
                   'air_threat' => $aThreat, 'severity' => round($sev, 3)];
        return $this->wrap('operasi_gabungan', $impact, $affected, $deaths, $injured,
                           $displaced, $damaged, $destroyed, $econ, $sev);
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