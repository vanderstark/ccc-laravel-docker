# 🚨 Crisis Command Center (CCC) — Laravel Docker

**Command Center Digital untuk Simulasi Bencana, Operasi Militer & Latihan Kepemimpinan** — berbasis **Laravel 11** + **Docker Compose** (Nginx + PHP-FPM + MySQL 8).

Dikembangkan sesuai spesifikasi **Tactical Floor Game (TFG)** & **Rapat Pendirian Laboratorium Kepemimpinan Digital Polri Lembang** — offline-first (0 CDN), siap air-gapped.

---

## 📦 Fitur Lengkap

### 🎮 Menu Utama TFG (8 Menu Sesuai Dokumen)

| # | Menu | Fungsi | Detail |
|---|------|--------|--------|
| 1 | **Latihan** | Sesi & State Management | Briefing, ROE, state machine (draft→briefing→running→paused→ended), T+ timer, decision log |
| 2 | **Peta** | Peta Komando (Tactical Map) | Leaflet offline, marker, zona, route, live sync 10s, heatmap, bookmark |
| 3 | **Objek** | Pengelolaan Unit & Marker | CRUD marker unit/insiden/asset, status kekuatan |
| 4 | **Operasi** | Order Board & ORBAT | Order board (perintah/informasi), ORBAT board 7 satker, update status real-time |
| 5 | **Situasi** | Data & Informasi | Live feed, analitik, komunikasi krisis, medsos, kurikulum |
| 6 | **EXCON** | Inject & Fog of War | Inject queue (scheduled), fog of war control per satker, roleplay channel |
| 7 | **AAR** | Replay & Evaluasi | Replay player (1×–8×), heatmap pergerakan, side-by-side, laporan Markdown |
| 8 | **Sistem** | Admin & Config | RBAC 3 role, backup/restore, audit trail, konfigurasi lab |

### 🗺️ Peta Komando
- **Leaflet offline** — self-hosted tiles (0 CDN), unduh sekali → 100% offline
- **Marker** unit / insiden / aset (CRUD + tampil di peta)
- **Zona / Route / Objective** (CRUD + layer peta)
- **MarkerCluster + Heatmap** — visualisasi pergerakan unit
- **Live sync** — polling otomatis 10 detik, semua layar sinkron
- **Replay / After Action Review** — playback 1×/2×/4×/8×, timeline, anotasi

### ⚡ EXCON — Inject & Fog of War
- **Inject queue** — situasi dadakan dikirim ke satker pada waktu T+ tertentu
- **Fog of War** — batasi informasi per satker (AI, Reserse, Brimob, Lantas, Sabhara, Binmas, Manajemen Konflik)
- **Roleplay channel** — simulasi komunikasi radio antar satker
- **Control tempo** — EXCON atur kecepatan dan durasi simulasi

### 🎯 Session Management (Latihan)
- **State machine**: `draft` → `briefing` → `running` → `paused` → `ended`
- **T+ timer** — hitung waktu berjalan (detik/menit)
- **Objectives SMART** — 1–3 tujuan pembelajaran
- **ROE** — Rules of Engagement
- **Decision log** — catat keputusan per satker + PIC + waktu
- **7 Satker Blue Cell**: Analisis Informasi, Reserse, Brimob, Lantas, Sabhara, Binmas, Manajemen Konflik

### 📋 Order Board & ORBAT (Operasi)
- **Order board** — perintah, informasi, instruksi dengan status (draft→dikirim→dibaca→dilaksanakan→selesai)
- **ORBAT board** — Order of Battle: nama unit, kekuatan, status, lokasi GPS
- Update satker real-time (kekuatan, status bergerak/bertugas/pulang)

### 🖥️ Video Wall & COP Kiosk
- **COP read-only** — tampilan peta + unit + inject ticker untuk videotron
- **Layout**: Full map, Split 70/30, atau 2×2 grid
- **Auto-refresh 5 detik** — tanpa interaksi manual
- **URL**: `/wall/{session_id}` (bisa dibuka di layar lebar tanpa login)

### 📊 Replay Engine & Heatmap
- **Replay player** — playback timeline sesi (keputusan + inject)
- **Speed control**: 1×, 2×, 4×, 8×
- **Heatmap** — visualisasi pergerakan unit di peta (Leaflet.heat)
- **Side-by-side comparison** — bandingkan 2 sesi latihan
- **AAR Workflow**: Briefing → Simulation → Decision → AAR → Feedback + laporan Markdown

### 🧠 Kepemimpinan & Kurikulum
- **Penilaian 6 dimensi**: kualitas keputusan (25%), kecepatan respons (20%), kolaborasi (15%), komunikasi krisis (15%), integritas (10%), manajemen risiko (15%) — auto-scoring + manual, grade A–E
- **Dashboard Pimpinan**: KPI, chart dimensi, ranking peserta
- **Kurikulum Sespimmen/Sespimti**: 3 level, 10 mapping skenario, progress peserta

### 📱 Krisis & Media Sosial
- **Monitoring media sosial** — platform (X/FB/IG/TikTok/WA), jenis konten, sentimen
- **Deteksi hoax & rumor** otomatis (rule-based, offline)
- **Komunikasi krisis** — siaran pers, briefing media, klarifikasi (draf/terbit)
- **Analitik/AI** — ringkasan situasi otomatis, rekomendasi keputusan, deteksi anomali

### 📤 Export & Data
- **Export CSV** laporan simulasi
- **Export briefing** Markdown per simulasi
- **Scenario Package Engine** — manifest.json + orbat + injects.csv + scoring.json (versioned)
- **47 preset wilayah**: 34 provinsi + 7 skenario nasional + 3 strategis (Natuna, Papua, Timor Timur)
- **35 tipe bencana**: 26 bencana + 4 siber/sosial + 5 militer

---

## 🏃 Quick Start — Install & Deploy

### Syarat
- Docker & Docker Compose terinstall
- Port 8080, 8081, 3306 kosong

### Install

```bash
# 1. Clone repo
git clone https://github.com/vanderstark/ccc-laravel-docker.git
cd ccc-laravel-docker

# 2. Salin environment
cp .env.example .env
# (opsional) edit DB_PASSWORD di .env

# 3. Build & start semua service (~2-3 menit pertama)
docker-compose up -d --build

# 4. Tunggu container siap
docker-compose ps
# Pastikan semua "Up" (healthy)

# 5. Install dependencies & jalankan migration
docker-compose exec app composer install
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force

# 6. Buka aplikasi di browser
# http://localhost:8080
```

### Login Default

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@ccc.test` | `admin123` |
| **Operator** | *(daftar manual)* | *(pilih role)* |
| **Viewer** | *(daftar manual)* | *(pilih role)* |

> Klik **"Daftar"** di halaman login untuk membuat user baru dengan role Operator/Viewer.

### Database Manager — Adminer

| Field | Value |
|-------|-------|
| URL | `http://localhost:8081` |
| Server | `db` |
| User | `ccc_user` |
| Password | `secret` (atau sesuai `DB_PASSWORD` di `.env`) |
| Root | `root` / `rootpass` |

> ⚠️ **Ganti password default** sebelum production!

---

## 🧩 Panduan Penggunaan — 8 Menu TFG

### Menu 1: Latihan (Sesi Latihan)
1. Klik menu **Latihan** → **Sesi Latihan**
2. Klik **"Buat Sesi Baru"** → isi nama, kode, pilih skenario + wilayah, tentukan objectives & ROE
3. Sesi dibuat dalam status **DRAFT** → klik **"Mulai Briefing"**
4. Setelah briefing selesai → klik **"Mulai Latihan"** (status: RUNNING, T+ timer dimulai)
5. Di sepanjang sesi: catat keputusan via **Log Keputusan**, kirim inject via **EXCON**
6. Klik **"Pause"** jika perlu jeda → klik **"Mulai Latihan"** lagi untuk melanjutkan
7. Klik **"Akhiri Sesi"** setelah selesai → status: ENDED

### Menu 2: EXCON (Inject & Fog of War)
1. Dari halaman sesi → klik **"EXCON"** di Inject Queue
2. **Tambah Inject**: isi kode (INJ-01), judul, pesan, pilih satker yang menerima, atur waktu T+
3. Klik **"Kirim"** pada inject untuk mengirimkannya ke satker terkait
4. Klik menu **Fog of War Control** → toggle ON/OFF per satker
5. **ON** = satker tidak melihat informasi (fog aktif) | **OFF** = jelas

### Menu 3: Operasi (Order Board & ORBAT)
1. Dari halaman sesi → klik **"Operasi"** (Order Board)
2. **Tambah Order**: isi nomor (OP-001), pilih jenis, tulis isi, tentukan tujuan satker
3. Klik **"ORBAT Board"** untuk melihat/mengupdate kekuatan 7 satker
4. Update status satker: Siaga → Bergerak → Bertugas → Pulang

### Menu 4: Peta Komando
1. Klik menu **Peta** di navbar
2. Lihat marker unit, insiden, aset berdasarkan sesi aktif
3. Toggle layer marker, zona, route
4. Live sync: semua perubahan muncul otomatis (polling 10 detik)

### Menu 5: Video Wall (COP Kiosk)
1. Buka URL: `http://localhost:8080/wall/{session_id}` (tanpa login)
2. Tampilan peta penuh dengan overlay: nama sesi, T+ timer, status, ticker inject
3. Ideal untuk **videotron** atau **layar command center**
4. Auto-refresh setiap 5 detik

### Menu 6: Replay & Heatmap (AAR)
1. Setelah sesi ENDED → klik **"Replay & AAR"** di halaman sesi
2. **Replay Player**: gunakan slider atau tombol speed (1×–8×) untuk memutar ulang
3. **Timeline Anotasi**: lihat semua event (inject + keputusan) berurutan
4. **Heatmap**: visualisasi pergerakan unit di peta
5. **AAR Workflow**: klik **"AAR Workflow"** untuk evaluasi 5 tahap + laporan Markdown

### Menu 7: Kepemimpinan
1. Klik menu **Kepemimpinan** → **Dashboard Pimpinan**
2. Lihat KPI, chart dimensi, ranking peserta
3. Klik **"Penilaian Baru"** → pilih peserta, isi 6 dimensi (atau biarkan auto-scoring)
4. Lihat hasil: grade A–E berdasarkan total skor

### Menu 8: Kurikulum Sespimmen/Sespimti
1. Klik menu **Kurikulum** → lihat 3 level + 10 mapping skenario
2. Catat **progress peserta**: status (belum/berlangsung/selesai) + skor

---

## 🗺️ Peta Offline (Leaflet Tiles)

```bash
cd app
python3 scripts/download-tiles.py
```

- Tile tersimpan di `app/public/leaflet/tiles/{z}/{x}/{y}.png`
- **Unduh sekali saat instalasi** — setelah itu 100% offline
- Penting untuk ruang command center **air-gapped** (tidak ada internet)

---

## ⚙️ Konfigurasi (.env)

| Env | Default | Deskripsi |
|-----|---------|-----------|
| `APP_NAME` | `CCC` | Nama aplikasi |
| `APP_URL` | `http://localhost:8080` | URL publik |
| `DB_HOST` | `db` | Host MySQL (pakai `db` di Docker) |
| `DB_DATABASE` | `ccc_database` | Nama database |
| `DB_USERNAME` | `ccc_user` | User MySQL |
| `DB_PASSWORD` | `secret` | Password MySQL |
| `APP_PORT` | `8080` | Port Nginx |
| `ADMINER_PORT` | `8081` | Port Adminer |
| `APP_KEY` | *(auto-generated)* | Laravel app key |

> Untuk ganti port: edit `APP_PORT` di `.env` lalu `docker-compose up -d`

---

## 🎯 Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend | **Laravel 11** + PHP 8.3-FPM |
| Database | **MySQL 8** (via Docker) |
| Web Server | **Nginx** (via Docker) |
| Peta | **Leaflet 1.x** + MarkerCluster + Heatmap (self-hosted, 0 CDN) |
| UI | **Bootstrap 5** + dark theme |
| DB Manager | **Adminer** (port 8081) |
| AI/Analytics | Rule-based offline (tanpa API eksternal) |

---

## 📁 Struktur Repo

```
├── Dockerfile                    # PHP-FPM 8.3
├── docker-compose.yml            # nginx + mysql + php-fpm + adminer
├── docker/nginx.conf             # Konfigurasi Nginx
├── app/                          # Source kode Laravel (lengkap)
│   ├── app/Http/Controllers/     # 12 controller (Dashboard, Simulasi, Latihan, EXCON, Operasi, Replay, VideoWall, Krisis, Kurikulum, Leadership, AAR, Export, Audit, Marker, Zone, Organization)
│   ├── app/Models/               # 20+ model (Session, Inject, FogOfWar, ORBAT, Order, DecisionLog, MovementLog, ScenarioPackage, dll)
│   ├── app/Services/             # AnalitikAIService, LeadershipAssessmentService, KomunikasiKrisisService, ExportService, Impact Engine
│   ├── database/migrations/      # 15+ migrasi (tabel utama + TFG core)
│   ├── database/seeders/         # 35 tipe bencana, 47 preset, 45 perang, kurikulum, disaster type
│   ├── resources/views/          # 25+ blade view (dashboard, peta, taktis, latihan, excon, operasi, replay, videowall, leadership, AAR, krisis, kurikulum)
│   ├── routes/web.php            # 80+ route
│   └── public/leaflet/           # Leaflet self-hosted + tiles
├── database.sql                  # Seed MySQL lengkap
├── .env.example
└── README.md
```

---

## 🔐 Checklist Keamanan (Production)

1. ✅ Ganti `DB_PASSWORD`, `rootpass`, dan password user admin
2. ✅ Set `APP_DEBUG=false` di `.env`
3. ✅ Gunakan HTTPS (Let's Encrypt via Certbot / nginx reverse proxy)
4. ✅ Jangan expose Adminer ke publik — batasi via firewall/nginx
5. ✅ Backup database rutin:
   ```bash
   docker exec ccc-mysql mysqldump -u root -p ccc_database > backup_$(date +%F).sql
   ```
6. ✅ Ganti session driver ke Redis/Database (bukan file) untuk multi-server
7. ✅ Rate limiting di login: `Route::middleware('throttle:5,1')`

---

## 🆘 Troubleshooting

| Masalah | Solusi |
|---------|--------|
| `Permission denied storage/` | `docker-compose exec app chown -R www-data:www-data storage bootstrap/cache` |
| DB connection refused | `docker-compose ps` → pastikan `db` status `healthy` |
| Port 8080 sudah dipakai | Ganti `APP_PORT` di `.env` → `docker-compose up -d` |
| Peta kosong / tile tidak muncul | `docker-compose exec app php artisan view:clear` lalu jalankan `python3 scripts/download-tiles.py` |
| Route 404 | `docker-compose exec app php artisan route:clear` |
| Ingin reset database | `docker-compose down -v` → `docker-compose up -d --build` (data hilang!) |
| Migrasi gagal | `docker-compose exec app php artisan migrate:fresh --seed --force` |
| CSS/JS tidak muncul | `docker-compose exec app php artisan view:cache` |

---

## 📊 Statistik Sistem

| Item | Jumlah |
|------|--------|
| Tipe bencana | 35 (26 bencana + 4 siber + 5 militer) |
| Perang historis | 45 |
| Preset wilayah | 47 (34 provinsi + 7 nasional + 3 strategis + 3 default) |
| Model Laravel | 20+ |
| Controller | 12+ |
| Route | 80+ |
| View Blade | 25+ |
| Migrasi database | 15+ |
| Service (offline AI) | 5 (AnalitikAI, Leadership, Krisis, Export, Impact) |
| Menu TFG | 8 (Latihan, Peta, Objek, Operasi, Situasi, EXCON, AAR, Sistem) |

---

© 2026 Crisis Command Center — Akademi Kepolisian · Tactical Floor Game Command Center