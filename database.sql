/* ============================================================
   CRISIS COMMAND CENTER (Laravel) — Database Seed MySQL
   ============================================================
   Cara pakai:
     mysql -u root -p -e 'CREATE DATABASE ccc_database CHARACTER SET utf8mb4';
     mysql -u root -p ccc_database < database.sql
   ============================================================ */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET @@SESSION.SQL_LOG_BIN = 0;

-- ================== STRUKTUR TABEL ==================

CREATE TABLE IF NOT EXISTS `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `deskripsi` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL,
  `password` VARCHAR(255) NOT NULL,
  `role_id` BIGINT UNSIGNED NULL,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `disaster_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(100) NOT NULL,
  `nama` VARCHAR(200) NOT NULL,
  `kategori` VARCHAR(100) NULL,
  `kelompok` VARCHAR(100) NOT NULL DEFAULT 'Alam',
  `deskripsi` TEXT NULL,
  `param_demo` JSON NULL,
  `icon` VARCHAR(50) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `disaster_types_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `wars` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(200) NOT NULL,
  `tahun` VARCHAR(50) NULL,
  `wilayah` VARCHAR(200) NULL,
  `matra` VARCHAR(100) NULL,
  `kategori` VARCHAR(100) NULL,
  `pop` BIGINT NULL,
  `lat` DOUBLE NULL,
  `lon` DOUBLE NULL,
  `deskripsi` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `presets` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(100) NOT NULL,
  `nama` VARCHAR(200) NOT NULL,
  `deskripsi` TEXT NULL,
  `lat` DOUBLE NULL,
  `lon` DOUBLE NULL,
  `zoom` INT NULL,
  `population` BIGINT NULL,
  `area_km2` DOUBLE NULL,
  `disaster_types` JSON NULL,
  `param_overrides` JSON NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `presets_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `simulations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL,
  `disaster_type_id` BIGINT UNSIGNED NOT NULL,
  `war_id` BIGINT UNSIGNED NULL,
  `preset_id` BIGINT UNSIGNED NULL,
  `location` VARCHAR(255) NOT NULL DEFAULT 'Kota Semarang',
  `lat` DOUBLE NULL,
  `lon` DOUBLE NULL,
  `population` BIGINT NOT NULL DEFAULT 500000,
  `area_km2` DOUBLE NOT NULL DEFAULT 50,
  `area_type` VARCHAR(50) NOT NULL DEFAULT 'suburb',
  `infrastructure_density` DOUBLE NOT NULL DEFAULT 0.5,
  `params` JSON NULL,
  `classification` VARCHAR(50) NOT NULL,
  `alert_level` VARCHAR(20) NOT NULL,
  `affected_population` BIGINT NOT NULL DEFAULT 0,
  `estimated_casualties` BIGINT NOT NULL DEFAULT 0,
  `estimated_deaths` BIGINT NOT NULL DEFAULT 0,
  `estimated_injured` BIGINT NOT NULL DEFAULT 0,
  `displaced` BIGINT NOT NULL DEFAULT 0,
  `damaged_buildings` BIGINT NOT NULL DEFAULT 0,
  `destroyed_buildings` BIGINT NOT NULL DEFAULT 0,
  `economic_damage_usd` DOUBLE NOT NULL DEFAULT 0,
  `impact_detail` JSON NULL,
  `resources` JSON NULL,
  `actions` JSON NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `simulations_disaster_type_id_foreign` (`disaster_type_id`),
  KEY `simulations_user_id_foreign` (`user_id`),
  CONSTRAINT `simulations_disaster_type_id_foreign` FOREIGN KEY (`disaster_type_id`) REFERENCES `disaster_types` (`id`),
  CONSTRAINT `simulations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL,
  `cancelled_at` INT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ===== DATA: roles (3 baris) =====
INSERT INTO `roles` (`id`, `code`, `nama`, `deskripsi`, `created_at`, `updated_at`) VALUES (1, 'admin', 'Administrator', 'Akses penuh: kelola pengguna, data, pengaturan sistem.', '2026-08-11 07:39:06', '2026-08-11 07:39:06');
INSERT INTO `roles` (`id`, `code`, `nama`, `deskripsi`, `created_at`, `updated_at`) VALUES (2, 'operator', 'Operator', 'Menjalankan simulasi, melihat hasil, mengelola preset.', '2026-08-11 07:39:06', '2026-08-11 07:39:06');
INSERT INTO `roles` (`id`, `code`, `nama`, `deskripsi`, `created_at`, `updated_at`) VALUES (3, 'viewer', 'Viewer', 'Hanya melihat dashboard dan hasil simulasi.', '2026-08-11 07:39:06', '2026-08-11 07:39:06');

-- ===== DATA: disaster_types (31 baris) =====
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (1, 'earthquake', 'Gempa Bumi', 'Bencana Alam Geologis', 'Alam', 'Gempa bumi berdenyut yang disebabkan oleh pergerakan platen.', '{"earthquake_magnitude":6.5,"earthquake_depth_km":20}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (2, 'tsunami', 'Tsunami', 'Bencana Alam Geologis', 'Alam', 'Gelombang laut raksasa akibat gempa bumi di dasar laut.', '{"tsunami_wave_height_m":5,"tsunami_epicenter_distance_km":50}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (3, 'volcano', 'Letusan Gunung Api', 'Bencana Alam Geologis', 'Alam', 'Letusan magma, abu, dan gas dari gunung api.', '{"volcano_vei":4,"volcano_eruption_distance_km":10}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (4, 'landslide', 'Tanah Longsor', 'Bencana Alam Geologis', 'Alam', 'Gerakan massa tanah/batu menurun lereng akibat gravitasi.', '{"severity_scale":0.6}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (5, 'liquefaction', 'Likuifaksi', 'Bencana Alam Geologis', 'Alam', 'Tanah jenuh air kehilangan kekuatan dukungan saat diguncang.', '{"severity_scale":0.6}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (6, 'flood', 'Banjir', 'Bencana Alam Hidrometeorologi', 'Alam', 'Genangan air meluap ke area pemukiman akibat curah hujan tinggi.', '{"flood_depth_m":1.5,"flood_duration_hours":24}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (7, 'flash_flood', 'Banjir Bandang', 'Bencana Alam Hidrometeorologi', 'Alam', 'Banjir mendadak berkecepatan tinggi dari lereng gunung.', '{"severity_scale":0.6}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (8, 'drought', 'Kekeringan', 'Bencana Alam Hidrometeorologi', 'Alam', 'Kelanggaran air jangka panjang akibat curah hujan di bawah normal.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (9, 'tornado', 'Angin Puting Beliung', 'Bencana Alam Hidrometeorologi', 'Alam', 'Putaran udara cepat merusak bangunan di jalurnya.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (10, 'strong_wind', 'Angin Kencang', 'Bencana Alam Hidrometeorologi', 'Alam', 'Angin kencang merusak atap, pohon, dan infrastruktur ringan.', '{"severity_scale":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (11, 'coastal_abrasion', 'Abrasi Pantai', 'Bencana Alam Hidrometeorologi', 'Alam', 'Erosi pantai akibat gelombang & arus laut yang menghantam tebing.', '{"severity_scale":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (12, 'extreme_wave', 'Gelombang Ekstrem', 'Bencana Alam Hidrometeorologi', 'Alam', 'Gelombang laut tinggi berlebihan akibat cuaca buruk.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (13, 'disease_outbreak', 'Wabah Penyakit', 'Bencana Alam Biologi', 'Alam', 'Munculnya kasus penyakit menular melebihi ambang normal.', '{"severity_scale":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (14, 'pandemic', 'Pandemi', 'Bencana Alam Biologi', 'Alam', 'Wabah penyakit menular skala global/nasional.', '{"severity_scale":0.6}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (15, 'forest_fire', 'Kebakaran Hutan dan Lahan', 'Kebakaran', 'Alam', 'Kebakaran vegetasi luas di hutan, lahan gambut, atau perkebunan.', '{"fire_area_ha":2000,"fire_wind_speed_kmh":25,"fire_fuel_type":"peat"}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (16, 'building_fire', 'Kebakaran Gedung', 'Kebakaran', 'Non-Alam', 'Kebakaran struktur gedung bertingkat/komersial.', '{"severity_scale":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (17, 'settlement_fire', 'Kebakaran Permukiman', 'Kebakaran', 'Non-Alam', 'Kebakaran menyebar di padatan permukiman kumuh.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (18, 'transport_accident', 'Kecelakaan Transportasi', 'Bencana Non-Alam', 'Non-Alam', 'Tabrakan/kejutan kendaraan darat/laut/udara menimbulkan korban massal.', '{"severity_scale":0.3}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (19, 'tech_failure', 'Kegagalan Teknologi', 'Bencana Non-Alam', 'Non-Alam', 'Kegagalan sistem vital (listrik, komunikasi, nuklir, kimia).', '{"severity_scale":0.3}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (20, 'environmental_pollution', 'Pencemaran Lingkungan', 'Bencana Non-Alam', 'Non-Alam', 'Pencemaran udara/air/tanah bahan berbahaya skala luas.', '{"severity_scale":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (21, 'toxic_gas', 'Gas Beracun', 'Bencana Non-Alam', 'Non-Alam', 'Peledakan/kebocoran gas toksik di area industri/pertambangan.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (22, 'construction_failure', 'Kegagalan Konstruksi', 'Bencana Non-Alam', 'Non-Alam', 'Runtuhnya bendungan, jembatan, gedung tinggi akibat desain/eksekusi buruk.', '{"severity_scale":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (23, 'social_conflict', 'Konflik Sosial', 'Bencana Sosial', 'Sosial', 'Benturan antar kelompok etnis/agama/kelas sosial.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (24, 'riot', 'Kerusuhan', 'Bencana Sosial', 'Sosial', 'Kekerasan massa tidak terencana di area perkotaan.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (25, 'terrorism', 'Terorisme', 'Bencana Sosial', 'Sosial', 'Serangan kekerasan terencana menakuti-nakuti masyarakat.', '{"severity_scale":0.6}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (26, 'mass_violence', 'Aksi Kekerasan Massal', 'Bencana Sosial', 'Sosial', 'Kekerasan skala besar antar massa/kelompok.', '{"severity_scale":0.5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (27, 'demonstration', 'Demo', 'Bencana Sosial', 'Sosial', 'Aksi unjuk rasa yang berpotensi eskalasi kekerasan.', '{"severity_scale":0.3}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (28, 'conflict', 'Konflik Darat', 'Operasi Militer', 'Militer', 'Operasi darat konvensional/gerilya/pemberontakan.', '{"conflict_intensity":0.7,"conflict_type":"insurgency"}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (29, 'maritime', 'Konflik Laut', 'Operasi Militer', 'Militer', 'Operasi maritim: sengketa ZEE, blokade, amfibi, pembajakan.', '{"maritime_threat_level":0.8,"enemy_naval_units":5}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (30, 'air', 'Konflik Udara', 'Operasi Militer', 'Militer', 'Operasi udara: intrusi, pertahanan udara, serangan udara, no-fly zone.', '{"air_threat_level":0.7,"enemy_aircraft":6}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');
INSERT INTO `disaster_types` (`id`, `code`, `nama`, `kategori`, `kelompok`, `deskripsi`, `param_demo`, `created_at`, `updated_at`) VALUES (31, 'combined', 'Operasi Gabungan Tri-Matra', 'Operasi Militer', 'Militer', 'Operasi gabungan darat + laut + udara (Tri Matra TNI).', '{"conflict_intensity":0.6,"maritime_threat_level":0.5,"air_threat_level":0.4}', '2026-08-11 07:39:05', '2026-08-11 07:39:05');

-- ===== DATA: wars (45 baris) =====
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (1, 'Perang Bubat', '1357', 'Bubat, Jawa Timur (Trowulan)', 'darat', 'Perang Era Kerajaan', 200000, -7.55, 112.38, 'Konflik Majapahit vs Kerajaan Sunda (Pasunda Bubat) di alun-alun Trowulan.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (2, 'Perang Paregreg', '1404-1406', 'Majapahit, Jawa Timur', 'darat', 'Perang Saudara', 300000, -7.55, 112.38, 'Perang saudara Majapahit antara Bhre Wirabhumi dan Wikramawardhana.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (3, 'Perang Demak-Majapahit', '1527', 'Demak - Majapahit, Jawa Timur', 'darat', 'Perang Era Kerajaan', 220000, -6.89, 110.63, 'Ekspedisi Kesultanan Demak menguasai sisa Majapahit.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (4, 'Perang Aceh', '1873-1904', 'Aceh, Sumatera', 'gabungan', 'Perlawanan Kolonial', 500000, 5.55, 95.32, 'Perlawanan Kesultanan Aceh vs Belanda — perang terlama dan terbesar di Nusantara.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (5, 'Perang Padri', '1803-1837', 'Minangkabau, Sumatera Barat', 'darat', 'Perlawanan Kolonial', 350000, -0.55, 100.5, 'Perang saudara kaum Padri hingga Belanda turun tangan.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (6, 'Perang Diponegoro', '1825-1830', 'Jogja - Surakarta, Jawa Tengah', 'darat', 'Perlawanan Kolonial', 500000, -7.79, 110.36, 'Perang Jawa Pangeran Diponegoro — perlawanan terbesar abad 19.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (7, 'Perang Banjar', '1859-1905', 'Kalimantan Selatan', 'darat', 'Perlawanan Kolonial', 250000, -3.35, 114.6, 'Perlawanan Kesultanan Banjar vs Belanda (Pangeran Antasari).', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (8, 'Perang Bali (Ekspedisi)', '1846-1849', 'Bali', 'gabungan', 'Perlawanan Kolonial', 150000, -8.36, 115.13, 'Ekspedisi Belanda ke Buleleng, Jagaraga, dan badung.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (9, 'Perang Jagaraga', '1848-1849', 'Jagaraga, Buleleng, Bali', 'darat', 'Perlawanan Kolonial', 100000, -8.1, 115.09, 'Puputan Buleleng — pertempuran habis-habisan di benteng Jagaraga.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (10, 'Perang Puputan Badung', '1906', 'Badung, Bali Selatan', 'darat', 'Perlawanan Kolonial', 90000, -8.65, 115.21, 'Puputan (perang habis-habisan) Badung vs Belanda.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (11, 'Perang Puputan Klungkung', '1908', 'Klungkung, Bali', 'darat', 'Perlawanan Kolonial', 80000, -8.54, 115.41, 'Puputan Klungkung — penyerahan terakhir Bali ke Belanda.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (12, 'Perang Sisingamangaraja', '1878-1907', 'Tapanuli, Sumatera Utara', 'darat', 'Perlawanan Kolonial', 200000, 1.99, 99.25, 'Perlawanan rakyat Batak pimpinan Sisingamangaraja XII.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (13, 'Perang Batak', '1878-1907', 'Tanah Batak, Sumatera Utara', 'darat', 'Perlawanan Kolonial', 200000, 1.99, 99.25, 'Gelombang perlawanan di tanah Batak terhadap kompeni.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (14, 'Perang Makassar', '1660-1669', 'Gowa-Tallo, Sulawesi Selatan', 'gabungan', 'Perlawanan Kolonial', 300000, -5.15, 119.43, 'Perlawanan Sultan Hasanuddin (Ayam Jantan Timur) vs Belanda.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (15, 'Perang Tondano', '1808-1809', 'Minahasa, Sulawesi Utara', 'darat', 'Perlawanan Kolonial', 120000, 1.3, 124.9, 'Perlawanan rakyat Tondano terhadap kerja rodi Belanda.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (16, 'Perang Jawa', '1837', 'Jawa Timur', 'darat', 'Perlawanan Kolonial', 400000, -7.8, 112.03, 'Perlawanan di Kediri dan kawasan Jawa Timur.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (17, 'Perang Lombok', '1894', 'Lombok, NTB', 'gabungan', 'Perlawanan Kolonial', 180000, -8.65, 116.32, 'Ekspedisi Belanda ke Lombok (Cakranagara).', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (18, 'Perang Bone', '1824-1905', 'Sulawesi Selatan', 'darat', 'Perlawanan Kolonial', 150000, -4.53, 120.29, 'Perlawanan panjang Kesultanan Bone melawan Belanda.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (19, 'Perang Palembang', '1819-1821', 'Palembang, Sumatera Selatan', 'laut', 'Perlawanan Kolonial', 160000, -2.99, 104.75, 'Serangan militer Belanda ke Kesultanan Palembang (Sultan Mahmud).', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (20, 'Perang Pattimura', '1817', 'Saparua, Maluku Tengah', 'darat', 'Perlawanan Kolonial', 100000, -3.78, 128.64, 'Perlawanan Kapitan Pattimura di Saparua, Maluku.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (21, 'Perang Diponegoro II', '1825', 'Jawa Tengah', 'darat', 'Perlawanan Kolonial', 500000, -7.79, 110.36, 'Ekses lanjutan Perlawanan Pangeran Diponegoro.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (22, 'Perang Gerilya Jend. Sudirman', '1948-1949', 'Jawa Tengah - Selatan', 'darat', 'Revolusi Nasional', 450000, -7.65, 109.06, 'Perjalanan gerilya Jenderal Sudirman saat Agresi Militer Belanda II.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (23, 'Pertempuran Surabaya', '1945', 'Surabaya, Jawa Timur', 'darat', 'Revolusi Nasional', 600000, -7.25, 112.75, 'Pertempuran 10 November 1945 — arek-arek Surabaya melawan Sekutu.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (24, 'Pertempuran Ambarawa', '1945', 'Ambarawa, Jawa Tengah', 'darat', 'Revolusi Nasional', 180000, -7.27, 110.39, 'Pertempuran Palagan Ambarawa (12-15 Des 1945).', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (25, 'Pertempuran Medan Area', '1945-1946', 'Medan, Sumatera Utara', 'darat', 'Revolusi Nasional', 250000, 3.59, 98.67, 'Aksi pemuda Medan vs pasukan Belanda/NICA.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (26, 'Bandung Lautan Api', '1946', 'Bandung, Jawa Barat', 'darat', 'Revolusi Nasional', 400000, -6.91, 107.61, 'Pembakaran Bandung Selatan oleh pejuang pada 24 Maret 1946.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (27, 'Pertempuran Lima Hari di Semarang', '1945', 'Semarang, Jawa Tengah', 'darat', 'Revolusi Nasional', 350000, -6.96, 110.42, 'Pertempuran 15-19 Okt 1945 — gugurnya dr. Karyadi.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (28, 'Agresi Militer Belanda I', '1947', 'Jawa & Sumatera', 'gabungan', 'Revolusi Nasional', 900000, -7.5, 110, 'Operasi Product Belanda (Juli 1947).', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (29, 'Agresi Militer Belanda II', '1948-1949', 'Jogja & Jawa Tengah', 'gabungan', 'Revolusi Nasional', 750000, -7.79, 110.36, 'Serangan ke Ibukota Yogja (19 Des 1948) — awal gerilya Sudirman.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (30, 'Perang Kemerdekaan Indonesia', '1945-1949', 'Seluruh Indonesia', 'gabungan', 'Revolusi Nasional', 2000000, -6.2, 106.8, 'Revolusi nasional total — darat, laut, udara.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (31, 'Pemberontakan PKI Madiun', '1948', 'Madiun, Jawa Timur', 'darat', 'Pemberontakan Dalam Negeri', 150000, -7.62, 111.53, 'Pemberontakan PKI Musso di Madiun.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (32, 'Pemberontakan DI/TII', '1949-1962', 'Jabar & Sulsel', 'darat', 'Pemberontakan Dalam Negeri', 300000, -6.91, 107.61, 'Geraka DI/TII Kartosuwiryo & Kahar Muzakkar.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (33, 'Pemberontakan APRA', '1950', 'Bandung, Jawa Barat', 'darat', 'Pemberontakan Dalam Negeri', 100000, -6.91, 107.61, 'Angkatan Perang Ratu Adil (APRA) kudeta Westerling.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (34, 'Pemberontakan Andi Azis', '1950', 'Makassar, Sulawesi Selatan', 'darat', 'Pemberontakan Dalam Negeri', 120000, -5.14, 119.43, 'Kudeta Andi Azis di Makassar.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (35, 'Pemberontakan RMS', '1950', 'Ambon, Maluku', 'darat', 'Pemberontakan Dalam Negeri', 90000, -3.69, 128.17, 'Republik Maluku Selatan (Soumokil).', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (36, 'PRRI / Pemerintah Revolusioner', '1958-1961', 'Bukittinggi, Sumatera Barat', 'darat', 'Pemberontakan Dalam Negeri', 180000, -0.3, 100.36, 'Pemerintahan Revolusioner RI di Sumatera Tengah.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (37, 'Permesta', '1957-1961', 'Manado, Sulawesi Utara', 'gabungan', 'Pemberontakan Dalam Negeri', 150000, 1.47, 124.85, 'Permesta — operasi militer di Sulawesi Utara & Maluku.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (38, 'Operasi Trikora', '1961-1962', 'Papua Barat', 'gabungan', 'Operasi Militer Nasional', 400000, -4, 137, 'Operasi pembebasan Irian Barat dari Belanda.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (39, 'Operasi Dwikora', '1963-1966', 'Kalimantan Utara & Sarawak', 'gabungan', 'Operasi Militer Nasional', 350000, 3, 114, 'Konfrontasi dengan Malaysia — perang konvensional + non-tempur.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (40, 'Operasi Seroja', '1975-1976', 'Timor Timur (Dili)', 'gabungan', 'Operasi Militer Nasional', 250000, -8.5, 126, 'Operasi integrasi di Timor Timur.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (41, 'Konflik Aceh', '1976-2005', 'Aceh', 'gabungan', 'Konflik Daerah', 400000, 5, 96, 'Konflik separatisme GAM vs TNI/POLRI (MoU Helsinki 2005).', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (42, 'Konflik Timor Timur', '1976-1999', 'Timor Timur (Dili)', 'gabungan', 'Konflik Daerah', 200000, -8.55, 125.56, 'Konflik dan referendum 1999.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (43, 'Konflik Poso', '1998-2001', 'Poso, Sulawesi Tengah', 'darat', 'Konflik Daerah', 150000, -1.39, 120.76, 'Konflik komunal Poso.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (44, 'Konflik Maluku', '1999-2002', 'Ambon, Maluku', 'darat', 'Konflik Daerah', 200000, -3.69, 128.17, 'Konflik komunal Ambon.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');
INSERT INTO `wars` (`id`, `nama`, `tahun`, `wilayah`, `matra`, `kategori`, `pop`, `lat`, `lon`, `deskripsi`, `created_at`, `updated_at`) VALUES (45, 'Konflik Papua', '1962-sekarang', 'Papua & Papua Barat', 'gabungan', 'Konflik Daerah', 500000, -4, 137, 'Konflik separat OPM/TPNPB — operasi keamanan.', '2026-08-11 07:35:29', '2026-08-11 07:35:29');

-- ===== DATA: presets (3 baris) =====
INSERT INTO `presets` (`id`, `code`, `nama`, `deskripsi`, `lat`, `lon`, `zoom`, `population`, `area_km2`, `disaster_types`, `param_overrides`, `created_at`, `updated_at`) VALUES (1, 'natuna', 'Kepulauan Natuna', 'Preset untuk simulasi sengketa maritim & keamanan di Laut Natuna Utara.', 3.9954, 108.388, 8, 85000, 2642, '["maritime","air","combined","earthquake","flood"]', '{"maritime_threat_level":0.75,"air_threat_level":0.6}', '2026-08-11 07:39:06', '2026-08-11 07:39:06');
INSERT INTO `presets` (`id`, `code`, `nama`, `deskripsi`, `lat`, `lon`, `zoom`, `population`, `area_km2`, `disaster_types`, `param_overrides`, `created_at`, `updated_at`) VALUES (2, 'papua', 'Papua & Papua Barat', 'Preset untuk simulasi konflik daerah, penegakan keamanan, dan bencana di Tanah Papua.', -4.2699, 136.0843, 7, 4300000, 420540, '["conflict","social_conflict","flood","landslide","earthquake"]', '{"conflict_intensity":0.6}', '2026-08-11 07:39:06', '2026-08-11 07:39:06');
INSERT INTO `presets` (`id`, `code`, `nama`, `deskripsi`, `lat`, `lon`, `zoom`, `population`, `area_km2`, `disaster_types`, `param_overrides`, `created_at`, `updated_at`) VALUES (3, 'timor', 'Timor Timur (NTT)', 'Preset untuk simulasi operasi militer & bencana di perbatasan Timor.', -9.5297, 125.0348, 8, 5300000, 47581, '["conflict","maritime","volcano","drought","flood"]', '{"conflict_intensity":0.5}', '2026-08-11 07:39:06', '2026-08-11 07:39:06');

SET FOREIGN_KEY_CHECKS=1;

-- ====== AKUN ADMIN DEFAULT ======
-- email: admin@ccc.test   password: admin123
-- (password di-hash bcrypt: 'admin123')
