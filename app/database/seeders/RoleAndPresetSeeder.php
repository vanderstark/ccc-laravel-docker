<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Preset;
use Illuminate\Database\Seeder;

class RoleAndPresetSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $roles = [
            ['code' => 'admin', 'nama' => 'Administrator', 'deskripsi' => 'Akses penuh: kelola pengguna, data, pengaturan sistem.'],
            ['code' => 'operator', 'nama' => 'Operator', 'deskripsi' => 'Menjalankan simulasi, melihat hasil, mengelola preset.'],
            ['code' => 'viewer', 'nama' => 'Viewer', 'deskripsi' => 'Hanya melihat dashboard dan hasil simulasi.'],
        ];
        foreach ($roles as $r) {
            Role::create($r);
        }

        // Presets skenario khas Indonesia
        $presets = [
            [
                'code' => 'natuna',
                'nama' => 'Kepulauan Natuna',
                'deskripsi' => 'Preset untuk simulasi sengketa maritim & keamanan di Laut Natuna Utara.',
                'lat' => 3.9954, 'lon' => 108.3880, 'zoom' => 8,
                'population' => 85000, 'area_km2' => 2642.0,
                'disaster_types' => ['maritime', 'air', 'combined', 'earthquake', 'flood'],
                'param_overrides' => ['maritime_threat_level' => 0.75, 'air_threat_level' => 0.6],
            ],
            [
                'code' => 'papua',
                'nama' => 'Papua & Papua Barat',
                'deskripsi' => 'Preset untuk simulasi konflik daerah, penegakan keamanan, dan bencana di Tanah Papua.',
                'lat' => -4.2699, 'lon' => 136.0843, 'zoom' => 7,
                'population' => 4300000, 'area_km2' => 420540.0,
                'disaster_types' => ['conflict', 'social_conflict', 'flood', 'landslide', 'earthquake'],
                'param_overrides' => ['conflict_intensity' => 0.6],
            ],
            [
                'code' => 'timor',
                'nama' => 'Timor Timur (NTT)',
                'deskripsi' => 'Preset untuk simulasi operasi militer & bencana di perbatasan Timor.',
                'lat' => -9.5297, 'lon' => 125.0348, 'zoom' => 8,
                'population' => 5300000, 'area_km2' => 47581.0,
                'disaster_types' => ['conflict', 'maritime', 'volcano', 'drought', 'flood'],
                'param_overrides' => ['conflict_intensity' => 0.5],
            ],
        ];
        foreach ($presets as $p) {
            Preset::create($p);
        }
    }
}