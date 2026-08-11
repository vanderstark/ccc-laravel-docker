<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddCyberSecurityScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $newTypes = [
            ['code' => 'cyber_attack', 'nama' => 'Serangan Siber', 'kategori' => 'Kejahatan Siber', 'kelompok' => 'Sosial', 'deskripsi' => 'Serangan siber terhadap infrastruktur kritis: hacking, data breach, ransomware, DDoS.', 'param_demo' => json_encode(['cyber_attacks_per_day' => 500, 'attack_duration_hours' => 6])],
            ['code' => 'disinformation', 'nama' => 'Disinformasi / Hoax', 'kategori' => 'Disinformasi', 'kelompok' => 'Sosial', 'deskripsi' => 'Penyebaran informasi keliru/fitnah yang dapat memicu kemusnahan sosial dan krimalitas.', 'param_demo' => json_encode(['social_media_posts' => 50000, 'misinformation_spread_hours' => 12])],
            ['code' => 'public_trust_crisis', 'nama' => 'Krisis Kepercayaan Publik', 'kategori' => 'Krisis Kepercayaan', 'kelompok' => 'Sosial', 'deskripsi' => 'Penurunan drastis kepercayaan publik terhadap institusi akibat skandal, korupsi, atau kegagalan layanan kritis.', 'param_demo' => json_encode(['trust_drop_percent' => 45, 'recovery_days' => 30])],
            ['code' => 'national_security', 'nama' => 'Pengamanan Agenda Nasional', 'kategori' => 'Keamanan Nasional', 'kelompok' => 'Militer', 'deskripsi' => 'Ancaman terhadap integritas negara: ancaman separatis, infiltrasi asing, gangguan Kamtibmas.', 'param_demo' => json_encode(['threat_level' => 'high', 'affected_regions' => 3])],
        ];

        foreach ($newTypes as $t) {
            DB::table('disaster_types')->updateOrInsert(
                ['code' => $t['code']],
                $t
            );
        }
        $this->command?->info('Added 4 scenario: Siber, Disinformasi, Krisis Kepercayaan, Keamanan Nasional');
    }
}
