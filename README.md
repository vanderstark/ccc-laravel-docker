# Crisis Command Center — Laravel (Docker)

Sistem **Crisis Command Center** berbasis **Laravel 13** dengan **Docker Compose** (PHP-FPM + Nginx + MySQL 8).

## 📦 Fitur
- **31 tipe simulasi** (26 bencana Indonesia + 5 operasi militer)
- **45 perang historis** + **3 preset wilayah** (Natuna, Papua, Timor)
- **Peta Leaflet offline** (self-hosted tiles, 0 CDN)
- **Alokasi sumber daya otomatis** + **rencana aksi 4 fase**

## 🏃 Quick Start (Docker)

```bash
# 1. Clone & masuk ke repo
git clone https://github.com/vanderstark/ccc-laravel-docker.git
cd ccc-laravel-docker

# 2. Salin environment
cp .env.example .env
# Edit .env jika ingin ganti password DB_PASSWORD

# 3. Start semua layanan
docker-compose up -d --build

# 4. Buka di browser
# http://localhost:8080
```

**Login default setelah setup:**
- Akses via tombol "Daftar" di halaman login
- Role dapat dipilih (admin, operator, publik)

**Database Manager — Adminer:**
- Akses: `http://localhost:8081`
- Server: `db`
- User: `ccc_user` / Password: `secret` (atau sesuai `DB_PASSWORD`)
- Root: `root` / Password: `rootpass`

## 🗄️ Deploy Tanpa Docker

```bash
cd app
composer install --no-dev --optimize-autoloader
php artisan key:generate
# Import database
mysql -u root -p -e 'CREATE DATABASE ccc_database CHARACTER SET utf8mb4'
mysql -u root -p ccc_database < ../database.sql
php artisan serve --host=0.0.0.0 --port=8000
```

## 🗺️ Peta Offline (Leaflet)

Tile peta disimpan di folder `app/public/leaflet/tiles/`. Untuk mengunduh tile wilayah Indonesia:

```bash
cd app
python3 scripts/download-tiles.py
```

Tile akan tersimpan di `public/leaflet/tiles/{z}/{x}/{y}.png`.

## 🎯 Teknologi
- **Laravel 13** + PHP 8.3
- **MySQL 8** / SQLite (dev)
- **Leaflet 1.x** + MarkerCluster + Heatmap
- **Bootstrap 5** + custom dark theme

## 📁 Struktur Repo

```
├── Dockerfile          # PHP-FPM 8.3
├── docker-compose.yml  # nginx + mysql + php-fpm
├── docker/
│   └── nginx.conf
├── app/                # Source kode Laravel (copy dari app-core)
├── database.sql        # Seed MySQL (31 sim, 45 war, 3 preset, 3 role)
├── .env.example
└── README.md
```

## ⚙️ Konfigurasi

| Env | Default | Deskripsi |
|-----|---------|-----------|
| `DB_HOST` | `db` | Hostname MySQL (gunakan `db` di Docker) |
| `DB_DATABASE` | `ccc_database` | Nama database |
| `DB_PASSWORD` | (env) | Password MySQL |
| `APP_PORT` | `8080` | Port untuk Nginx |

## 🆘 Bantuan

- Buka `http://localhost:8080`
- Klik "Daftar" → pilih role → isi form
- Klik "Simulasi Baru" → pilih tipe bencana → isi parameter → "Jalankan Simulasi"

© 2026 Crisis Command Center — Akademi Kepolisian