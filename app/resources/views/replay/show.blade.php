@extends('layouts.app')
@section('title', 'Replay & AAR')
@section('header', '<i class="bi bi-camera-replay"></i> Replay Sesi & Heatmap')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Sesi: {{ $session->nama }} <small class="text-muted">({{ $session->kode }})</small></h5>
    <div>
        <a href="{{ route('latihan.show', $session) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('aar.create', ['session' => $session->id]) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-clipboard-check"></i> AAR Workflow
        </a>
    </div>
</div>

<div class="row">
    {{-- Player area --}}
    <div class="col-lg-8 mb-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-play-circle"></i> Replay Player</span>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-outline-light" onclick="replaySpeed(1)">1×</button>
                    <button class="btn btn-sm btn-outline-light" onclick="replaySpeed(2)">2×</button>
                    <button class="btn btn-sm btn-outline-light" onclick="replaySpeed(4)">4×</button>
                    <button class="btn btn-sm btn-outline-light" onclick="replaySpeed(8)">8×</button>
                </div>
            </div>
            <div class="card-body">
                <div id="replayMap" style="height:400px;border-radius:8px;background:#0d1117"></div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button class="btn btn-outline-light btn-sm" onclick="replayJump(-10)"><i class="bi bi-skip-backward"></i> -10s</button>
                    <input type="range" id="replaySlider" class="form-range w-50" min="0" max="100" value="0" oninput="replaySeek(this.value)">
                    <span class="text-muted small" id="replayTime">T+00:00</span>
                    <button class="btn btn-outline-light btn-sm" onclick="replayJump(10)">+10s <i class="bi bi-skip-forward"></i></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="col-lg-4 mb-3">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-clock-history"></i> Timeline Anotasi</div>
            <div class="card-body" style="max-height:480px;overflow-y:auto">
                @forelse ($timeline as $ev)
                    <div class="border-start border-2 {{ $ev['type'] === 'inject' ? 'border-warning' : 'border-info' }} ps-3 py-2 mb-2">
                        <div class="small">
                            <span class="badge bg-{{ $ev['type'] === 'inject' ? 'warning text-dark' : 'info' }}">T+{{ gmdate('i:s', $ev['t_plus_sec']) }}</span>
                            <span class="badge bg-secondary">{{ $ev['satker'] }}</span>
                        </div>
                        <div class="fw-bold mt-1">{{ $ev['title'] }}</div>
                        <div class="small text-muted">{{ $ev['detail'] }}</div>
                    </div>
                @empty
                    <p class="text-muted">Belum ada event dalam timeline. Inject & keputusan akan muncul di sini.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Heatmap section --}}
<div class="card">
    <div class="card-header"><i class="bi bi-fire"></i> Heatmap Pergerakan Unit</div>
    <div class="card-body">
        <div id="heatmapMap" style="height:350px;border-radius:8px;background:#0d1117"></div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
<style>
    .leaflet-container { background:#0d1117; }
    .leaflet-popup-content-wrapper { background:#161b22; color:#e6edf3; }
    .leaflet-popup-tip { background:#161b22; }
</style>
<script>
// ===== REPLAY PLAYER (sederhana: visualisasi timeline) =====
const events = @json($timeline);
const replayMap = L.map('replayMap', { center: [-2.5, 118], zoom: 5, zoomControl: true });
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '' }).addTo(replayMap);

let replayIndex = 0;
let replayTimer = null;
const maxT = Math.max(...events.map(e => e.t_plus_sec), 1);

function updateReplay() {
    document.getElementById('replayTime').textContent = 'T+' + formatTime(replayIndex);
    document.getElementById('replaySlider').value = (replayIndex / maxT * 100).toFixed(1);

    // Show events up to current index
    const visible = events.filter(e => e.t_plus_sec <= replayIndex);
    const marker = L.circleMarker([-2.5, 118], { radius: 8, color: '#58a6ff', fillColor: '#58a6ff', fillOpacity: 0.3 }).addTo(replayMap);
    setTimeout(() => marker.remove(), 1500);
}

function replaySpeed(mult) {
    clearInterval(replayTimer);
    const step = Math.max(1, Math.round(maxT / 100));
    replayTimer = setInterval(() => {
        replayIndex = Math.min(maxT, replayIndex + step * mult);
        updateReplay();
        if (replayIndex >= maxT) clearInterval(replayTimer);
    }, 100);
}

function replayJump(sec) {
    replayIndex = Math.max(0, Math.min(maxT, replayIndex + sec));
    updateReplay();
}

function replaySeek(val) {
    replayIndex = val / 100 * maxT;
    updateReplay();
}

function formatTime(t) {
    const h = Math.floor(t/3600), m = Math.floor(t%3600/60), s = Math.floor(t%60);
    return [h,m,s].map(n => String(n).padStart(2,'0')).join(':');
}

// ===== HEATMAP =====
const heatMap = L.map('heatmapMap', { center: [-2.5, 118], zoom: 5, zoomControl: true });
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '' }).addTo(heatMap);

fetch('{{ route('replay.heatmap', $session) }}')
    .then(r => r.json())
    .then(data => {
        if (data.points && data.points.length > 0) {
            L.heatLayer(data.points, { radius: 25, blur: 20, maxZoom: 17, max: 1.0, gradient: {0.2:'#1f6feb',0.4:'#58a6ff',0.6:'#d29922',0.8:'#f0883e',1.0:'#f85149'} }).addTo(heatMap);
        } else {
            document.querySelector('#heatmapMap').insertAdjacentHTML('beforeend',
                '<div class="text-center text-muted p-4">Belum ada data pergerakan unit.</div>');
        }
    });

// Start replay at 1x
replaySpeed(1);
</script>
@endsection