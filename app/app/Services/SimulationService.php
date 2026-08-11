<?php
namespace App\Services;

use App\Models\Simulation;
use App\Models\DisasterType;
use App\Models\War;
use App\Models\Preset;
use App\Services\Impact\ImpactEngine;
use App\Services\Impact\ResourceAllocator;
use App\Services\Impact\ActionPlanner;

class SimulationService
{
    public function __construct(
        private ImpactEngine $engine,
    ) {}

    /**
     * Jalankan simulasi bencana/konflik penuh.
     * @param array $input Input diverifikasi dari request
     * @return Simulation
     */
    public function run(array $input): Simulation
    {
        $disasterCode = $input['disaster_type'];
        $disaster = DisasterType::where('code', $disasterCode)->firstOrFail();

        // 1. Hitung dampak inti
        [$impact, $affected, $deaths, $injured, $displaced, $damaged, $destroyed, $econ, $severity]
            = $this->engine->calculate($disasterCode, $input);

        // 2. Klasifikasi alert
        $classification = ImpactEngine::classify($severity);
        $alertLevel = ImpactEngine::classifyAlert($severity);

        // 3. Kumpulkan hasil hitung
        $result = [
            'affected' => $affected, 'deaths' => $deaths, 'injured' => $injured,
            'displaced' => $displaced, 'damaged' => $damaged, 'destroyed' => $destroyed,
            'economic_damage_usd' => (float) $econ, 'severity' => $severity,
        ];

        // 4. Alokasi sumber daya + rencana aksi
        $resources = ResourceAllocator::allocate($result);
        $actions = ActionPlanner::plan($disasterCode, $alertLevel, $result);

        // 5. Simpan
        $sim = Simulation::create([
            'user_id' => auth()->id(),
            'disaster_type_id' => $disaster->id,
            'war_id' => isset($input['war_id']) && $input['war_id'] ? (int) $input['war_id'] : null,
            'preset_id' => isset($input['preset_id']) && $input['preset_id'] ? (int) $input['preset_id'] : null,
            'location' => $input['location'] ?? 'Kota Semarang',
            'lat' => $input['lat'] ?? null,
            'lon' => $input['lon'] ?? null,
            'population' => (int) ($input['population'] ?? 500000),
            'area_km2' => (float) ($input['area_km2'] ?? 50.0),
            'area_type' => $input['area_type'] ?? 'suburb',
            'infrastructure_density' => (float) ($input['infrastructure_density'] ?? 0.5),
            'params' => $input,
            'classification' => $classification,
            'alert_level' => $alertLevel,
            'affected_population' => $affected,
            'estimated_casualties' => $deaths + $injured,
            'estimated_deaths' => $deaths,
            'estimated_injured' => $injured,
            'displaced' => $displaced,
            'damaged_buildings' => $damaged,
            'destroyed_buildings' => $destroyed,
            'economic_damage_usd' => round($econ * 1_000_000, 2),
            'impact_detail' => $impact,
            'resources' => $resources,
            'actions' => $actions,
        ]);

        return $sim->load(['disasterType', 'war', 'preset', 'user']);
    }

    /** Statistik dashboard. */
    public function stats(): array
    {
        $total = Simulation::count();
        $byAlert = Simulation::selectRaw('alert_level, count(*) as c')
            ->groupBy('alert_level')->pluck('c', 'alert_level')->toArray();
        $totalAffected = (int) Simulation::sum('affected_population');
        $totalDeaths = (int) Simulation::sum('estimated_deaths');
        $totalDmg = (float) Simulation::sum('economic_damage_usd');

        $recent = Simulation::with(['disasterType'])
            ->latest()->limit(8)->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'tipe' => $s->disasterType?->nama,
                'lokasi' => $s->location,
                'alert' => $s->alert_level,
                'waktu' => $s->created_at->format('d M Y H:i'),
            ]);

        return compact('total', 'byAlert', 'totalAffected', 'totalDeaths', 'totalDmg', 'recent');
    }
}