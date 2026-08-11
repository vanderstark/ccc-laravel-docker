<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPresetSeeder::class,      // 3 role + 3 preset lama (natuna, papua, timor)
            DisasterTypeSeeder::class,       // 35 tipe bencana
            WarSeeder::class,                // 45 perang sejarah
            OrganizationSeeder::class,       // POLRI, HANKAM, PEMDA, BNPB
            AddCyberSecurityScenarioSeeder::class,  // 4 tipe baru (siber, disinfo, krisis kepercayaan, agenda nasional)
            KurikulumSeeder::class,          // 3 level + 10 mapping
            PresetIndonesiaSeeder::class,    // 34 provinsi + 7 nasional = 41 preset baru
        ]);
    }
}