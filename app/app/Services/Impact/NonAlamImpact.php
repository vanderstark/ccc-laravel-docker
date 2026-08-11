<?php
namespace App\Services\Impact;

class NonAlamImpact extends AbstractImpact
{
    public function calculate(string $code, array $input): array
    {
        return match ($code) {
            'transport_accident'      => $this->gen($input, 'kecelakaan_transportasi', 0.0004, 0.008, 0.6, 0.1, 0.7),
            'tech_failure'            => $this->techFailure($input),
            'environmental_pollution' => $this->gen($input, 'pencemaran_lingkungan', 0.0003, 0.005, 0.7, 0.2, 0.9),
            'toxic_gas'               => $this->gen($input, 'gas_beracun', 0.002, 0.015, 0.4, 0.5, 1.2),
            'construction_failure'    => $this->gen($input, 'kegagalan_konstruksi', 0.001, 0.01, 1.0, 0.2, 0.9),
            default                   => $this->gen($input, 'non_alam', 0.0005, 0.005, 0.7, 0.2, 0.9),
        };
    }

    private function techFailure(array $input): array
    {
        [$impact, $affected, $deaths, $injured, $displaced, $damaged, $destroyed, $econ, $sev]
            = $this->gen($input, 'kegagalan_teknologi', 0.0001, 0.001, 0.8, 0.15, 0.7);
        $impact['service_disruption_pct'] = round(min(0.99, $sev * 0.9), 3);
        $impact['recovery_time_hours'] = max(1, (int) ($sev * 72));
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