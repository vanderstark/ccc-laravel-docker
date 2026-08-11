<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Preset;

/**
 * PresetSeeder — Preset wilayah seluruh pulau besar Indonesia
 * Lengkap: 34 provinsi, 11 pulau utama, latitude/longitude, penduduk, luas, tipe bencana khas, paramater spesifik.
 */
class PresetIndonesiaSeeder extends Seeder
{
    public function run(): void
    {
        $presets = [

            // ═══════════════════════════════════════════
            // PULAU SUMATERA (10 provinsi)
            // ═══════════════════════════════════════════

            [
                'code' => 'aceh', 'nama' => 'Aceh',
                'deskripsi' => 'Presets simulasi bencana Tsunami & Gempa Aceh — zona Ring of Fire aktif, bekas Tsunami 2004, konflik GAM (sejarah).',
                'lat' => 4.6951, 'lon' => 96.7494, 'zoom' => 7,
                'population' => 5274871, 'area_km2' => 57956.0,
                'disaster_types' => ['tsunami', 'earthquake', 'flood', 'conflict', 'landslide', 'forest_fire'],
                'param_overrides' => ['tsunami_risk' => 0.95, 'seismic_zone' => 'tinggi', 'tsunami_2004_reference' => true],
            ],
            [
                'code' => 'sumatera_utara', 'nama' => 'Sumatera Utara',
                'deskripsi' => 'Presets Gunungapi Sinabung aktif, banjir bandang Karo, dan kebakaran hutan Riau.',
                'lat' => 2.1154, 'lon' => 99.5451, 'zoom' => 7,
                'population' => 14799361, 'area_km2' => 72983.0,
                'disaster_types' => ['volcano', 'flood', 'landslide', 'forest_fire', 'social_conflict'],
                'param_overrides' => ['sinabung_activity' => 0.8, 'forest_fire_risk' => 0.7],
            ],
            [
                'code' => 'sumatera_barat', 'nama' => 'Sumatera Barat',
                'deskripsi' => 'Presets gempa bumi Megathrust Mentawai, banjir bandang Padang, Longsor Agam.',
                'lat' => -0.7399, 'lon' => 100.8000, 'zoom' => 7,
                'population' => 5534472, 'area_km2' => 42012.0,
                'disaster_types' => ['earthquake', 'tsunami', 'flood', 'landslide', 'flash_flood'],
                'param_overrides' => ['megathrust_risk' => 0.90, 'padang_subduction' => 'tinggi'],
            ],
            [
                'code' => 'riau', 'nama' => 'Riau',
                'deskripsi' => 'Presets Kebakaran Hutan & Lahan (Karhutla) gambut asap, banjir Kampar, konflik agraria.',
                'lat' => 1.7304, 'lon' => 102.4767, 'zoom' => 7,
                'population' => 6394087, 'area_km2' => 87023.0,
                'disaster_types' => ['forest_fire', 'flood', 'environmental_pollution', 'social_conflict', 'drought'],
                'param_overrides' => ['karhutla_risk' => 0.95, 'asap_crossborder' => true, 'gambut_depth' => 'tinggi'],
            ],
            [
                'code' => 'jambi', 'nama' => 'Jambi',
                'deskripsi' => 'Presets kebakaran hutan gambut, banjir Sungai Batang Hari, longsor.',
                'lat' => -1.4852, 'lon' => 102.4381, 'zoom' => 8,
                'population' => 3548228, 'area_km2' => 50058.0,
                'disaster_types' => ['forest_fire', 'flood', 'landslide', 'drought', 'environmental_pollution'],
                'param_overrides' => ['karhutla_risk' => 0.85, 'gambut_area' => 'luas'],
            ],
            [
                'code' => 'sumatera_selatan', 'nama' => 'Sumatera Selatan',
                'deskripsi' => 'Presets banjir Palembang, kebakaran hutan, konflik lokal, pencemaran sungai Musi.',
                'lat' => -3.3194, 'lon' => 103.9144, 'zoom' => 7,
                'population' => 8467432, 'area_km2' => 91592.0,
                'disaster_types' => ['flood', 'forest_fire', 'environmental_pollution', 'social_conflict'],
                'param_overrides' => ['banjir_sungai_musi' => 'tinggi', 'pencemaran_musi' => true],
            ],
            [
                'code' => 'bengkulu', 'nama' => 'Bengkulu',
                'deskripsi' => 'Presets gempa bumi megathrust, tsunami pesisir, kebakaran hutan, longsor.',
                'lat' => -3.8004, 'lon' => 102.2649, 'zoom' => 8,
                'population' => 2010670, 'area_km2' => 19919.0,
                'disaster_types' => ['earthquake', 'tsunami', 'forest_fire', 'landslide'],
                'param_overrides' => ['megathrust_sunda' => 0.88, 'tsunami_pesisir' => true],
            ],
            [
                'code' => 'lampung', 'nama' => 'Lampung',
                'deskripsi' => 'Presets tsunami Selat Sunda (Anak Krakatau), gempa, banjir, konflik agraria.',
                'lat' => -4.5586, 'lon' => 105.4068, 'zoom' => 7,
                'population' => 9007848, 'area_km2' => 34623.0,
                'disaster_types' => ['tsunami', 'earthquake', 'volcano', 'flood', 'social_conflict'],
                'param_overrides' => ['selat_sunda_risk' => 0.85, 'anak_krakatau' => 'aktif'],
            ],
            [
                'code' => 'kepulauan_bangka_belitung', 'nama' => 'Kepulauan Bangka Belitung',
                'deskripsi' => 'Presets konflik tambang timah ilegal, banjir, kebakaran hutan, abrasi pantai.',
                'lat' => -2.7411, 'lon' => 106.4406, 'zoom' => 8,
                'population' => 1455500, 'area_km2' => 16424.0,
                'disaster_types' => ['flood', 'forest_fire', 'coastal_abrasion', 'social_conflict', 'environmental_pollution'],
                'param_overrides' => ['tambang_ilegal' => 'tinggi', 'abrasi_pantai' => 'moderate'],
            ],
            [
                'code' => 'kepulauan_riau', 'nama' => 'Kepulauan Riau',
                'deskripsi' => 'Presets keamanan laut, sengketa maritim, kebakaran, pencemaran laut, smog lintas batas.',
                'lat' => 3.9457, 'lon' => 108.1429, 'zoom' => 8,
                'population' => 2064564, 'area_km2' => 8201.0,
                'disaster_types' => ['maritime', 'forest_fire', 'environmental_pollution', 'strong_wind', 'flooding'],
                'param_overrides' => ['lintas_batang_malaka' => true, 'smog_lintas_batang' => true],
            ],

            // ═══════════════════════════════════════════
            // PULAU JAWA (6 provinsi)
            // ═══════════════════════════════════════════

            [
                'code' => 'dki_jakarta', 'nama' => 'DKI Jakarta',
                'deskripsi' => 'Presets banjir metropolitan, gempa Jakarta, kebakaran permukiman, kerusuhan, demo massal, krisis kepercayaan publik.',
                'lat' => -6.2088, 'lon' => 106.8456, 'zoom' => 11,
                'population' => 10562088, 'area_km2' => 662.0,
                'disaster_types' => ['flood', 'earthquake', 'building_fire', 'social_conflict', 'riot', 'demonstration', 'public_trust_crisis'],
                'param_overrides' => ['banjir_robb' => 0.90, 'kerawanan_urban' => 'tinggi', 'pusat_pemerintahan' => true],
            ],
            [
                'code' => 'jawa_barat', 'nama' => 'Jawa Barat',
                'deskripsi' => 'Presets Gunungapi Galunggung & Papandayan, tanah longsor Bandung, banjir Citarum, konflik sosial.',
                'lat' => -6.9175, 'lon' => 107.6191, 'zoom' => 8,
                'population' => 49938700, 'area_km2' => 35377.0,
                'disaster_types' => ['volcano', 'landslide', 'flood', 'earthquake', 'social_conflict', 'river_flood'],
                'param_overrides' => ['gunung_api_aktif' => 2, 'longsor_bandung' => 'tinggi', 'sungai_citarum' => 'pencemaran'],
            ],
            [
                'code' => 'jawa_tengah', 'nama' => 'Jawa Tengah',
                'deskripsi' => 'Presets Gunungapi Merapi aktif, gempa Jogjakarta, banjir, tanah longsor, tsunami pesisir selatan.',
                'lat' => -7.1510, 'lon' => 110.1403, 'zoom' => 8,
                'population' => 36516035, 'area_km2' => 32800.0,
                'disaster_types' => ['volcano', 'earthquake', 'flood', 'landslide', 'tsunami', 'social_conflict'],
                'param_overrides' => ['merapi_status' => 'siaga', 'gempa_jogja' => 0.75, 'tsunami_southern_coast' => 'moderate'],
            ],
            [
                'code' => 'di_yogyakarta', 'nama' => 'DI Yogyakarta',
                'deskripsi' => 'Presets gempa bumi Yogyakarta, Gunungapi Merapi, banjir, longsor, kerusuhan mahasiswa.',
                'lat' => -7.7956, 'lon' => 110.3695, 'zoom' => 9,
                'population' => 3668738, 'area_km2' => 3133.0,
                'disaster_types' => ['earthquake', 'volcano', 'flood', 'landslide', 'demonstration', 'social_conflict'],
                'param_overrides' => ['merapi_danger' => 0.85, 'gempa_jogja_2006_reference' => true],
            ],
            [
                'code' => 'jawa_timur', 'nama' => 'Jawa Timur',
                'deskripsi' => 'Presets Gunungapi Semeru aktif, Ijen, banjir Sidoarjo, tsunami pesisir, likuifaksi.',
                'lat' => -7.5361, 'lon' => 112.2384, 'zoom' => 8,
                'population' => 40665696, 'area_km2' => 47799.0,
                'disaster_types' => ['volcano', 'earthquake', 'flood', 'liquefaction', 'tsunami', 'social_conflict'],
                'param_overrides' => ['semeru_aktif' => 0.95, 'ijen_belawan' => true, 'sidoarjo_lumpur' => true],
            ],
            [
                'code' => 'banten', 'nama' => 'Banten',
                'deskripsi' => 'Presets tsunami Selat Sunda (Krakatau 2018), gempa, banjir, konflik sosial, kebakaran hutan.',
                'lat' => -6.4058, 'lon' => 106.0640, 'zoom' => 8,
                'population' => 12689143, 'area_km2' => 9662.0,
                'disaster_types' => ['tsunami', 'earthquake', 'flood', 'social_conflict', 'forest_fire'],
                'param_overrides' => ['selat_sunda_risk' => 0.85, 'tsunami_2018_reference' => true],
            ],

            // ═══════════════════════════════════════════
            // PULAU KALIMANTAN (5 provinsi)
            // ═══════════════════════════════════════════

            [
                'code' => 'kalimantan_barat', 'nama' => 'Kalimantan Barat',
                'deskripsi' => 'Presets kebakaran hutan & lahan gambut, banjir Kapuas, konflik agraria Dayak-Madura.',
                'lat' => -0.2788, 'lon' => 111.4752, 'zoom' => 7,
                'population' => 5414390, 'area_km2' => 147307.0,
                'disaster_types' => ['forest_fire', 'flood', 'social_conflict', 'drought', 'environmental_pollution'],
                'param_overrides' => ['karhutla_risk' => 0.92, 'gambut_kalimantan' => 'luas'],
            ],
            [
                'code' => 'kalimantan_tengah', 'nama' => 'Kalimantan Tengah',
                'deskripsi' => 'Presets kebakaran hutan gambut, banjir, konflik lahan, pencemaran sungai.',
                'lat' => -1.6397, 'lon' => 113.3824, 'zoom' => 7,
                'population' => 2669969, 'area_km2' => 153564.0,
                'disaster_types' => ['forest_fire', 'flood', 'social_conflict', 'drought', 'environmental_pollution'],
                'param_overrides' => ['karhutla_risk' => 0.88, 'sungai_kahayan' => 'banjir'],
            ],
            [
                'code' => 'kalimantan_selatan', 'nama' => 'Kalimantan Selatan',
                'deskripsi' => 'Presets banjir Bandarmasih, longsor, kebakaran hutan, konflik tambang batubara.',
                'lat' => -3.0926, 'lon' => 115.2838, 'zoom' => 8,
                'population' => 4073584, 'area_km2' => 38744.0,
                'disaster_types' => ['flood', 'landslide', 'forest_fire', 'social_conflict', 'environmental_pollution'],
                'param_overrides' => ['banjir_bandarmasih' => 'tinggi', 'tambang_batubara' => 'aktif'],
            ],
            [
                'code' => 'kalimantan_timur', 'nama' => 'Kalimantan Timur',
                'deskripsi' => 'Presets kebakaran hutan, banjir, IKN Nusantara (perencanaan), konflik lahan, pencemaran laut.',
                'lat' => 1.6407, 'lon' => 116.4194, 'zoom' => 7,
                'population' => 3766039, 'area_km2' => 129066.0,
                'disaster_types' => ['forest_fire', 'flood', 'social_conflict', 'environmental_pollution', 'maritime'],
                'param_overrides' => ['ikn_area' => true, 'karhutla_risk' => 0.82, 'pencemaran_mahakam' => true],
            ],
            [
                'code' => 'kalimantan_utara', 'nama' => 'Kalimantan Utara',
                'deskripsi' => 'Presets kebakaran hutan, banjir, perbatasan Malaysia, konflik lahan, pencemaran sungai.',
                'lat' => 3.0731, 'lon' => 116.0413, 'zoom' => 7,
                'population' => 701814, 'area_km2' => 75467.0,
                'disaster_types' => ['forest_fire', 'flood', 'social_conflict', 'maritime', 'environmental_pollution'],
                'param_overrides' => ['perbatasan_malaysia' => true, 'karhutla_risk' => 0.75],
            ],

            // ═══════════════════════════════════════════
            // PULAU SULAWESI (6 provinsi)
            // ═══════════════════════════════════════════

            [
                'code' => 'sulawesi_utara', 'nama' => 'Sulawesi Utara',
                'deskripsi' => 'Presets Gunungapi Lokon & Soputan aktif, gempa, tsunami Sangihe, banjir.',
                'lat' => 0.6247, 'lon' => 123.9750, 'zoom' => 8,
                'population' => 2621923, 'area_km2' => 13851.0,
                'disaster_types' => ['volcano', 'earthquake', 'tsunami', 'flood', 'strong_wind'],
                'param_overrides' => ['gunung_lokon' => 'aktif', 'sangih_trench' => 'tinggi'],
            ],
            [
                'code' => 'sulawesi_tengah', 'nama' => 'Sulawesi Tengah',
                'deskripsi' => 'Presets gempa & tsunami Palu 2018, likuifaksi, longsor, banjir.',
                'lat' => -1.4300, 'lon' => 121.4456, 'zoom' => 7,
                'population' => 2985734, 'area_km2' => 61841.0,
                'disaster_types' => ['earthquake', 'tsunami', 'liquefaction', 'landslide', 'flood', 'volcano'],
                'param_overrides' => ['palu_koro_fault' => 0.92, 'likuifaksi_sigi' => 'tinggi', 'palu_2018_reference' => true],
            ],
            [
                'code' => 'sulawesi_selatan', 'nama' => 'Sulawesi Selatan',
                'deskripsi' => 'Presets banjir Makassar, gempa, longsor, kebakaran, konflik sosial.',
                'lat' => -3.6688, 'lon' => 119.9741, 'zoom' => 8,
                'population' => 9073509, 'area_km2' => 46717.0,
                'disaster_types' => ['flood', 'earthquake', 'landslide', 'social_conflict', 'building_fire'],
                'param_overrides' => ['banjir_makassar' => 'moderate', 'longsor_sulsel' => 0.65],
            ],
            [
                'code' => 'sulawesi_tenggara', 'nama' => 'Sulawesi Tenggara',
                'deskripsi' => 'Presets gempa bumi, banjir, longsor, konflik tambang nikel, pencemaran laut.',
                'lat' => -4.1449, 'lon' => 122.1748, 'zoom' => 8,
                'population' => 2624875, 'area_km2' => 38067.0,
                'disaster_types' => ['earthquake', 'flood', 'landslide', 'social_conflict', 'environmental_pollution', 'maritime'],
                'param_overrides' => ['tambang_nikel' => 'luas', 'pencemaran_laut' => 'tinggi'],
            ],
            [
                'code' => 'gorontalo', 'nama' => 'Gorontalo',
                'deskripsi' => 'Presets gempa bumi, banjir, tanah longsor, konflik lokal.',
                'lat' => 0.6999, 'lon' => 122.4467, 'zoom' => 8,
                'population' => 1171681, 'area_km2' => 11257.0,
                'disaster_types' => ['earthquake', 'flood', 'landslide', 'social_conflict'],
                'param_overrides' => ['gempa_gorontalo' => 'moderate'],
            ],
            [
                'code' => 'sulawesi_utara_barat', 'nama' => 'Sulawesi Utara Barat (Sangihe & Talaud)',
                'deskripsi' => 'Presets perbatasan laut Filipina, gempa Sangihe, tsunami, konflik maritim.',
                'lat' => 3.5053, 'lon' => 125.5000, 'zoom' => 9,
                'population' => 232002, 'area_km2' => 1023.0,
                'disaster_types' => ['earthquake', 'tsunami', 'maritime', 'strong_wind'],
                'param_overrides' => ['sangih_trench' => 0.88, 'perbatasan_filipina' => true],
            ],

            // ═══════════════════════════════════════════
            // BALI & NUSA TENGGARA (8 provinsi)
            // ═══════════════════════════════════════════

            [
                'code' => 'bali', 'nama' => 'Bali',
                'deskripsi' => 'Presets Gunungapi Agung aktif, gempa Lombok, tsunami, banjir, kerusuhan pariwisata.',
                'lat' => -8.3405, 'lon' => 115.0920, 'zoom' => 9,
                'population' => 4317404, 'area_km2' => 5780.0,
                'disaster_types' => ['volcano', 'earthquake', 'tsunami', 'flood', 'demonstration', 'social_conflict'],
                'param_overrides' => ['gunung_agung' => 'siaga', 'gempa_lombok_2018' => true, 'pariwisata_pusat' => true],
            ],
            [
                'code' => 'nusa_tenggara_barat', 'nama' => 'Nusa Tenggara Barat',
                'deskripsi' => 'Presets gempa Lombok, Gunungapi Rinjani, banjir, longsor, konflik sosial.',
                'lat' => -8.6529, 'lon' => 117.3616, 'zoom' => 8,
                'population' => 5320092, 'area_km2' => 18572.0,
                'disaster_types' => ['earthquake', 'volcano', 'flood', 'landslide', 'social_conflict'],
                'param_overrides' => ['rinjani_aktif' => true, 'gempa_lombok' => 'tinggi'],
            ],
            [
                'code' => 'nusa_tenggara_timur', 'nama' => 'Nusa Tenggara Timur',
                'deskripsi' => 'Presets banjir Flores, gempa, longsor, konflik perbatasan Timor Leste.',
                'lat' => -8.6574, 'lon' => 121.0794, 'zoom' => 8,
                'population' => 5325566, 'area_km2' => 47876.0,
                'disaster_types' => ['flood', 'earthquake', 'landslide', 'conflict', 'volcano', 'drought'],
                'param_overrides' => ['perbatasan_timor' => true, 'flooding_flores' => 'tinggi'],
            ],

            // ═══════════════════════════════════════════
            // MALUKU & PAPUA (9 provinsi)
            // ═══════════════════════════════════════════

            [
                'code' => 'maluku', 'nama' => 'Maluku',
                'deskripsi' => 'Presets gempa bumi Ambon, tsunami, konflik sosial Maluku, banjir, longsor.',
                'lat' => -3.2384, 'lon' => 130.1453, 'zoom' => 8,
                'population' => 1848923, 'area_km2' => 46914.0,
                'disaster_types' => ['earthquake', 'tsunami', 'social_conflict', 'flood', 'landslide'],
                'param_overrides' => ['gempa_ambon' => 0.82, 'konflik_maluku_sejarah' => true],
            ],
            [
                'code' => 'maluku_utara', 'nama' => 'Maluku Utara',
                'deskripsi' => 'Presets gempa Halmahera, tsunami, banjir, konflik tambang, perbatasan laut.',
                'lat' => 1.5710, 'lon' => 127.8088, 'zoom' => 8,
                'population' => 1282937, 'area_km2' => 31982.0,
                'disaster_types' => ['earthquake', 'tsunami', 'flood', 'social_conflict', 'maritime'],
                'param_overrides' => ['halmahera_fault' => 'tinggi', 'perbatasan_laut' => true],
            ],
            [
                'code' => 'papua', 'nama' => 'Papua',
                'deskripsi' => 'Presets konflik OPM, banjir, longsor, perbatasan PNG, operasi keamanan.',
                'lat' => -4.2699, 'lon' => 138.0843, 'zoom' => 7,
                'population' => 1258682, 'area_km2' => 319036.0,
                'disaster_types' => ['conflict', 'flood', 'landslide', 'social_conflict', 'earthquake'],
                'param_overrides' => ['konflik_opm' => 0.65, 'perbatasan_png' => true, 'akses_sulit' => true],
            ],
            [
                'code' => 'papua_barat', 'nama' => 'Papua Barat',
                'deskripsi' => 'Presets konflik daerah, banjir, longsor, keamanan hutan, perbatasan laut.',
                'lat' => -1.3361, 'lon' => 133.1747, 'zoom' => 8,
                'population' => 1134068, 'area_km2' => 102946.0,
                'disaster_types' => ['conflict', 'flood', 'landslide', 'forest_fire', 'social_conflict'],
                'param_overrides' => ['konflik_papua_barat' => 0.60, 'hutan_untouched' => true],
            ],
            [
                'code' => 'papua_pegunungan', 'nama' => 'Papua Pegunungan',
                'deskripsi' => 'Presets gempa Jayawijaya, longsor, banjir, konflik, akses sulit.',
                'lat' => -4.0681, 'lon' => 137.1343, 'zoom' => 8,
                'population' => 554413, 'area_km2' => 51213.0,
                'disaster_types' => ['earthquake', 'landslide', 'flood', 'conflict', 'social_conflict'],
                'param_overrides' => ['gempa_pegunungan' => 'moderate', 'akses_sulit' => true, 'ketinggian' => 'tinggi'],
            ],
            [
                'code' => 'papua_selatan', 'nama' => 'Papua Selatan',
                'deskripsi' => 'Presets banjir, longsor, konflik, perbatasan PNG laut, pencemaran laut.',
                'lat' => -6.3173, 'lon' => 139.5313, 'zoom' => 8,
                'population' => 430289, 'area_km2' => 117508.0,
                'disaster_types' => ['flood', 'landslide', 'conflict', 'maritime', 'environmental_pollution'],
                'param_overrides' => ['perbatasan_laut_png' => true, 'pencemaran_laut' => true],
            ],
            [
                'code' => 'papua_tengah', 'nama' => 'Papua Tengah',
                'deskripsi' => 'Presets banjir, longsor, konflik, akses terbatas.',
                'lat' => -3.3975, 'lon' => 135.4448, 'zoom' => 8,
                'population' => 1285644, 'area_km2' => 62394.0,
                'disaster_types' => ['flood', 'landslide', 'conflict', 'earthquake'],
                'param_overrides' => ['akses_terbatas' => true],
            ],
            [
                'code' => 'papua_barat_daya', 'nama' => 'Papua Barat Daya',
                'deskripsi' => 'Presets banjir, longsor, konflik lokal, pencemaran.',
                'lat' => -1.3855, 'lon' => 133.1013, 'zoom' => 8,
                'population' => 563682, 'area_km2' => 16683.0,
                'disaster_types' => ['flood', 'landslide', 'social_conflict', 'environmental_pollution'],
                'param_overrides' => ['konflik_lokal' => 'moderate'],
            ],
            [
                'code' => 'papua_pesisir', 'nama' => 'Papua Pesisir & Kepulauan',
                'deskripsi' => 'Presets tsunami pesisir, gempa laut, banjir, konflik maritim.',
                'lat' => -2.5512, 'lon' => 139.3231, 'zoom' => 8,
                'population' => 449415, 'area_km2' => 33904.0,
                'disaster_types' => ['tsunami', 'earthquake', 'maritime', 'flood', 'social_conflict'],
                'param_overrides' => ['tsunami_pesisir' => 0.80, 'maritime_area' => true],
            ],

            // ═══════════════════════════════════════════
            // SCENARIO KHUSUS (NASIONAL / MULTIPULAU)
            // ═══════════════════════════════════════════

            [
                'code' => 'nasional_pandemi', 'nama' => 'Nasional — Pandemi Nasional',
                'deskripsi' => 'Presets pandemi skala nasional: isolasi regional, kesehatan, logistik, ekonomi.',
                'lat' => -2.5489, 'lon' => 118.0149, 'zoom' => 5,
                'population' => 275501339, 'area_km2' => 1904569.0,
                'disaster_types' => ['pandemic', 'disease_outbreak', 'social_conflict', 'public_trust_crisis'],
                'param_overrides' => ['pandemic_scale' => 'nasional', 'kesehatan_prioritas' => true],
            ],
            [
                'code' => 'nasional_gempa_gunung', 'nama' => 'Nasional — Gempa & Gunung Api',
                'deskripsi' => 'Presets skenario gempa berantai + erupsi multi-gunungapi — ancaman Ring of Fire.',
                'lat' => -5.0, 'lon' => 115.0, 'zoom' => 5,
                'population' => 275501339, 'area_km2' => 1904569.0,
                'disaster_types' => ['earthquake', 'tsunami', 'volcano', 'liquefaction', 'landslide'],
                'param_overrides' => ['ring_of_fire_risk' => 'tinggi', 'multi_volcano' => true, 'megathrust_sunda' => 0.85],
            ],
            [
                'code' => 'nasional_karhutla', 'nama' => 'Nasional — Karhutla & Asap',
                'deskripsi' => 'Presets Kebakaran Hutan & Lahan lintas pulau, asap lintas batang negara, kesehatan masyarakat.',
                'lat' => 0.0, 'lon' => 114.0, 'zoom' => 5,
                'population' => 275501339, 'area_km2' => 1904569.0,
                'disaster_types' => ['forest_fire', 'disease_outbreak', 'environmental_pollution', 'social_conflict'],
                'param_overrides' => ['karhutla_nasional' => true, 'asap_lintas_batang' => true, 'gambut_risk' => 0.90],
            ],
            [
                'code' => 'nasional_banjir_bandang', 'nama' => 'Nasional — Banjir Bandang Multilokasi',
                'deskripsi' => 'Presets banjir bandang simultan di beberapa pulau — logistik & evakuasi nasional.',
                'lat' => -2.0, 'lon' => 117.0, 'zoom' => 5,
                'population' => 275501339, 'area_km2' => 1904569.0,
                'disaster_types' => ['flood', 'flash_flood', 'landslide', 'social_conflict'],
                'param_overrides' => ['banjir_multilokasi' => true, 'logistik_nasional' => true],
            ],
            [
                'code' => 'nasional_terorisme', 'nama' => 'Nasional — Terorisme & Keamanan',
                'deskripsi' => 'Presets ancaman terorisme lintas daerah: bom, penyanderaan, serangan siber — operasi gabungan.',
                'lat' => -6.2088, 'lon' => 106.8456, 'zoom' => 5,
                'population' => 275501339, 'area_km2' => 1904569.0,
                'disaster_types' => ['terrorism', 'cyber_attack', 'conflict', 'combined', 'national_security'],
                'param_overrides' => ['ancaman_nasional' => true, 'operasi_gabungan' => true, 'cyber_dimensi' => true],
            ],
            [
                'code' => 'nasional_disinformasi', 'nama' => 'Nasional — Disinformasi & Krisis Kepercayaan',
                'deskripsi' => 'Presets hoaks massif lintas media, krisis kepercayaan publik, counter-narrative.',
                'lat' => -6.2088, 'lon' => 106.8456, 'zoom' => 5,
                'population' => 275501339, 'area_km2' => 1904569.0,
                'disaster_types' => ['disinformation', 'public_trust_crisis', 'social_conflict', 'demonstration'],
                'param_overrides' => ['disinformasi_nasional' => true, 'media_social_saturasi' => 0.95],
            ],
        ];

        $inserted = 0;
        foreach ($presets as $p) {
            if (!Preset::where('code', $p['code'])->exists()) {
                Preset::create($p);
                $inserted++;
            }
        }
        echo "Preset baru di-insert: {$inserted}\n";
        echo "Total preset: " . Preset::count() . "\n";
    }
}
