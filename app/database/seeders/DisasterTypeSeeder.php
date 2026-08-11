<?php

namespace Database\Seeders;

use App\Models\DisasterType;
use Illuminate\Database\Seeder;

class DisasterTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Geologis
            ['code' => 'earthquake', 'nama' => 'Gempa Bumi', 'kategori' => 'Bencana Alam Geologis', 'kelompok' => 'Alam', 'deskripsi' => 'Gempa bumi berdenyut yang disebabkan oleh pergerakan platen.', 'param_demo' => ['earthquake_magnitude' => 6.5, 'earthquake_depth_km' => 20]],
            ['code' => 'tsunami', 'nama' => 'Tsunami', 'kategori' => 'Bencana Alam Geologis', 'kelompok' => 'Alam', 'deskripsi' => 'Gelombang laut raksasa akibat gempa bumi di dasar laut.', 'param_demo' => ['tsunami_wave_height_m' => 5, 'tsunami_epicenter_distance_km' => 50]],
            ['code' => 'volcano', 'nama' => 'Letusan Gunung Api', 'kategori' => 'Bencana Alam Geologis', 'kelompok' => 'Alam', 'deskripsi' => 'Letusan magma, abu, dan gas dari gunung api.', 'param_demo' => ['volcano_vei' => 4, 'volcano_eruption_distance_km' => 10]],
            ['code' => 'landslide', 'nama' => 'Tanah Longsor', 'kategori' => 'Bencana Alam Geologis', 'kelompok' => 'Alam', 'deskripsi' => 'Gerakan massa tanah/batu menurun lereng akibat gravitasi.', 'param_demo' => ['severity_scale' => 0.6]],
            ['code' => 'liquefaction', 'nama' => 'Likuifaksi', 'kategori' => 'Bencana Alam Geologis', 'kelompok' => 'Alam', 'deskripsi' => 'Tanah jenuh air kehilangan kekuatan dukungan saat diguncang.', 'param_demo' => ['severity_scale' => 0.6]],

            // Hidrometeorologi
            ['code' => 'flood', 'nama' => 'Banjir', 'kategori' => 'Bencana Alam Hidrometeorologi', 'kelompok' => 'Alam', 'deskripsi' => 'Genangan air meluap ke area pemukiman akibat curah hujan tinggi.', 'param_demo' => ['flood_depth_m' => 1.5, 'flood_duration_hours' => 24]],
            ['code' => 'flash_flood', 'nama' => 'Banjir Bandang', 'kategori' => 'Bencana Alam Hidrometeorologi', 'kelompok' => 'Alam', 'deskripsi' => 'Banjir mendadak berkecepatan tinggi dari lereng gunung.', 'param_demo' => ['severity_scale' => 0.6]],
            ['code' => 'drought', 'nama' => 'Kekeringan', 'kategori' => 'Bencana Alam Hidrometeorologi', 'kelompok' => 'Alam', 'deskripsi' => 'Kelanggaran air jangka panjang akibat curah hujan di bawah normal.', 'param_demo' => ['severity_scale' => 0.5]],
            ['code' => 'tornado', 'nama' => 'Angin Puting Beliung', 'kategori' => 'Bencana Alam Hidrometeorologi', 'kelompok' => 'Alam', 'deskripsi' => 'Putaran udara cepat merusak bangunan di jalurnya.', 'param_demo' => ['severity_scale' => 0.5]],
            ['code' => 'strong_wind', 'nama' => 'Angin Kencang', 'kategori' => 'Bencana Alam Hidrometeorologi', 'kelompok' => 'Alam', 'deskripsi' => 'Angin kencang merusak atap, pohon, dan infrastruktur ringan.', 'param_demo' => ['severity_scale' => 0.4]],
            ['code' => 'coastal_abrasion', 'nama' => 'Abrasi Pantai', 'kategori' => 'Bencana Alam Hidrometeorologi', 'kelompok' => 'Alam', 'deskripsi' => 'Erosi pantai akibat gelombang & arus laut yang menghantam tebing.', 'param_demo' => ['severity_scale' => 0.4]],
            ['code' => 'extreme_wave', 'nama' => 'Gelombang Ekstrem', 'kategori' => 'Bencana Alam Hidrometeorologi', 'kelompok' => 'Alam', 'deskripsi' => 'Gelombang laut tinggi berlebihan akibat cuaca buruk.', 'param_demo' => ['severity_scale' => 0.5]],

            // Biologi
            ['code' => 'disease_outbreak', 'nama' => 'Wabah Penyakit', 'kategori' => 'Bencana Alam Biologi', 'kelompok' => 'Alam', 'deskripsi' => 'Munculnya kasus penyakit menular melebihi ambang normal.', 'param_demo' => ['severity_scale' => 0.4]],
            ['code' => 'pandemic', 'nama' => 'Pandemi', 'kategori' => 'Bencana Alam Biologi', 'kelompok' => 'Alam', 'deskripsi' => 'Wabah penyakit menular skala global/nasional.', 'param_demo' => ['severity_scale' => 0.6]],

            // Kebakaran
            ['code' => 'forest_fire', 'nama' => 'Kebakaran Hutan dan Lahan', 'kategori' => 'Kebakaran', 'kelompok' => 'Alam', 'deskripsi' => 'Kebakaran vegetasi luas di hutan, lahan gambut, atau perkebunan.', 'param_demo' => ['fire_area_ha' => 2000, 'fire_wind_speed_kmh' => 25, 'fire_fuel_type' => 'peat']],
            ['code' => 'building_fire', 'nama' => 'Kebakaran Gedung', 'kategori' => 'Kebakaran', 'kelompok' => 'Non-Alam', 'deskripsi' => 'Kebakaran struktur gedung bertingkat/komersial.', 'param_demo' => ['severity_scale' => 0.4]],
            ['code' => 'settlement_fire', 'nama' => 'Kebakaran Permukiman', 'kategori' => 'Kebakaran', 'kelompok' => 'Non-Alam', 'deskripsi' => 'Kebakaran menyebar di padatan permukiman kumuh.', 'param_demo' => ['severity_scale' => 0.5]],

            // Non-Alam
            ['code' => 'transport_accident', 'nama' => 'Kecelakaan Transportasi', 'kategori' => 'Bencana Non-Alam', 'kelompok' => 'Non-Alam', 'deskripsi' => 'Tabrakan/kejutan kendaraan darat/laut/udara menimbulkan korban massal.', 'param_demo' => ['severity_scale' => 0.3]],
            ['code' => 'tech_failure', 'nama' => 'Kegagalan Teknologi', 'kategori' => 'Bencana Non-Alam', 'kelompok' => 'Non-Alam', 'deskripsi' => 'Kegagalan sistem vital (listrik, komunikasi, nuklir, kimia).', 'param_demo' => ['severity_scale' => 0.3]],
            ['code' => 'environmental_pollution', 'nama' => 'Pencemaran Lingkungan', 'kategori' => 'Bencana Non-Alam', 'kelompok' => 'Non-Alam', 'deskripsi' => 'Pencemaran udara/air/tanah bahan berbahaya skala luas.', 'param_demo' => ['severity_scale' => 0.4]],
            ['code' => 'toxic_gas', 'nama' => 'Gas Beracun', 'kategori' => 'Bencana Non-Alam', 'kelompok' => 'Non-Alam', 'deskripsi' => 'Peledakan/kebocoran gas toksik di area industri/pertambangan.', 'param_demo' => ['severity_scale' => 0.5]],
            ['code' => 'construction_failure', 'nama' => 'Kegagalan Konstruksi', 'kategori' => 'Bencana Non-Alam', 'kelompok' => 'Non-Alam', 'deskripsi' => 'Runtuhnya bendungan, jembatan, gedung tinggi akibat desain/eksekusi buruk.', 'param_demo' => ['severity_scale' => 0.4]],

            // Sosial
            ['code' => 'social_conflict', 'nama' => 'Konflik Sosial', 'kategori' => 'Bencana Sosial', 'kelompok' => 'Sosial', 'deskripsi' => 'Benturan antar kelompok etnis/agama/kelas sosial.', 'param_demo' => ['severity_scale' => 0.5]],
            ['code' => 'riot', 'nama' => 'Kerusuhan', 'kategori' => 'Bencana Sosial', 'kelompok' => 'Sosial', 'deskripsi' => 'Kekerasan massa tidak terencana di area perkotaan.', 'param_demo' => ['severity_scale' => 0.5]],
            ['code' => 'terrorism', 'nama' => 'Terorisme', 'kategori' => 'Bencana Sosial', 'kelompok' => 'Sosial', 'deskripsi' => 'Serangan kekerasan terencana menakuti-nakuti masyarakat.', 'param_demo' => ['severity_scale' => 0.6]],
            ['code' => 'mass_violence', 'nama' => 'Aksi Kekerasan Massal', 'kategori' => 'Bencana Sosial', 'kelompok' => 'Sosial', 'deskripsi' => 'Kekerasan skala besar antar massa/kelompok.', 'param_demo' => ['severity_scale' => 0.5]],
            ['code' => 'demonstration', 'nama' => 'Demo', 'kategori' => 'Bencana Sosial', 'kelompok' => 'Sosial', 'deskripsi' => 'Aksi unjuk rasa yang berpotensi eskalasi kekerasan.', 'param_demo' => ['severity_scale' => 0.3]],

            // Militer
            ['code' => 'conflict', 'nama' => 'Konflik Darat', 'kategori' => 'Operasi Militer', 'kelompok' => 'Militer', 'deskripsi' => 'Operasi darat konvensional/gerilya/pemberontakan.', 'param_demo' => ['conflict_intensity' => 0.7, 'conflict_type' => 'insurgency']],
            ['code' => 'maritime', 'nama' => 'Konflik Laut', 'kategori' => 'Operasi Militer', 'kelompok' => 'Militer', 'deskripsi' => 'Operasi maritim: sengketa ZEE, blokade, amfibi, pembajakan.', 'param_demo' => ['maritime_threat_level' => 0.8, 'enemy_naval_units' => 5]],
            ['code' => 'air', 'nama' => 'Konflik Udara', 'kategori' => 'Operasi Militer', 'kelompok' => 'Militer', 'deskripsi' => 'Operasi udara: intrusi, pertahanan udara, serangan udara, no-fly zone.', 'param_demo' => ['air_threat_level' => 0.7, 'enemy_aircraft' => 6]],
            ['code' => 'combined', 'nama' => 'Operasi Gabungan Tri-Matra', 'kategori' => 'Operasi Militer', 'kelompok' => 'Militer', 'deskripsi' => 'Operasi gabungan darat + laut + udara (Tri Matra TNI).', 'param_demo' => ['conflict_intensity' => 0.6, 'maritime_threat_level' => 0.5, 'air_threat_level' => 0.4]],
        ];

        foreach ($data as $d) {
            DisasterType::create($d);
        }
    }
}
