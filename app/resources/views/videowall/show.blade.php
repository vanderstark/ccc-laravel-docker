<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CCC Video Wall — COP Kiosk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        html, body, #map { height:100%; width:100%; overflow:hidden; }
        body { font-family:'Segoe UI',system-ui,sans-serif; background:#0f1923; color:#e6edf3; }
        #map { position:absolute; top:0; left:0; z-index:1; }
        .overlay { position:absolute; z-index:10; pointer-events:none; }
        .top-bar { top:0; left:0; right:0; padding:1rem 2rem; display:flex; justify-content:space-between; align-items:center; background:linear-gradient(180deg,rgba(15,25,35,0.95),transparent); pointer-events:auto; }
        .bottom-bar { bottom:0; left:0; right:0; padding:1rem 2rem; display:flex; justify-content:space-between; align-items:center; background:linear-gradient(0deg,rgba(15,25,35,0.95),transparent); pointer-events:auto; }
        .session-title { font-size:1.8rem; font-weight:700; letter-spacing:-0.5px; }
        .t-plus { font-size:3rem; font-weight:700; font-variant-numeric:tabular-nums; color:#58a6ff; font-family:monospace; }
        .status-badge { font-size:1rem; padding:0.5rem 1.2rem; }
        .side-panel { display:flex; flex-direction:column; gap:0.5rem; }
        .stat { background:rgba(22,27,34,0.95); border:1px solid #30363d; border-radius:8px; padding:0.5rem 1rem; min-width:140px; text-align:center; }
        .stat-label { font-size:0.75rem; text-transform:uppercase; color:#8b949e; }
        .stat-value { font-size:1.25rem; font-weight:600; color:#58a6ff; }
        .inject-ticker { max-width:600px; overflow:hidden; white-space:nowrap; }
        .ticker-content { display:inline-block; padding-left:100%; animation:ticker 20s linear infinite; }
        @keyframes ticker { to { transform:translateX(-100%); } }
        .leaflet-container { background:#0d1117; }
        .leaflet-popup-content-wrapper { background:#161b22; color:#e6edf3; border-radius:8px; }
        .leaflet-popup-tip { background:#161b22; }
        .unit-marker { background:transparent; border:none; }
        .unit-marker-icon { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:bold; color:#fff; border:2px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.5); }
        @media (max-width:768px) {
            .session-title { font-size:1.2rem; }
            .t-plus { font-size:1.5rem; }
            .top-bar, .bottom-bar { padding:0.5rem 1rem; }
        }
    </style>
</head>
<body>
    <div id="map"></div>

    {{-- Top Bar --}}
    <div class="overlay top-bar">
        <div>
            <div class="session-title">{{ $session->nama }} <small class="fw-normal text-muted">{{ $session->kode }}</small></div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="t-plus" id="tplusDisplay">{{ gmdate('H:i:s', $session->t_plus_detik) }}</span>
            @php $badge = match($session->status) { 'running'=>'success','paused'=>'warning','briefing'=>'info','ended'=>'dark',default=>'secondary' }; @endphp
            <span class="badge bg-{{ $badge }} status-badge">{{ strtoupper($session->status) }}</span>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="overlay bottom-bar">
        <div class="d-flex gap-3 side-panel">
            @if ($session->preset)
                <div class="stat"><div class="stat-label">Wilayah</div><div class="stat-value">{{ $session->preset->nama }}</div></div>
            @endif
            <div class="stat"><div class="stat-label">Satker Aktif</div><div class="stat-value">{{ $session->orbatUnits->where('status','!=','pulang')->count() }}</div></div>
            <div class="stat"><div class="stat-label">Inject Terkirim</div><div class="stat-value">{{ $session->injects->where('status','delivered')->count() }}</div></div>
        </div>

        @if ($latest_injects->count())
            <div class="inject-ticker" style="pointer-events:auto">
                <div class="ticker-content text-warning">
                    @foreach ($latest_injects as $inj)
                        <span class="me-4"><i class="bi bi-lightning-charge"></i> {{ $inj->title }} — {{ $inj->message }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize map
        const map = L.map('map', {
            center: [{{ $session->preset ? $session->preset->latitude : -2.5 }}, {{ $session->preset ? $session->preset->longitude : 118 }}],
            zoom: {{ $session->preset ? $session->preset->zoom_level : 5 }},
            zoomControl: false,
            attributionControl: false,
        });

        // Basemap - use local tiles if available, fallback to OSM
        L.tileLayer('/tiles/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map).on('tileerror', function() {
            // Fallback to OSM if local tiles fail
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
        });

        // Unit marker icons per satker
        const satkerColors = {
            ai: '#58a6ff', reserse: '#f78166', brimob: '#d2a8ff',
            lantas: '#79c0ff', sabhara: '#a5d6ff', binmas: '#ffa657',
            manajemen_konflik: '#ff7b72'
        };
        const satkerLabels = {
            ai: 'AI', reserse: 'RES', brimob: 'BRM',
            lantas: 'LTS', sabhara: 'SBH', binmas: 'BNS',
            manajemen_konflik: 'MKF'
        };

        let unitMarkers = {};

        function renderUnits(units) {
            Object.values(unitMarkers).forEach(m => map.removeLayer(m));
            unitMarkers = {};

            units.forEach(u => {
                if (!u.latitude || !u.longitude) return;
                const color = satkerColors[u.satker] || '#58a6ff';
                const label = satkerLabels[u.satker] || u.satker.toUpperCase().slice(0,3);

                const icon = L.divIcon({
                    className: 'unit-marker',
                    html: `<div class="unit-marker-icon" style="background:${color}; border-color:${color};" title="${u.nama_unit} (${u.kekuatan})">${label}</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16],
                });

                const marker = L.marker([u.latitude, u.longitude], { icon })
                    .bindPopup(`<strong>${satkerLabels[u.satker] ?? u.satker}</strong><br>${u.nama_unit}<br>Kekuatan: ${u.kekuatan}<br>Status: ${u.status}`)
                    .addTo(map);
                unitMarkers[u.id] = marker;
            });
        }

        // Initial render
        renderUnits({{ $session->orbatUnits->toJson() }});

        // Poll data
        async function pollData() {
            try {
                const r = await fetch('{{ route('videowall.data', $session) }}');
                const d = await r.json();

                // Update T+ timer
                if (d.session.t_plus_detik !== undefined) {
                    const t = d.session.t_plus_detik;
                    document.getElementById('tplusDisplay').textContent =
                        [Math.floor(t/3600), Math.floor(t%3600/60), t%60].map(n => String(n).padStart(2,'0')).join(':');
                }

                // Update units
                if (d.units) renderUnits(d.units);
            } catch (e) { console.error(e); }
        }

        setInterval(pollData, 5000);
    </script>
</body>
</html>