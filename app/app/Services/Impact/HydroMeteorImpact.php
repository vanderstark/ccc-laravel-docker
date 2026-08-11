<?php
namespace App\Services\Impact;

class HydroMeteorImpact extends AbstractImpact
{
    public function calculate(string $code, array $input): array
    {
        return match ($code) {
            'flood'              => $this->flood($input),
            'flash_flood'        => $this->flashFlood($input),
            'drought'            => $this->drought($input),
            'tornado'            => $this->tornado($input),
            'strong_wind'        => $this->strongWind($input),
            'coastal_abrasion'   => $this->coastal($input),
            'extreme_wave'       => $this->extremeWave($input),
            default              => $this->gen($input, 'banjir', 0.0003, 0.004, 1.0, 0.4, 1.0),
        };
    }

    private function flood(array $input): array
    {
        $depth = (float) ($input['flood_depth_m'] ?? 1.0);
        $durH = (float) ($input['flood_duration_hours'] ?? 12.0);
        $dmgPct = min(0.85, 0.10 + 0.15 * $depth + 0.002 * min($durH, 120));
        $affectedRatio = min(0.9, 0.2 + 0.1 * $depth + 0.05 * min((int)($durH / 12), 10));
        $pop = $this->population($input);
        $affected = (int) ($pop * $affectedRatio);
        $displaced = (int) ($affected * 0.65);

        if ($depth < 2)      { $dr = 0.0003; $ir = 0.004; }
        elseif ($depth < 4)  { $dr = 0.001;  $ir = 0.01;  }
        else                  { $dr = 0.003;  $ir = 0.025; }
        $deaths = (int) ($affected * $dr);
        $injured = (int) ($affected * $ir);
        $bc = $this->buildingCount($input);
        $damaged = (int) ($bc * $dmgPct);
        $destroyed = (int) ($damaged * 0.25);
        $econ = round(($damaged * 25000 + $destroyed * 60000) / 1_000_000, 1);
        $sev = min(1.0, $depth / 5 + $durH / 200);

        $impact = ['flood_depth_m' => $depth, 'duration_hours' => $durH, 'damage_pct' => round($dmgPct, 3),
                   'severity' => round($sev, 3)];
        return $this->wrap('banjir', $impact, $affected, $deaths, $injured,
                           $displaced, $damaged, $destroyed, $econ, $sev);
    }

    private function flashFlood(array $input): array
    {
        return $this->gen($input, 'banjir_bandang', 0.003, 0.015, 1.4, 0.55, 1.3);
    }

    private function drought(array $input): array
    {
        [$impact, $affected, $deaths, $injured, $displaced, $damaged, $destroyed, $econ, $sev]
            = $this->gen($input, 'kekeringan', 0.0001, 0.001, 0.5, 0.15, 0.8);
        $impact['agricultural_loss_pct'] = round(min(0.9, $sev * 0.8), 3);
        $impact['water_scarcity'] = $sev > 0.6 ? 'kritis' : ($sev > 0.3 ? 'sedang' : 'ringan');
        $econ = round($econ + $affected * 15 / 1_000_000, 1);
        return [$impact, $affected, $deaths, $injured, $displaced, $damaged, $destroyed, $econ, $sev];
    }

    private function tornado(array $input): array
    {
        return $this->gen($input, 'angin_puting_beliung', 0.0008, 0.006, 1.5, 0.3, 1.2);
    }

    private function strongWind(array $input): array
    {
        return $this->gen($input, 'angin_kencang', 0.0003, 0.003, 1.1, 0.2, 1.0);
    }

    private function coastal(array $input): array
    {
        return $this->gen($input, 'abrasi_pantai', 0.0001, 0.001, 0.8, 0.25, 0.9);
    }

    private function extremeWave(array $input): array
    {
        return $this->gen($input, 'gelombang_ekstrem', 0.0005, 0.003, 0.9, 0.3, 1.0);
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