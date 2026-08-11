#!/bin/bash
# =============================================================
# download-tiles.py — Unduh tile OpenStreetMap untuk mode offline
# Jalankan dari root project Laravel:  python3 scripts/download-tiles.py
# =============================================================
import os, sys, urllib.request, json, math, hashlib

OUT = os.path.join(os.path.dirname(__file__), '..', 'public', 'leaflet', 'tiles')
os.makedirs(OUT, exist_ok=True)

# Area Indonesia: bbox [-12, 90, -12, 142]
MINLAT, MAXLAT, MINLON, MAXLON = -12, 6, 90, 142
ZOOM_MIN, ZOOM_MAX = 3, 14
TILE_SIZE = 256
TILE_URL = "https://tile.openstreetmap.org/{z}/{x}/{y}.png"
UA = "CCC-Laravel-DownloadTiles/1.0"

def latlon_to_tile(lat, lon, zoom):
    lat_rad = math.radians(lat)
    n = 2 ** zoom
    x = int((lon + 180.0) / 360.0 * n)
    y = int((1.0 - math.log(math.tan(lat_rad) + 1.0/math.cos(lat_rad)) / math.pi) / 2.0 * n)
    return x, y

total = 0
errors = 0
for z in range(ZOOM_MIN, ZOOM_MAX + 1):
    x_min, y_max = latlon_to_tile(MINLAT, MINLON, z)
    x_max, y_min = latlon_to_tile(MAXLAT, MAXLON, z)
    for x in range(x_min, x_max + 1):
        for y in range(y_min, y_max + 1):
            out_dir = os.path.join(OUT, str(z), str(x))
            out_file = os.path.join(out_dir, f"{y}.png")
            if os.path.exists(out_file):
                continue
            os.makedirs(out_dir, exist_ok=True)
            url = TILE_URL.format(z=z, x=x, y=y)
            try:
                req = urllib.request.Request(url, headers={"User-Agent": UA})
                data = urllib.request.urlopen(req, timeout=10).read()
                with open(out_file, 'wb') as f:
                    f.write(data)
                total += 1
                if total % 50 == 0:
                    print(f"  [+] {total} tiles...")
            except Exception as e:
                errors += 1
                # skip
print(f"Selesai: {total} tiles diunduh, {errors} error")
print(f"Lokasi: {OUT}")
