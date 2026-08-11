@extends('layouts.app')
@section('title', 'Peta Komando')

@push('styles')
<link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}">
<link rel="stylesheet" href="{{ asset('leaflet/leaflet.markercluster.css') }}">
<link rel="stylesheet" href="{{ asset('leaflet/MarkerCluster.Default.css') }}">
<style>
    #map { height: 70vh; border-radius: 12px; border: 1px solid #30363d; }
    .leaflet-container { background: #0d1117; }
    .leaflet-popup-content-wrapper { background: #161b22; color: #e6edf3; border-radius: 8px; }
    .leaflet-popup-tip { background: #161b22; }
    .leaflet-control-zoom a { background: #161b22; color: #e6edf3; border-color: #30363d; }
    .leaflet-control-zoom a:hover { background: #21262d; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Peta Simulasi</h4>
    <a href="{{ route('simulations.create') }}" class="btn btn-primary"><i class="bi bi-plus me-1"></i>Simulasi Baru</a>
</div>

<div class="card">
    <div class="card-body">
        <div id="map"></div>
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-bold"><i class="bi bi-list-check me-1"></i>Preset Wilayah</div>
            <div class="card-body">
                @foreach($presets ?? [] as $p)
                <button class="btn btn-sm btn-outline-info me-1 mb-1 preset-btn"
                        data-lat="{{ $p->lat }}" data-lon="{{ $p->lon }}" data-zoom="{{ $p->zoom }}">
                    {{ $p->nama }}
                </button>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-bold"><i class="bi bi-layer-forward me-1"></i>Layer</div>
            <div class="card-body">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="layerMarkers" checked>
                    <label class="form-check-label" for="layerMarkers">Marker Simulasi</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="layerHeat" checked>
                    <label class="form-check-label" for="layerHeat">Heatmap</label>
                </div>
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

L.tileLayer('{{ asset("leaflet/tiles/{z}/{x}/{y}.png") }}', {
    maxZoom: 18,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | CCC Offline'
}).addTo(map);

const markers = L.markerClusterGroup();
const heatPoints = [];

@if(isset($simulations))
@foreach($simulations as $s)
@if($s->lat && $s->lon)
    const m{{ $s->id }} = L.marker([{{ $s->lat }}, {{ $s->lon }}], {
        icon: L.divIcon({
            className: 'custom-marker',
            html: '<i class="bi bi-{{ $s->disasterType?->icon ?? "exclamation-triangle" }} fs-4" style="color:{{ $s->alert_level=="merah"?"#f85149":($s->alert_level=="oranye"?"#d29922":($s->alert_level=="kuning"?"#d29922":"#3fb950")) }}"></i>',
            iconSize: [24, 24]
        })
    }).bindPopup(`
        <strong>#{{ $s->id }} - {{ $s->disasterType?->nama }}</strong><br>
        {{ $s->location }}<br>
        <span class="badge bg-{{ ['merah'=>'danger','oranye'=>'warning','kuning'=>'info','hijau'=>'success'][$s->alert_level] ?? 'secondary' }}">{{ $s->alert_level }}</span>
        <br><small>{{ $s->created_at->format('d M Y H:i') }}</small>
    `);
    markers.addLayer(m{{ $s->id }});
    heatPoints.push([{{ $s->lat }}, {{ $s->lon }}, 0.5]);
@endif
@endforeach
@endfor

map.addLayer(markers);

let heatLayer;
if (heatPoints.length && typeof L.heatLayer === 'function') {
    heatLayer = L.heatLayer(heatPoints, { radius: 25, blur: 15, maxZoom: 12 }).addTo(map);
} else if (heatPoints.length) {
    // simple circles fallback
    heatPoints.forEach(p => L.circleMarker([p[0], p[1]], { radius: 8, fillColor: '#1f6feb', color: '#fff', weight: 1, fillOpacity: 0.6 }).addTo(map));
}

document.querySelectorAll('.preset-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        map.setView([btn.dataset.lat, btn.dataset.lon], parseInt(btn.dataset.zoom) || 7);
    });
});

document.getElementById('layerMarkers').addEventListener('change', e => {
    e.target.checked ? map.addLayer(markers) : map.removeLayer(markers);
});
document.getElementById('layerHeat').addEventListener('change', e => {
    if (heatLayer) e.target.checked ? map.addLayer(heatLayer) : map.removeLayer(heatLayer);
});

map.on('click', e => {
    document.querySelector('[name="lat"]').value = e.latlng.lat.toFixed(4);
    document.querySelector('[name="lon"]').value = e.latlng.lng.toFixed(4);
    L.popup().setLatLng(e.latlng).setContent('Titik ini bisa dipakai untuk simulasi baru').openOn(map);
});
</script>
@endpush