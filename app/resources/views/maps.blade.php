@extends('layouts.app')
@section('title', 'Peta Komando')

@push('styles')
<link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}">
<link rel="stylesheet" href="{{ asset('leaflet/leaflet.markercluster.css') }}">
<link rel="stylesheet" href="{{ asset('leaflet/MarkerCluster.Default.css') }}">
<style>
    #map { height: 72vh; border-radius: 12px; border: 1px solid #30363d; }
    .leaflet-container { background: #0d1117; }
    .leaflet-popup-content-wrapper { background: #161b22; color: #e6edf3; border-radius: 8px; }
    .leaflet-popup-tip { background: #161b22; }
    .leaflet-control-zoom a { background: #161b22; color: #e6edf3; border-color: #30363d; }
    .leaflet-control-zoom a:hover { background: #21262d; }
    .layer-panel { background: #161b22; padding: 10px 14px; border-radius: 10px; border: 1px solid #30363d; }
    .live-dot { width: 10px; height: 10px; border-radius: 50%; background: #3fb950; display: inline-block; animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
    .marker-unit { color: #1f6feb; font-size: 1.3em; }
    .marker-incident { color: #f85149; font-size: 1.3em; }
    .marker-asset { color: #d29922; font-size: 1.3em; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Peta Komando <span class="live-dot ms-2" id="liveIndicator" title="Live Sync" style="display:none;"></span></h4>
    <div>
        <a href="{{ route('simulations.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus me-1"></i>Simulasi</a>
        <a href="{{ route('tactical.markers') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-geo-alt me-1"></i>Markers</a>
        <a href="{{ route('tactical.zones') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-map me-1"></i>Zones</a>
        <a href="{{ route('export.csv') }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-file-earmark-spreadsheet me-1"></i>Export</a>
    </div>
</div>

<div class="card">
    <div class="card-body p-2">
        <div id="map"></div>
    </div>
</div>

<div class="row g-3 mt-2">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header fw-bold"><i class="bi bi-list-check me-1"></i>Preset Wilayah</div>
            <div class="card-body py-2">
                @foreach($presets ?? [] as $p)
                <button class="btn btn-sm btn-outline-info me-1 mb-1 preset-btn" data-lat="{{ $p->lat }}" data-lon="{{ $p->lon }}" data-zoom="{{ $p->zoom }}">{{ $p->nama }}</button>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="layer-panel">
            <h6 class="mb-2"><i class="bi bi-layers me-1"></i>Layers</h6>
            <div class="form-check"><input class="form-check-input" type="checkbox" id="layerSim" checked><label class="form-check-label" for="layerSim">Simulasi</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" id="layerUnit" checked><label class="form-check-label" for="layerUnit">Unit (POLRI/HANKAM)</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" id="layerIncident" checked><label class="form-check-label" for="layerIncident">Incident</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" id="layerAsset"><label class="form-check-label" for="layerAsset">Asset</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" id="layerZones"><label class="form-check-label" for="layerZones">Zona / Route</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" id="layerHeat"><label class="form-check-label" for="layerHeat">Heatmap</label></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="layer-panel">
            <h6 class="mb-2"><i class="bi bi-arrow-repeat me-1"></i>Live Sync</h6>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="liveSync">
                <label class="form-check-label" for="liveSync">Auto-refresh (10 detik)</label>
            </div>
            <small class="text-muted d-block" id="lastSync">Belum aktif</small>
            <div class="mt-2">
                <a href="{{ route('api.sync') }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="bi bi-arrow-clockwise me-1"></i>Fetch Now</a>
                <a href="{{ route('api.timeline') }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-clock-history me-1"></i>Timeline</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('leaflet/leaflet.js') }}"></script>
<script src="{{ asset('leaflet/leaflet.markercluster.js') }}"></script>
<script>
const map = L.map('map').setView([-2.5, 118.0], 5);
L.tileLayer('{{ asset("leaflet/tiles/{z}/{x}/{y}.png") }}', { maxZoom:18, attribution:'&copy; OpenStreetMap | CCC' }).addTo(map);

// Layer groups
const simMarkers = L.markerClusterGroup();
const unitMarkers = L.layerGroup();
const incidentMarkers = L.layerGroup();
const assetMarkers = L.layerGroup();
const zonesGroup = L.layerGroup();
const heatPoints = [];

// Simulasi markers (dari server)
@if(isset($simulations))
@foreach($simulations as $s)
@if($s->lat && $s->lon)
    L.marker([{{ $s->lat }}, {{ $s->lon }}], {
        icon: L.divIcon({ className:'', html:'<i class="bi bi-{{ $s->disasterType?->icon ?? "exclamation-triangle" }} fs-4" style="color:{{ $s->alert_level=="merah"?"#f85149":($s->alert_level=="oranye"?"#d29922":($s->alert_level=="kuning"?"#d29922":"#3fb950")) }};text-shadow:0 0 6px #000"></i>', iconSize:[28,28] })
    }).bindPopup(`<strong>#{{ $s->id }} - {{ $s->disasterType?->nama }}</strong><br>{{ $s->location }}<br><span class="badge bg-{{ ['merah'=>'danger','oranye'=>'warning','kuning'=>'info','hijau'=>'success'][$s->alert_level] ?? 'secondary' }}">{{ $s->alert_level }}</span><br><small>{{ $s->created_at?->format('d M Y H:i') }}</small>`).addTo(simMarkers);
    heatPoints.push([{{ $s->lat }}, {{ $s->lon }}, 0.5]);
@endif
@endforeach
@endif

map.addLayer(simMarkers);
let heatLayer;
if (heatPoints.length) { heatLayer = L.heatLayer(heatPoints, { radius:25, blur:15, maxZoom:12 }).addTo(map); }

// Preset
document.querySelectorAll('.preset-btn').forEach(b => b.addEventListener('click', () => map.setView([b.dataset.lat, b.dataset.lon], parseInt(b.dataset.zoom)||7)));

// Layer toggles
document.getElementById('layerSim').addEventListener('change', e => e.target.checked ? map.addLayer(simMarkers) : map.removeLayer(simMarkers));
document.getElementById('layerUnit').addEventListener('change', e => e.target.checked ? map.addLayer(unitMarkers) : map.removeLayer(unitMarkers));
document.getElementById('layerIncident').addEventListener('change', e => e.target.checked ? map.addLayer(incidentMarkers) : map.removeLayer(incidentMarkers));
document.getElementById('layerAsset').addEventListener('change', e => e.target.checked ? map.addLayer(assetMarkers) : map.removeLayer(assetMarkers));
document.getElementById('layerZones').addEventListener('change', e => e.target.checked ? map.addLayer(zonesGroup) : map.removeLayer(zonesGroup));
document.getElementById('layerHeat').addEventListener('change', e => { if(heatLayer) e.target.checked ? map.addLayer(heatLayer) : map.removeLayer(heatLayer); });

// Live sync
let syncInterval;
document.getElementById('liveSync').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('liveIndicator').style.display = 'inline-block';
        fetchSync();
        syncInterval = setInterval(fetchSync, 10000);
    } else {
        document.getElementById('liveIndicator').style.display = 'none';
        clearInterval(syncInterval);
    }
});

async function fetchSync() {
    try {
        const res = await fetch('{{ route("api.sync") }}');
        const data = await res.json();
        // Update markers
        unitMarkers.clearLayers(); incidentMarkers.clearLayers(); assetMarkers.clearLayers(); zonesGroup.clearLayers();
        data.markers.forEach(m => {
            const icon = L.divIcon({ className:'', html:'<i class="bi bi-'+(m.type==='unit'?'geo-alt':'exclamation-triangle')+' marker-'+m.type+'" style="text-shadow:0 0 6px #000"></i>', iconSize:[26,26] });
            const mk = L.marker([m.lat, m.lon], { icon }).bindPopup('<b>'+m.nama+'</b><br>'+m.type+'<br><small>'+m.status+'</small>');
            if (m.type==='unit') mk.addTo(unitMarkers);
            else if (m.type==='incident') mk.addTo(incidentMarkers);
            else mk.addTo(assetMarkers);
        });
        // Update zones
        data.zones.forEach(z => {
            if (z.geometry && z.geometry.length) {
                const latlngs = z.geometry.map(p => [parseFloat(p[0]), parseFloat(p[1])]);
                if (z.jenis==='route') L.polyline(latlngs, { color: z.warna||'#1f6feb', weight:3 }).bindPopup(z.nama).addTo(zonesGroup);
                else L.polygon(latlngs, { color: z.warna||'#1f6feb', fillOpacity:0.25 }).bindPopup(z.nama).addTo(zonesGroup);
            }
        });
        document.getElementById('lastSync').textContent = 'Terakhir: ' + new Date().toLocaleTimeString('id-ID');
    } catch(e) { console.error('Sync error:', e); }
}

// Map click → copy coord
map.on('click', e => L.popup().setLatLng(e.latlng).setContent('<small>'+e.latlng.lat.toFixed(5)+', '+e.latlng.lng.toFixed(5)+'</small>').openOn(map));
</script>
@endpush
