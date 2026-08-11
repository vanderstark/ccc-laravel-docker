<?php

namespace App\Services;

use App\Models\Simulation;
use App\Models\DisasterType;
use App\Models\War;
use App\Models\Preset;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CCCService
{
    private const DISASTER_MAP = [
        'earthquake' => 'gempa_bumi',
        'tsunami' => 'tsunami',
        'volcano' => 'letusan_gunung_api',
        'landslide' => 'tanah_longsor',
        'liquefaction' => 'likuifaksi',
        'flood' => 'banjir',
        'flash_flood' => 'banjir_bandang',
        'drought' => 'kekeringan',
        'tornado' => 'angin_puting_beliung',
        'strong_wind' => 'angin_kencang',
        'coastal_abrasion' => 'abrasi_pantai',
        'extreme_wave' => 'gelombang_ekstrem',
        'disease_outbreak' => 'wabah_penyakit',
        'pandemic' => 'pandemi',
        'forest_fire' => 'kebakaran_hutan_lahan',
        'building_fire' => 'kebakaran_gedung',
        'settlement_fire' => 'kebakaran_permukiman',
        'transport_accident' => 'kecelakaan_transportasi',
        'tech_failure' => 'kegagalan_teknologi',
        'environmental_pollution' => 'pencemaran_lingkungan',
        'toxic_gas' => 'gas_beracun',
        'construction_failure' => 'kegagalan_konstruksi',
        'social_conflict' => 'konflik_sosial',
        'riot' => 'kerusuhan',
        'terrorism' => 'terorisme',
        'mass_violence' => 'aksi_kekerasan_massal',
        'demonstration' => 'demo',
        'conflict' => 'konflik_darat',
        'maritime' => 'konflik_laut',
        'air' => 'konflik_udara',
        'combined' => 'operasi_gabungan',
    ];

    /**
     * Hitung dampak simulasi (simulasi berbasis formula).
     * @param SimulateRequest $req
     * @return array impact, affected, deaths, injured, displaced, damaged, destroyed, economic_damage, severity
     */
    public function calculate($req)
    {
        $disasterType = $this->getDisasterType($req->disaster_type);

        // Formula dasar — bisa di-extend per tipe
        $formula = new $disasterType();
        $formula->setRequest($req);
        return $formula->compute();
    }

    private function getDisasterType($disasterType)
    {
        $map = $this->DISASTER_MAP;
        return $map[$disasterType] ?? 'general';
    }

    /**
     * Buat seeder untuk 31 tipe bencana.
     */
    public function seedDisasterTypes(): void
    {
        $data = [
            // Gempa Bumi
            ['code' => 'earthquake', 'nama' => 'Gempa Bumi', 'kategori' => 'Bencana Alam Geologis', 'kelompok' => 'Alam', 'deskripsi' => 'Gempa bumi berdenyut yang disebabkan oleh pergerakan platen.'],
            // ... 31 total
        ];
        // Insert 31 items from the same array.
        foreach ($data as $d) {
            DisasterType::create($d);
        }
    }

    /**
     * Buat seeder untuk 45 perang sejarah Indonesia.
     */
    public function seedWars(): void
    {
        $data = [
            ['nama' => 'Perang Bubat', 'tahun' => '1357', 'wilayah' => 'Bubat, Jawa Timur (Trowulan)', 'matra' => 'darat', 'kategori' => 'Perang Era Kerajaan', 'pop' => 200000, 'lat' => -7.55, 'lon' => 112.38, 'deskripsi' => 'Konflik Majapahit vs Kerajaan Sunda.'],
            // ... 45 total
        ];
        foreach ($data as $d) {
            War::create($d);
        }
    }

    /**
     * Buat seeder untuk 3 preset (Natuna, Papua, Timor).
     */
    public function seedPresets(): void
    {
        $data = [
            ['code' => 'natuna', 'nama' => 'Natuna', 'deskripsi' => 'Pulau Natuna, Kalimantan Tengah', 'lat' => -0.20, 'lon' => 112.60, 'zoom' => 10, 'population' => 200000, 'area_km2' => 50.00],
            ['code' => 'papua', 'nama' => 'Papua', 'deskripsi' => 'Papua Barat — wilayah timur Indonesia', 'lat' => -4.00, 'lon' => 137.00, 'zoom' => 10, 'population' => 500000, 'area_km2' => 300000],
            ['code' => 'timor', 'nama' => 'Timor-Leste', 'deskripsi' => 'Timor-Leste — negara otonom di Asia Tenggara', 'lat' => -8.50, 'lon' => 125.50, 'zoom' => 10, 'population' => 500000, 'area_km2' => 30000],
        ];
        foreach ($data as $d) {
            Preset::create($d);
        }
    }
}