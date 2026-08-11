<?php
namespace App\Services\Impact;

class BioImpact extends AbstractImpact
{
    public function calculate(string $code, array $input): array
    {
        return match ($code) {
            'disease_outbreak' => $this->gen($input, 'wabah_penyakit', 0.001, 0.01, 0.3, 0.1, 0.9),
            'pandemic'         => $this->pandemic($input),
            default            => $this->gen($input, 'biologi', 0.001, 0.01, 0.3, 0.1, 0.9),
        };
    }

    private function pandemic(array $input): array
    {
        [$impact, $affected, $deaths, $injured, $displaced, $damaged, $destroyed, $econ, $sev]
            = $this->gen($input, 'pandemi', 0.005, 0.02, 0.2, 0.05, 1.2);
        if ($sev > 0.7) {
            $deaths = (int) ($deaths * 2);
            $impact['mortality_rate_pct'] = round($sev * 2.5, 2);
        } else {
            $impact['mortality_rate_pct'] = round($sev * 1.2, 2);
        }
        $impact['quarantine_zone'] = $sev > 0.5 ? 'area' : 'lokasi';
        $impact['healthcare_capacity_stress'] = $sev > 0.6 ? 'kritis' : 'sedang';
        return [$impact, $affected, $deaths, $injured, $displaced, $damaged, $destroyed, $econ, $sev];
    }

    private function gen(array $input, string $type, float $drBase, float $irBase,
        float $dmgMult, float $dispRatio, float $sevMult): array
    {
        $s = $this->severity($input) * $sevMult;
        $pop = $this->population($input);
        $affected = (int) ($pop * min(0.95, 0.1 + $s * 0.6));
        $deaths = (int) ($pop * $drBase * (1 + 3 * $s));
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