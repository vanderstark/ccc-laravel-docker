<?php
namespace App\Services\Impact;

class SosialImpact extends AbstractImpact
{
    public function calculate(string $code, array $input): array
    {
        return match ($code) {
            'social_conflict' => $this->gen($input, 'konflik_sosial', 0.0005, 0.005, 1.1, 0.35, 1.1),
            'riot'            => $this->gen($input, 'kerusuhan', 0.0008, 0.008, 1.3, 0.4, 1.2),
            'terrorism'       => $this->gen($input, 'terorisme', 0.003, 0.015, 1.4, 0.5, 1.3),
            'mass_violence'   => $this->gen($input, 'aksi_kekerasan_massal', 0.002, 0.012, 1.2, 0.45, 1.2),
            'demonstration'   => $this->gen($input, 'demo', 0.0001, 0.002, 0.4, 0.1, 0.6),
            default           => $this->gen($input, 'sosial', 0.0005, 0.005, 1.0, 0.3, 1.0),
        };
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