# 🚨 Crisis Command Center (CCC) — Laravel Docker

**Command Center Digital untuk Simulasi Bencana, Operasi Militer & Latihan Kepemimpinan** — berbasis **Laravel 11** + **Docker Compose** (Nginx + PHP-FPM + MySQL 8).

Dikembangkan sesuai spesifikasi **Tactical Floor Game Command Center** & **Rapat Pendirian Laboratorium Kepemimpinan Digital Polri** — offline-first (0 CDN), siap air-gapped.

---

## 📦 Fitur Lengkap

### 🎮 Simulasi & Skenario
| Fitur | Detail |
|-------|--------|
| **35 tipe bencana & militer** | 26 bencana Indonesia (gempa, tsunami, gunung api, banjir, karhutla, dll) + 4 keamanan siber/sosial (serangan siber, disinformasi, krisis kepercayaan, agenda nasional) + 5 operasi militer |
| **45 perang historis** | Konflik & operasi militer bersejarah (replay/pembelajaran) |
| **47 preset wilayah** | **Seluruh Indonesia**: 34 provinsi + 7 skenario nasional + 3 preset strategis (Natuna, Papua, Timor) — masing-masing dengan koordinat, penduduk, luas, tipe bencana khas & parameter khusus |
| **Impact Engine** | Perhitungan dampak otomatis: 7 class, 4 fase (response → recovery → reconstruction → mitigation), berbasis magnitude/radius/population |
| **Alokasi sumber daya** | Otomatis — personel, logistik, alat berat, medis |

### 🗺️ Peta Komando (Tactical Map)
- **Leaflet offline** — self-hosted tiles (0 CDN), unduh sekali → 100% offline
- **Marker** unit / insiden / aset (CRUD + tampil di peta)
- **Zona / Route / Objective** (CRUD + layer peta)
- **MarkerCluster + Heatmap**
- **Live sync** — polling otomatis 10 detik, semua layar sinkron
- **Replay / After Action Review** — timeline + snapshot per langkah

### 👥 Multi-User & Keamanan
- **3 role**: Admin / Operator / Viewer (Role-Based Access Control)
- Login + register + middleware auth
- **Log Aktivitas & Audit Trail** — semua aksi tercatat

### 🧠 Modul Kepemimpinan (Lab Digital Polri)
- **Penilaian 6 dimensi**: kualitas keputusan, kecepatan respons, kolaborasi, komunikasi krisis, integritas, manajemen risiko (auto-scoring + manual, grade A–E)
- **Dashboard Pimpinan**: KPI, chart dimensi, ranking peserta
- **AAR Workflow**: Briefing → Simulation → Decision → AAR → Feedback (5 tahap) + laporan Markdown
- **Komunikasi Krisis & Media Sosial**: monitoring sentimen, deteksi hoax/rumor otomatis (rule-based), siaran pers, briefing media
- **Analitik/AI**: ringkasan situasi otomatis, rekomendasi keputusan, prediksi tren kinerja (semua offline, tanpa API eksternal)
- **Kurikulum Sespimmen/Sespimti**: 3 level pendidikan, 10 mapping skenario, progress peserta

### 📤 Export & Integrasi
- **Export CSV** laporan simulasi
- **Export briefing** Markdown per simulasi
- **Integrasi organisasi**: POLRI, HANKAM, PEMDA, BNPB

---

## 🏃 Quick Start (Docker)

```bash
# 1. Clone repo
git clone https://github.com/vanderstark/ccc-laravel-docker.git
cd ccc-laravel-docker

# 2. Salin environment
cp .env.example .env
# (opsional) edit DB_PASSWORD di .env

# 3. Build & start semua service
docker-compose up -d --build

# 4. Tunggu container siap (~30 detik pertama)
docker-compose ps

# 5. Buka aplikasi
# http://localhost:8080
```

### 🔑 Login Awal

| Role | Cara |
|------|------|
| **Admin** | `admin@ccc.test` / `admin123` (sudah di-seed) |
| **User baru** | Klik **"Daftar"** → pilih role (admin/operator/viewer) → isi form |

### 🗄️ Database Manager — Adminer
- URL: `http://localhost:8081`
- Server: `db`
- User: `ccc_user` / Password: `secret` (atau sesuai `DB_PASSWORD` di `.env`)
- Root: `root` / Password: `rootpass`

> ⚠️ **Ganti password default** sebelum production!

---

## 🧩 Alur Penggunaan Aplikasi

### 1️⃣ Jalankan Simulasi Bencana
1. Login → menu **Simulasi**
2. Klik **"Buat Simulasi Baru"**
3. Pilih **tipe bencana** (35 tersedia) & **preset wilayah** (47 tersedia — mis. "Aceh" untuk tsunami, "Riau" untuk karhutla)
4. Isi parameter (magnitude, radius, dll) → **"Jalankan Simulasi"**
5. Lihat **perhitungan dampak otomatis** + rencana aksi 4 fase

### 2️⃣ Peta Komando (Real-time)
1. Menu **Peta** → pilih preset wilayah / simulasi aktif
2. Toggle layer: **Unit / Insiden / Aset / Zona**
3. Tambah marker via menu **Taktis → Marker** (pilih tipe: unit/incident/asset)
4. Tambah zona/route via **Taktis → Zona**
5. Semua layar tersinkron otomatis (polling 10 detik)

### 3️⃣ Latihan Kepemimpinan
1. Menu **Kepemimpinan** → **Buat Penilaian**
2. Pilih peserta, simulasi, nilai 6 dimensi (atau biarkan auto-scoring)
3. Lihat **Dashboard Pimpinan**: ranking, chart, tren
4. Menu **AAR**: catat 5 tahap (briefing → sim → decision → AAR → feedback)
5. **Export laporan** AAR / CSV

### 4️⃣ Krisis & Media Sosial
1. Menu **Krisis & Medsos**
2. Tambah konten medsos → **deteksi hoax/rumor otomatis** + sentimen
3. Buat **siaran pers / klarifikasi** → terbitkan
4. Lihat **rekomendasi AI** (ringkasan situasi + tindakan prioritas)

### 5️⃣ Kurikulum (Sespimmen/Sespimti)
1. Menu **Kurikulum** → lihat 3 level + mapping skenario
2. Catat **progress peserta** (belum/berlangsung/selesai + skor)

---

## 🗺️ Peta Offline (Leaflet)

```bash
cd app
python3 scripts/download-tiles.py
```

- Tile tersimpan di `app/public/leaflet/tiles/{z}/{x}/{y}.png`
- **Unduh sekali saat instalasi** — setelah itu aplikasi berjalan **100% offline** (penting untuk ruang command center air-gapped)

---

## ⚙️ Konfigurasi (.env)

| Env | Default | Deskripsi |
|-----|---------|-----------|
| `APP_NAME` | CCC | Nama aplikasi |
| `APP_URL` | http://localhost:8080 | URL publik |
| `DB_HOST` | `db` | Host MySQL (pakai `db` di Docker) |
| `DB_DATABASE` | `ccc_database` | Nama database |
| `DB_USERNAME` | `ccc_user` | User MySQL |
| `DB_PASSWORD` | `secret` | Password MySQL |
| `APP_PORT` | `8080` | Port Nginx |
| `ADMINER_PORT` | `8081` | Port Adminer |

---

## 🎯 Teknologi

- **Laravel 11** + PHP 8.3-FPM
- **MySQL 8** (via Docker)
- **Nginx** (via Docker)
- **Leaflet 1.x** + MarkerCluster + Heatmap (self-hosted, 0 CDN)
- **Bootstrap 5** + dark theme
- **Adminer** (database manager)

---

## 📁 Struktur Repo

```
├── Dockerfile              # PHP-FPM 8.3
├── docker-compose.yml      # nginx + mysql + php-fpm + adminer
├── docker/
│   └── nginx.conf          # Konfigurasi Nginx
├── app/                    # Source kode Laravel (lengkap)
│   ├── app/                # Models, Controllers, Services
│   ├── database/           # Migrations + Seeders (47 preset, 35 tipe, 45 perang)
│   ├── resources/views/    # Blade views (dashboard, peta, taktis, leadership, AAR, krisis, kurikulum)
│   ├── routes/web.php      # 58 route
│   └── public/leaflet/     # Leaflet self-hosted
├── database.sql            # Seed MySQL lengkap
├── .env.example
└── README.md
```

---

## 🔐 Checklist Keamanan (Production)

1. Ganti `DB_PASSWORD`, `rootpass`, dan password user admin
2. Set `APP_DEBUG=false`
3. Gunakan HTTPS (Let's Encrypt via Certbot)
4. Jangan expose Adminer ke publik — batasi via firewall/nginx
5. Backup database rutin: `docker exec ccc-mysql mysqldump -u root -p ccc_database > backup.sql`

---

## 🆘 Troubleshooting

| Masalah | Solusi |
|---------|--------|
| `Permission denied storage/` | `sudo chown -R www-data:www-data storage bootstrap/cache` |
| DB connection refused | Cek container: `docker-compose ps` → pastikan `db` healthy |
| Port 8080 sudah dipakai | Ganti `APP_PORT` di `.env` → `docker-compose up -d` |
| Peta kosong / tile tidak muncul | Jalankan `python3 scripts/download-tiles.py` di folder `app/` |
| Ingin reset database | `docker-compose down -v` lalu `docker-compose up -d --build` (data hilang!) |

---

© 2026 Crisis Command Center — Akademi Kepolisian
