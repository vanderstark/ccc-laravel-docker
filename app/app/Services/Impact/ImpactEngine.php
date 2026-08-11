<?php
namespace App\Services\Impact;

use App\Services\Impact\Contracts\ImpactInterface;
use Illuminate\Support\Facades\Log;

class ImpactEngine
{
    /** Map disaster code → handler class FQCN */
    private const HANDLERS = [
        'earthquake'               => GeologicalImpact::class,
        'tsunami'                  => GeologicalImpact::class,
        'volcano'                  => GeologicalImpact::class,
        'landslide'                => GeologicalImpact::class,
        'liquefaction'             => GeologicalImpact::class,
        'flood'                    => HydroMeteorImpact::class,
        'flash_flood'              => HydroMeteorImpact::class,
        'drought'                  => HydroMeteorImpact::class,
        'tornado'                  => HydroMeteorImpact::class,
        'strong_wind'              => HydroMeteorImpact::class,
        'coastal_abrasion'         => HydroMeteorImpact::class,
        'extreme_wave'             => HydroMeteorImpact::class,
        'disease_outbreak'         => BioImpact::class,
        'pandemic'                 => BioImpact::class,
        'forest_fire'              => FireImpact::class,
        'building_fire'            => FireImpact::class,
        'settlement_fire'          => FireImpact::class,
        'transport_accident'       => NonAlamImpact::class,
        'tech_failure'             => NonAlamImpact::class,
        'environmental_pollution'  => NonAlamImpact::class,
        'toxic_gas'                => NonAlamImpact::class,
        'construction_failure'     => NonAlamImpact::class,
        'social_conflict'          => SosialImpact::class,
        'riot'                     => SosialImpact::class,
        'terrorism'                => SosialImpact::class,
        'mass_violence'            => SosialImpact::class,
        'demonstration'            => SosialImpact::class,
        'conflict'                 => MilitaryImpact::class,
        'maritime'                 => MilitaryImpact::class,
        'air'                      => MilitaryImpact::class,
        'combined'                 => MilitaryImpact::class,
    ];

    /**
     * Dispatch perhitungan dampak berdasarkan disaster_type code.
     * @param string $disasterCode  Kode tipe bencana (earthquake, flood, dsb.)
     * @param array  $input         Input simulasi (population, lat, lon, param khusus, dll.)
     * @return array                [impact, affected, deaths, injured, displaced,
     *                               damaged, destroyed, economic_m, severity]
     */
    public function calculate(string $disasterCode, array $input): array
    {
        $handlerClass = self::HANDLERS[$disasterCode] ?? null;
        if (!$handlerClass || !class_exists($handlerClass)) {
            Log::warning("ImpactEngine: unknown disaster code '$disasterCode', using GenericImpact");
            $handlerClass = GenericImpact::class;
        }
        /** @var ImpactInterface $handler */
        $handler = new $handlerClass();
        return $handler->calculate($disasterCode, $input);
    }

    /**
     * Hitung alert level berdasarkan severity.
     */
    public static function classifyAlert(float $severity): string
    {
        if ($severity >= 0.75) return 'merah';      // Kritis — Evakuasi total
        if ($severity >= 0.50) return 'oranye';     // Parah — Siaga darurat
        if ($severity >= 0.25) return 'kuning';     // Sedang — Siaga
        return 'hijau';                             // Ringan — Waspada
    }

    /**
     * Klasifikasi dampak berdasarkan severity.
     */
    public static function classify(float $severity): string
    {
        if ($severity >= 0.75) return 'Kritis';
        if ($severity >= 0.50) return 'Parah';
        if ($severity >= 0.25) return 'Sedang';
        return 'Ringan';
    }
}