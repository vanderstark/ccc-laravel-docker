<?php
namespace App\Services\Impact;

class GeologicalImpact extends AbstractImpact
{
    public function calculate(string $code, array $input): array
    {
        return match ($code) {
            'earthquake'   => $this->earthquake($input),
            'tsunami'      => $this->tsunami($input),
            'volcano'      => $this->volcano($input),
            'landslide'    => $this->landslide($input),
            'liquefaction' => $this->liquefaction($input),
            default        => $this->genericFatal($input, 'gempa_bumi', 0.001, 0.008, 1.0, 0.4, 1.0),
        };
    }

    private function earthquake(array $input): array
    {
        $m = (float) ($input['earthquake_magnitude'] ?? 6.0);
        $depth = (float) ($input['earthquake_depth_km'] ?? 20.0);
        $dist = (float) ($input['epicenter_distance_km'] ?? 0.0);
        $mmi = $this->mmi($m, $depth, $dist);
        $density = max((float) ($input['infrastructure_density'] ?? 0.5), 0.1) * 1.2 + 0.4;

        if ($mmi >= 9)     { $dr = 0.008;   $ir = 0.05;  $dmgPct = 0.45; }
        elseif ($mmi >= 8) { $dr = 0.004;   $ir = 0.03;  $dmgPct = 0.28; }
        elseif ($mmi >= 7) { $dr = 0.0015;  $ir = 0.015; $dmgPct = 0.14; }
        elseif ($mmi >= 6) { $dr = 0.0004;  $ir = 0.006; $dmgPct = 0.06; }
        else               { $dr = 0.0001;  $ir = 0.002; $dmgPct = 0.02; }
        $dr *= $density; $ir *= $density;

        $pop = $this->population($input);
        $affected = (int) ($pop * min(1.0, 0.25 + $mmi * 0.07));
        $deaths = (int) ($pop * $dr);
        $injured = (int) ($pop * $ir);
        $displaced = (int) ($affected * 0.55);
        $bc = $this->buildingCount($input);
        $damaged = (int) ($bc * $dmgPct);
        $destroyed = (int) ($damaged * 0.35);
        $econ = round(($destroyed * 120000 + $damaged * 45000) / 1_000_000, 1);
        $sev = min(1.0, $mmi / 10);

        $impact = ['mmi' => round($mmi, 1), 'magnitude' => $m, 'depth_km' => $depth,
                   'epicenter_distance_km' => $dist, 'damage_percentage' => $dmgPct,
                   'severity' => round($sev, 3)];
        return $this->wrap('gempa_bumi', $impact, $affected, $deaths, $injured,
                           $displaced, $damaged, $destroyed, $econ, $sev);
    }

    private function mmi(float $m, float $depth, float $dist): float
    {
        $d = max($dist ?: 10, 1.0);
        $mmi = 3.5 + 0.65 * $m - 1.6 * log10($d + 10) - 0.2 * log10(max($depth ?: 10, 5));
        return max(2.0, min(12.0, $mmi));
    }

    private function tsunami(array $input): array
    {
        $wave = (float) ($input['tsunami_wave_height_m'] ?? 3.0);
        $epic = (float) ($input['tsunami_epicenter_distance_km'] ?? 50.0);
        if ($wave >= 15)     { $sev = 0.95; $dr = 0.03;  $ir = 0.10; $dmg = 0.70; }
        elseif ($wave >= 10) { $sev = 0.80; $dr = 0.015; $ir = 0.06; $dmg = 0.50; }
        elseif ($wave >= 5)  { $sev = 0.55; $dr = 0.005; $ir = 0.03; $dmg = 0.30; }
        elseif ($wave >= 2)  { $sev = 0.30; $dr = 0.001; $ir = 0.01; $dmg = 0.12; }
        else                 { $sev = 0.10; $dr = 0.0002; $ir = 0.003; $dmg = 0.04; }
        $shore = max(0.5, 1.0 - $epic / 500);

        $pop = $this->population($input);
        $affected = (int) ($pop * min(0.9, 0.15 + $sev * $shore * 0.5));
        $deaths = (int) ($affected * $dr);
        $injured = (int) ($affected * $ir);
        $displaced = (int) ($affected * 0.70);
        $bc = $this->buildingCount($input);
        $damaged = (int) ($bc * $dmg);
        $destroyed = (int) ($damaged * 0.55);
        $econ = round(($damaged * 50000 + $destroyed * 150000) / 1_000_000, 1);

        $impact = ['wave_height_m' => $wave, 'epicenter_distance_km' => $epic,
                   'shore_factor' => round($shore, 3), 'severity' => round($sev, 3)];
        return $this->wrap('tsunami', $impact, $affected, $deaths, $injured,
                           $displaced, $damaged, $destroyed, $econ, $sev);
    }

    private function volcano(array $input): array
    {
        $vei = (int) ($input['volcano_vei'] ?? 3);
        $dist = (float) ($input['volcano_eruption_distance_km'] ?? 10.0);
        $veiData = [
            0 => ['ash' => 1, 'death' => 0.0001, 'dmg' => 0.05, 'sev' => 0.05],
            1 => ['ash' => 5, 'death' => 0.0003, 'dmg' => 0.08, 'sev' => 0.12],
            2 => ['ash' => 20, 'death' => 0.001, 'dmg' => 0.15, 'sev' => 0.25],
            3 => ['ash' => 100, 'death' => 0.003, 'dmg' => 0.28, 'sev' => 0.45],
            4 => ['ash' => 300, 'death' => 0.008, 'dmg' => 0.40, 'sev' => 0.65],
            5 => ['ash' => 1000, 'death' => 0.02, 'dmg' => 0.55, 'sev' => 0.80],
            6 => ['ash' => 3000, 'death' => 0.04, 'dmg' => 0.70, 'sev' => 0.90],
            7 => ['ash' => 10000, 'death' => 0.08, 'dmg' => 0.85, 'sev' => 0.95],
            8 => ['ash' => 30000, 'death' => 0.15, 'dmg' => 0.95, 'sev' => 1.00],
        ];
        $v = $veiData[min($vei, 8)] ?? $veiData[3];
        $distFactor = max(0.2, 1.0 / (1 + ($dist / $v['ash']) ** 2));
        $sev = $v['sev'] * $distFactor;

        $pop = $this->population($input);
        $affected = (int) ($pop * min(0.9, 0.1 + $sev * 0.6));
        $deaths = (int) ($affected * $v['death'] * $distFactor);
        $injured = (int) ($affected * $v['death'] * 3 * $distFactor);
        $displaced = (int) ($affected * min(0.8, 0.3 + $sev * 0.4));
        $bc = $this->buildingCount($input);
        $damaged = (int) ($bc * $v['dmg'] * $distFactor);
        $destroyed = (int) ($damaged * (0.3 + 0.1 * $vei));
        $econ = round(($damaged * 40000 + $destroyed * 100000) / 1_000_000, 1);

        $impact = ['vei' => $vei, 'distance_km' => $dist, 'ash_radius_km' => $v['ash'],
                   'dist_factor' => round($distFactor, 3), 'severity' => round($sev, 3)];
        return $this->wrap('letusan_gunung_api', $impact, $affected, $deaths, $injured,
                           $displaced, $damaged, $destroyed, $econ, $sev);
    }

    private function landslide(array $input): array
    {
        return $this->genericFatal($input, 'tanah_longsor', 0.002, 0.008, 1.2, 0.35, 1.3);
    }

    private function liquefaction(array $input): array
    {
        return $this->genericFatal($input, 'likuifaksi', 0.003, 0.012, 1.5, 0.5, 1.3);
    }

    private function genericFatal(array $input, string $type, float $drBase,
        float $irBase, float $dmgMult, float $dispRatio, float $sevMult): array
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
        return $this->wrap($type, $impact, $affected, $deaths, $injured,
                           $displaced, $damaged, $destroyed, $econ, $s);
    }
}