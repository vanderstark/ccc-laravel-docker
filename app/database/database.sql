/* ==============================================
   Crisis Command Center — Database Seed MySQL
   Auto-generated dari SQLite seed data
   Jalankan: mysql -u root -p ccc_database < database.sql
   ============================================== */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

-- Table: disaster_types (31 rows)
DROP TABLE IF EXISTS `disaster_types`;
CREATE TABLE `disaster_types` (
`id` bigint   AUTO_INCREMENT PRIMARY KEY,
`code` varchar(255) NOT NULL DEFAULT 'None',
`nama` varchar(255) NOT NULL DEFAULT 'None',
`kategori` varchar(255) NOT NULL DEFAULT 'None',
`kelompok` varchar(255) NOT NULL DEFAULT 'None',
`deskripsi` text  DEFAULT 'None',
`param_demo` text  DEFAULT 'None',
`created_at` varchar(255)  DEFAULT 'None',
`updated_at` varchar(255)  DEFAULT 'None',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `disaster_types` (`id`,`code`,`nama`,`kategori`,`kelompok`,`deskripsi`,`param_demo`,`created_at`,`updated_at`) VALUES
  (1, 'earthquake', 'Gempa Bumi', 'Bencana Alam Geologis', 'Alam', 'Gempa bumi berdenyut yang disebabkan oleh pergerakan platen.', '{"earthquake_magnitude":6.5,"earthquake_depth_km":20}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (2, 'tsunami', 'Tsunami', 'Bencana Alam Geologis', 'Alam', 'Gelombang laut raksasa akibat gempa bumi di dasar laut.', '{"tsunami_wave_height_m":5,"tsunami_epicenter_distance_km":50}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (3, 'volcano', 'Letusan Gunung Api', 'Bencana Alam Geologis', 'Alam', 'Letusan magma, abu, dan gas dari gunung api.', '{"volcano_vei":4,"volcano_eruption_distance_km":10}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (4, 'landslide', 'Tanah Longsor', 'Bencana Alam Geologis', 'Alam', 'Gerakan massa tanah/batu menurun lereng akibat gravitasi.', '{"severity_scale":0.6}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (5, 'liquefaction', 'Likuifaksi', 'Bencana Alam Geologis', 'Alam', 'Tanah jenuh air kehilangan kekuatan dukungan saat diguncang.', '{"severity_scale":0.6}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (6, 'flood', 'Banjir', 'Bencana Alam Hidrometeorologi', 'Alam', 'Genangan air meluap ke area pemukiman akibat curah hujan tinggi.', '{"flood_depth_m":1.5,"flood_duration_hours":24}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (7, 'flash_flood', 'Banjir Bandang', 'Bencana Alam Hidrometeorologi', 'Alam', 'Banjir mendadak berkecepatan tinggi dari lereng gunung.', '{"severity_scale":0.6}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (8, 'drought', 'Kekeringan', 'Bencana Alam Hidrometeorologi', 'Alam', 'Kelanggaran air jangka panjang akibat curah hujan di bawah normal.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (9, 'tornado', 'Angin Puting Beliung', 'Bencana Alam Hidrometeorologi', 'Alam', 'Putaran udara cepat merusak bangunan di jalurnya.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (10, 'strong_wind', 'Angin Kencang', 'Bencana Alam Hidrometeorologi', 'Alam', 'Angin kencang merusak atap, pohon, dan infrastruktur ringan.', '{"severity_scale":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (11, 'coastal_abrasion', 'Abrasi Pantai', 'Bencana Alam Hidrometeorologi', 'Alam', 'Erosi pantai akibat gelombang & arus laut yang menghantam tebing.', '{"severity_scale":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (12, 'extreme_wave', 'Gelombang Ekstrem', 'Bencana Alam Hidrometeorologi', 'Alam', 'Gelombang laut tinggi berlebihan akibat cuaca buruk.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (13, 'disease_outbreak', 'Wabah Penyakit', 'Bencana Alam Biologi', 'Alam', 'Munculnya kasus penyakit menular melebihi ambang normal.', '{"severity_scale":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (14, 'pandemic', 'Pandemi', 'Bencana Alam Biologi', 'Alam', 'Wabah penyakit menular skala global/nasional.', '{"severity_scale":0.6}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (15, 'forest_fire', 'Kebakaran Hutan dan Lahan', 'Kebakaran', 'Alam', 'Kebakaran vegetasi luas di hutan, lahan gambut, atau perkebunan.', '{"fire_area_ha":2000,"fire_wind_speed_kmh":25,"fire_fuel_type":"peat"}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (16, 'building_fire', 'Kebakaran Gedung', 'Kebakaran', 'Non-Alam', 'Kebakaran struktur gedung bertingkat/komersial.', '{"severity_scale":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (17, 'settlement_fire', 'Kebakaran Permukiman', 'Kebakaran', 'Non-Alam', 'Kebakaran menyebar di padatan permukiman kumuh.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (18, 'transport_accident', 'Kecelakaan Transportasi', 'Bencana Non-Alam', 'Non-Alam', 'Tabrakan/kejutan kendaraan darat/laut/udara menimbulkan korban massal.', '{"severity_scale":0.3}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (19, 'tech_failure', 'Kegagalan Teknologi', 'Bencana Non-Alam', 'Non-Alam', 'Kegagalan sistem vital (listrik, komunikasi, nuklir, kimia).', '{"severity_scale":0.3}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (20, 'environmental_pollution', 'Pencemaran Lingkungan', 'Bencana Non-Alam', 'Non-Alam', 'Pencemaran udara/air/tanah bahan berbahaya skala luas.', '{"severity_scale":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (21, 'toxic_gas', 'Gas Beracun', 'Bencana Non-Alam', 'Non-Alam', 'Peledakan/kebocoran gas toksik di area industri/pertambangan.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (22, 'construction_failure', 'Kegagalan Konstruksi', 'Bencana Non-Alam', 'Non-Alam', 'Runtuhnya bendungan, jembatan, gedung tinggi akibat desain/eksekusi buruk.', '{"severity_scale":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (23, 'social_conflict', 'Konflik Sosial', 'Bencana Sosial', 'Sosial', 'Benturan antar kelompok etnis/agama/kelas sosial.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (24, 'riot', 'Kerusuhan', 'Bencana Sosial', 'Sosial', 'Kekerasan massa tidak terencana di area perkotaan.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (25, 'terrorism', 'Terorisme', 'Bencana Sosial', 'Sosial', 'Serangan kekerasan terencana menakuti-nakuti masyarakat.', '{"severity_scale":0.6}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (26, 'mass_violence', 'Aksi Kekerasan Massal', 'Bencana Sosial', 'Sosial', 'Kekerasan skala besar antar massa/kelompok.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (27, 'demonstration', 'Demo', 'Bencana Sosial', 'Sosial', 'Aksi unjuk rasa yang berpotensi eskalasi kekerasan.', '{"severity_scale":0.3}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (28, 'conflict', 'Konflik Darat', 'Operasi Militer', 'Militer', 'Operasi darat konvensional/gerilya/pemberontakan.', '{"conflict_intensity":0.7,"conflict_type":"insurgency"}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (29, 'maritime', 'Konflik Laut', 'Operasi Militer', 'Militer', 'Operasi maritim: sengketa ZEE, blokade, amfibi, pembajakan.', '{"maritime_threat_level":0.8,"enemy_naval_units":5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (30, 'air', 'Konflik Udara', 'Operasi Militer', 'Militer', 'Operasi udara: intrusi, pertahanan udara, serangan udara, no-fly zone.', '{"air_threat_level":0.7,"enemy_aircraft":6}', '2026-08-11 07:39:05', '2026-08-11 07:39:05'),
  (31, 'combined', 'Operasi Gabungan Tri-Matra', 'Operasi Militer', 'Militer', 'Operasi gabungan darat + laut + udara (Tri Matra TNI).', '{"conflict_intensity":0.6,"maritime_threat_level":0.5,"air_threat_level":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');

-- Table: migrations (8 rows)
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
`id` bigint   AUTO_INCREMENT PRIMARY KEY,
`migration` varchar(255) NOT NULL DEFAULT 'None',
`batch` bigint NOT NULL DEFAULT 'None',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `migrations` (`id`,`migration`,`batch`) VALUES
  (1, '0001_01_01_000000_create_users_table', 1),
  (2, '0001_01_01_000001_create_cache_table', 1),
  (3, '0001_01_01_000002_create_jobs_table', 1),
  (4, '2026_08_11_000001_create_disaster_types_table', 2),
  (5, '2026_08_11_000002_create_wars_table', 2),
  (6, '2026_08_11_000003_create_presets_table', 2),
  (7, '2026_08_11_000004_create_simulations_table', 2),
  (8, '2026_08_11_000005_create_roles_and_add_to_users', 2);

-- Table: presets (3 rows)
DROP TABLE IF EXISTS `presets`;
CREATE TABLE `presets` (
`id` bigint   AUTO_INCREMENT PRIMARY KEY,
`code` varchar(255) NOT NULL DEFAULT 'None',
`nama` varchar(255) NOT NULL DEFAULT 'None',
`deskripsi` text  DEFAULT 'None',
`lat` varchar(255) NOT NULL DEFAULT 'None',
`lon` varchar(255) NOT NULL DEFAULT 'None',
`zoom` bigint NOT NULL DEFAULT ''10'',
`population` bigint NOT NULL DEFAULT ''500000'',
`area_km2` varchar(255) NOT NULL DEFAULT ''50'',
`disaster_types` text  DEFAULT 'None',
`param_overrides` text  DEFAULT 'None',
`created_at` varchar(255)  DEFAULT 'None',
`updated_at` varchar(255)  DEFAULT 'None',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `presets` (`id`,`code`,`nama`,`deskripsi`,`lat`,`lon`,`zoom`,`population`,`area_km2`,`disaster_types`,`param_overrides`,`created_at`,`updated_at`) VALUES
  (1, 'natuna', 'Kepulauan Natuna', 'Preset untuk simulasi sengketa maritim & keamanan di Laut Natuna Utara.', 3.9954, 108.388, 8, 85000, 2642, '["maritime","air","combined","earthquake","flood"]', '{"maritime_threat_level":0.75,"air_threat_level":0.6}', '2026-08-11 07:39:06', '2026-08-11 07:39:06'),
  (2, 'papua', 'Papua & Papua Barat', 'Preset untuk simulasi konflik daerah, penegakan keamanan, dan bencana di Tanah Papua.', -4.2699, 136.0843, 7, 4300000, 420540, '["conflict","social_conflict","flood","landslide","earthquake"]', '{"conflict_intensity":0.6}', '2026-08-11 07:39:06', '2026-08-11 07:39:06'),
  (3, 'timor', 'Timor Timur (NTT)', 'Preset untuk simulasi operasi militer & bencana di perbatasan Timor.', -9.5297, 125.0348, 8, 5300000, 47581, '["conflict","maritime","volcano","drought","flood"]', '{"conflict_intensity":0.5}', '2026-08-11 07:39:06', '2026-08-11 07:39:06');

-- Table: roles (3 rows)
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
`id` bigint   AUTO_INCREMENT PRIMARY KEY,
`code` varchar(255) NOT NULL DEFAULT 'None',
`nama` varchar(255) NOT NULL DEFAULT 'None',
`deskripsi` text  DEFAULT 'None',
`created_at` varchar(255)  DEFAULT 'None',
`updated_at` varchar(255)  DEFAULT 'None',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `roles` (`id`,`code`,`nama`,`deskripsi`,`created_at`,`updated_at`) VALUES
  (1, 'admin', 'Administrator', 'Akses penuh: kelola pengguna, data, pengaturan sistem.', '2026-08-11 07:39:06', '2026-08-11 07:39:06'),
  (2, 'operator', 'Operator', 'Menjalankan simulasi, melihat hasil, mengelola preset.', '2026-08-11 07:39:06', '2026-08-11 07:39:06'),
  (3, 'viewer', 'Viewer', 'Hanya melihat dashboard dan hasil simulasi.', '2026-08-11 07:39:06', '2026-08-11 07:39:06');

-- Table: wars (45 rows)
DROP TABLE IF EXISTS `wars`;
CREATE TABLE `wars` (
`id` bigint   AUTO_INCREMENT PRIMARY KEY,
`nama` varchar(255) NOT NULL DEFAULT 'None',
`tahun` varchar(255) NOT NULL DEFAULT 'None',
`wilayah` text NOT NULL DEFAULT 'None',
`matra` varchar(255) NOT NULL DEFAULT 'None',
`kategori` varchar(255) NOT NULL DEFAULT 'None',
`pop` bigint NOT NULL DEFAULT ''0'',
`lat` varchar(255) NOT NULL DEFAULT 'None',
`lon` varchar(255) NOT NULL DEFAULT 'None',
`deskripsi` text NOT NULL DEFAULT 'None',
`created_at` varchar(255)  DEFAULT 'None',
`updated_at` varchar(255)  DEFAULT 'None',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `wars` (`id`,`nama`,`tahun`,`wilayah`,`matra`,`kategori`,`pop`,`lat`,`lon`,`deskripsi`,`created_at`,`updated_at`) VALUES
  (1, 'Perang Bubat', '1357', 'Bubat, Jawa Timur (Trowulan)', 'darat', 'Perang Era Kerajaan', 200000, -7.55, 112.38, 'Konflik Majapahit vs Kerajaan Sunda (Pasunda Bubat) di alun-alun Trowulan.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (2, 'Perang Paregreg', '1404-1406', 'Majapahit, Jawa Timur', 'darat', 'Perang Saudara', 300000, -7.55, 112.38, 'Perang saudara Majapahit antara Bhre Wirabhumi dan Wikramawardhana.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (3, 'Perang Demak-Majapahit', '1527', 'Demak - Majapahit, Jawa Timur', 'darat', 'Perang Era Kerajaan', 220000, -6.89, 110.63, 'Ekspedisi Kesultanan Demak menguasai sisa Majapahit.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (4, 'Perang Aceh', '1873-1904', 'Aceh, Sumatera', 'gabungan', 'Perlawanan Kolonial', 500000, 5.55, 95.32, 'Perlawanan Kesultanan Aceh vs Belanda — perang terlama dan terbesar di Nusantara.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (5, 'Perang Padri', '1803-1837', 'Minangkabau, Sumatera Barat', 'darat', 'Perlawanan Kolonial', 350000, -0.55, 100.5, 'Perang saudara kaum Padri hingga Belanda turun tangan.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (6, 'Perang Diponegoro', '1825-1830', 'Jogja - Surakarta, Jawa Tengah', 'darat', 'Perlawanan Kolonial', 500000, -7.79, 110.36, 'Perang Jawa Pangeran Diponegoro — perlawanan terbesar abad 19.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (7, 'Perang Banjar', '1859-1905', 'Kalimantan Selatan', 'darat', 'Perlawanan Kolonial', 250000, -3.35, 114.6, 'Perlawanan Kesultanan Banjar vs Belanda (Pangeran Antasari).', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (8, 'Perang Bali (Ekspedisi)', '1846-1849', 'Bali', 'gabungan', 'Perlawanan Kolonial', 150000, -8.36, 115.13, 'Ekspedisi Belanda ke Buleleng, Jagaraga, dan badung.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (9, 'Perang Jagaraga', '1848-1849', 'Jagaraga, Buleleng, Bali', 'darat', 'Perlawanan Kolonial', 100000, -8.1, 115.09, 'Puputan Buleleng — pertempuran habis-habisan di benteng Jagaraga.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (10, 'Perang Puputan Badung', '1906', 'Badung, Bali Selatan', 'darat', 'Perlawanan Kolonial', 90000, -8.65, 115.21, 'Puputan (perang habis-habisan) Badung vs Belanda.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (11, 'Perang Puputan Klungkung', '1908', 'Klungkung, Bali', 'darat', 'Perlawanan Kolonial', 80000, -8.54, 115.41, 'Puputan Klungkung — penyerahan terakhir Bali ke Belanda.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (12, 'Perang Sisingamangaraja', '1878-1907', 'Tapanuli, Sumatera Utara', 'darat', 'Perlawanan Kolonial', 200000, 1.99, 99.25, 'Perlawanan rakyat Batak pimpinan Sisingamangaraja XII.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (13, 'Perang Batak', '1878-1907', 'Tanah Batak, Sumatera Utara', 'darat', 'Perlawanan Kolonial', 200000, 1.99, 99.25, 'Gelombang perlawanan di tanah Batak terhadap kompeni.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (14, 'Perang Makassar', '1660-1669', 'Gowa-Tallo, Sulawesi Selatan', 'gabungan', 'Perlawanan Kolonial', 300000, -5.15, 119.43, 'Perlawanan Sultan Hasanuddin (Ayam Jantan Timur) vs Belanda.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (15, 'Perang Tondano', '1808-1809', 'Minahasa, Sulawesi Utara', 'darat', 'Perlawanan Kolonial', 120000, 1.3, 124.9, 'Perlawanan rakyat Tondano terhadap kerja rodi Belanda.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (16, 'Perang Jawa', '1837', 'Jawa Timur', 'darat', 'Perlawanan Kolonial', 400000, -7.8, 112.03, 'Perlawanan di Kediri dan kawasan Jawa Timur.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (17, 'Perang Lombok', '1894', 'Lombok, NTB', 'gabungan', 'Perlawanan Kolonial', 180000, -8.65, 116.32, 'Ekspedisi Belanda ke Lombok (Cakranagara).', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (18, 'Perang Bone', '1824-1905', 'Sulawesi Selatan', 'darat', 'Perlawanan Kolonial', 150000, -4.53, 120.29, 'Perlawanan panjang Kesultanan Bone melawan Belanda.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (19, 'Perang Palembang', '1819-1821', 'Palembang, Sumatera Selatan', 'laut', 'Perlawanan Kolonial', 160000, -2.99, 104.75, 'Serangan militer Belanda ke Kesultanan Palembang (Sultan Mahmud).', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (20, 'Perang Pattimura', '1817', 'Saparua, Maluku Tengah', 'darat', 'Perlawanan Kolonial', 100000, -3.78, 128.64, 'Perlawanan Kapitan Pattimura di Saparua, Maluku.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (21, 'Perang Diponegoro II', '1825', 'Jawa Tengah', 'darat', 'Perlawanan Kolonial', 500000, -7.79, 110.36, 'Ekses lanjutan Perlawanan Pangeran Diponegoro.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (22, 'Perang Gerilya Jend. Sudirman', '1948-1949', 'Jawa Tengah - Selatan', 'darat', 'Revolusi Nasional', 450000, -7.65, 109.06, 'Perjalanan gerilya Jenderal Sudirman saat Agresi Militer Belanda II.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (23, 'Pertempuran Surabaya', '1945', 'Surabaya, Jawa Timur', 'darat', 'Revolusi Nasional', 600000, -7.25, 112.75, 'Pertempuran 10 November 1945 — arek-arek Surabaya melawan Sekutu.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (24, 'Pertempuran Ambarawa', '1945', 'Ambarawa, Jawa Tengah', 'darat', 'Revolusi Nasional', 180000, -7.27, 110.39, 'Pertempuran Palagan Ambarawa (12-15 Des 1945).', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (25, 'Pertempuran Medan Area', '1945-1946', 'Medan, Sumatera Utara', 'darat', 'Revolusi Nasional', 250000, 3.59, 98.67, 'Aksi pemuda Medan vs pasukan Belanda/NICA.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (26, 'Bandung Lautan Api', '1946', 'Bandung, Jawa Barat', 'darat', 'Revolusi Nasional', 400000, -6.91, 107.61, 'Pembakaran Bandung Selatan oleh pejuang pada 24 Maret 1946.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (27, 'Pertempuran Lima Hari di Semarang', '1945', 'Semarang, Jawa Tengah', 'darat', 'Revolusi Nasional', 350000, -6.96, 110.42, 'Pertempuran 15-19 Okt 1945 — gugurnya dr. Karyadi.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (28, 'Agresi Militer Belanda I', '1947', 'Jawa & Sumatera', 'gabungan', 'Revolusi Nasional', 900000, -7.5, 110, 'Operasi Product Belanda (Juli 1947).', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (29, 'Agresi Militer Belanda II', '1948-1949', 'Jogja & Jawa Tengah', 'gabungan', 'Revolusi Nasional', 750000, -7.79, 110.36, 'Serangan ke Ibukota Yogja (19 Des 1948) — awal gerilya Sudirman.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (30, 'Perang Kemerdekaan Indonesia', '1945-1949', 'Seluruh Indonesia', 'gabungan', 'Revolusi Nasional', 2000000, -6.2, 106.8, 'Revolusi nasional total — darat, laut, udara.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (31, 'Pemberontakan PKI Madiun', '1948', 'Madiun, Jawa Timur', 'darat', 'Pemberontakan Dalam Negeri', 150000, -7.62, 111.53, 'Pemberontakan PKI Musso di Madiun.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (32, 'Pemberontakan DI/TII', '1949-1962', 'Jabar & Sulsel', 'darat', 'Pemberontakan Dalam Negeri', 300000, -6.91, 107.61, 'Geraka DI/TII Kartosuwiryo & Kahar Muzakkar.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (33, 'Pemberontakan APRA', '1950', 'Bandung, Jawa Barat', 'darat', 'Pemberontakan Dalam Negeri', 100000, -6.91, 107.61, 'Angkatan Perang Ratu Adil (APRA) kudeta Westerling.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (34, 'Pemberontakan Andi Azis', '1950', 'Makassar, Sulawesi Selatan', 'darat', 'Pemberontakan Dalam Negeri', 120000, -5.14, 119.43, 'Kudeta Andi Azis di Makassar.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (35, 'Pemberontakan RMS', '1950', 'Ambon, Maluku', 'darat', 'Pemberontakan Dalam Negeri', 90000, -3.69, 128.17, 'Republik Maluku Selatan (Soumokil).', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (36, 'PRRI / Pemerintah Revolusioner', '1958-1961', 'Bukittinggi, Sumatera Barat', 'darat', 'Pemberontakan Dalam Negeri', 180000, -0.3, 100.36, 'Pemerintahan Revolusioner RI di Sumatera Tengah.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (37, 'Permesta', '1957-1961', 'Manado, Sulawesi Utara', 'gabungan', 'Pemberontakan Dalam Negeri', 150000, 1.47, 124.85, 'Permesta — operasi militer di Sulawesi Utara & Maluku.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (38, 'Operasi Trikora', '1961-1962', 'Papua Barat', 'gabungan', 'Operasi Militer Nasional', 400000, -4, 137, 'Operasi pembebasan Irian Barat dari Belanda.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (39, 'Operasi Dwikora', '1963-1966', 'Kalimantan Utara & Sarawak', 'gabungan', 'Operasi Militer Nasional', 350000, 3, 114, 'Konfrontasi dengan Malaysia — perang konvensional + non-tempur.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (40, 'Operasi Seroja', '1975-1976', 'Timor Timur (Dili)', 'gabungan', 'Operasi Militer Nasional', 250000, -8.5, 126, 'Operasi integrasi di Timor Timur.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (41, 'Konflik Aceh', '1976-2005', 'Aceh', 'gabungan', 'Konflik Daerah', 400000, 5, 96, 'Konflik separatisme GAM vs TNI/POLRI (MoU Helsinki 2005).', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (42, 'Konflik Timor Timur', '1976-1999', 'Timor Timur (Dili)', 'gabungan', 'Konflik Daerah', 200000, -8.55, 125.56, 'Konflik dan referendum 1999.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (43, 'Konflik Poso', '1998-2001', 'Poso, Sulawesi Tengah', 'darat', 'Konflik Daerah', 150000, -1.39, 120.76, 'Konflik komunal Poso.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (44, 'Konflik Maluku', '1999-2002', 'Ambon, Maluku', 'darat', 'Konflik Daerah', 200000, -3.69, 128.17, 'Konflik komunal Ambon.', '2026-08-11 07:35:29', '2026-08-11 07:35:29'),
  (45, 'Konflik Papua', '1962-sekarang', 'Papua & Papua Barat', 'gabungan', 'Konflik Daerah', 500000, -4, 137, 'Konflik separat OPM/TPNPB — operasi keamanan.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');

SET FOREIGN_KEY_CHECKS=1;