@extends('layouts.app')

@section('title', 'Simulasi Baru')

@section('content')
<div class="mb-4">
    <h4 class="mb-1"><i class="bi bi-plus-circle me-2"></i>Simulasi Bencana & Operasi Militer</h4>
    <p class="text-muted small mb-0">Konfigurasi parameter lalu jalankan simulasi. Hasil akan muncul secara instan.</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('simulations.store') }}" id="simForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Tipe Simulasi <span class="text-danger">*</span></label>
                            <select name="disaster_type" class="form-select @error('disaster_type') is-invalid @enderror" required>
                                <option value="">-- Pilih Tipe --</option>
                                @foreach($disasterTypes as $dt)
                                <option value="{{ $dt->code }}" {{ old('disaster_type')==$dt->code?'selected':'' }}>
                                    {{ $dt->nama }} ({{ $dt->kelompok }})
                                </option>
                                @endforeach
                            </select>
                            @error('disaster_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Preset Wilayah (opsional)</label>
                            <select name="preset_id" class="form-select" id="presetSelect">
                                <option value="">-- Tanpa Preset --</option>
                                @foreach($presets as $p)
                                <option value="{{ $p->id }}" data-lat="{{ $p->lat }}" data-lon="{{ $p->lon }}" data-pop="{{ $p->population }}" data-area="{{ $p->area_km2 }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Skenario Perang (opsional)</label>
                            <select name="war_id" class="form-select">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($wars as $w)
                                <option value="{{ $w->id }}">{{ $w->nama }} ({{ $w->tahun }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12"><hr class="my-1"><small class="text-muted fw-bold">PARAMETER LOKASI & DEMOGRAFI</small></div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Lokasi</label>
                            <input name="location" class="form-control" value="{{ old('location', 'Kota Semarang') }}" placeholder="Nama kota/wilayah">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input name="lat" type="number" step="any" class="form-control" value="{{ old('lat') }}" placeholder="-6.99">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input name="lon" type="number" step="any" class="form-control" value="{{ old('lon') }}" placeholder="110.42">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Populasi</label>
                            <input name="population" type="number" class="form-control" value="{{ old('population', 500000) }}" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Luas (km²)</label>
                            <input name="area_km2" type="number" step="0.1" class="form-control" value="{{ old('area_km2', 50) }}" min="0.1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tipe Area</label>
                            <select name="area_type" class="form-select">
                                <option value="suburb">Suburban (Kota Kecil)</option>
                                <option value="urban" {{ old('area_type')=='urban'?'selected':'' }}>Urban (Kota Besar)</option>
                                <option value="rural" {{ old('area_type')=='rural'?'selected':'' }}>Rural (Pedesaan)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Kepadatan Infra (0-1)</label>
                            <input name="infrastructure_density" type="number" step="0.01" class="form-control" value="{{ old('infrastructure_density', 0.5) }}" min="0" max="1">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Skala Keparahan (0-1)</label>
                            <input name="severity_scale" type="number" step="0.01" class="form-control" value="{{ old('severity_scale', 0.5) }}" min="0" max="1">
                        </div>

                        <div class="col-12"><hr class="my-1"><small class="text-muted fw-bold">PARAMETER KHUSUS PER TIPE (opsional)</small></div>

                        <div id="earthquakeFields" class="col-12 param-fields" style="display:none">
                            <div class="row g-2">
                                <div class="col-md-4"><label class="form-label small">Magnitudo</label><input name="earthquake_magnitude" type="number" step="0.1" class="form-control" value="6.5"></div>
                                <div class="col-md-4"><label class="form-label small">Kedalaman (km)</label><input name="earthquake_depth_km" type="number" step="0.1" class="form-control" value="20"></div>
                                <div class="col-md-4"><label class="form-label small">Jarak Episenter (km)</label><input name="epicenter_distance_km" type="number" step="0.1" class="form-control" value="0"></div>
                            </div>
                        </div>

                        <div id="tsunamiFields" class="col-12 param-fields" style="display:none">
                            <div class="row g-2">
                                <div class="col-md-6"><label class="form-label small">Tinggi Gelombang (m)</label><input name="tsunami_wave_height_m" type="number" step="0.1" class="form-control" value="5"></div>
                                <div class="col-md-6"><label class="form-label small">Jarak Episenter (km)</label><input name="tsunami_epicenter_distance_km" type="number" step="0.1" class="form-control" value="50"></div>
                            </div>
                        </div>

                        <div id="volcanoFields" class="col-12 param-fields" style="display:none">
                            <div class="row g-2">
                                <div class="col-md-6"><label class="form-label small">VEI (0-8)</label><input name="volcano_vei" type="number" class="form-control" value="4"></div>
                                <div class="col-md-6"><label class="form-label small">Jarak Erupsi (km)</label><input name="volcano_eruption_distance_km" type="number" step="0.1" class="form-control" value="10"></div>
                            </div>
                        </div>

                        <div id="floodFields" class="col-12 param-fields" style="display:none">
                            <div class="row g-2">
                                <div class="col-md-6"><label class="form-label small">Kedalaman Banjir (m)</label><input name="flood_depth_m" type="number" step="0.1" class="form-control" value="1.5"></div>
                                <div class="col-md-6"><label class="form-label small">Durasi (jam)</label><input name="flood_duration_hours" type="number" step="0.1" class="form-control" value="24"></div>
                            </div>
                        </div>

                        <div id="fireFields" class="col-12 param-fields" style="display:none">
                            <div class="row g-2">
                                <div class="col-md-4"><label class="form-label small">Luas Area (ha)</label><input name="fire_area_ha" type="number" class="form-control" value="2000"></div>
                                <div class="col-md-4"><label class="form-label small">Kecepatan Angin (km/jam)</label><input name="fire_wind_speed_kmh" type="number" class="form-control" value="25"></div>
                                <div class="col-md-4"><label class="form-label small">Jenis Bahan Bakar</label>
                                    <select name="fire_fuel_type" class="form-select">
                                        <option value="gambut">Gambut (Peat)</option>
                                        <option value="hutan">Hutan (Forest)</option>
                                        <option value="mineral">Mineral</option>
                                        <option value="urban">Perkotaan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="conflictFields" class="col-12 param-fields" style="display:none">
                            <div class="row g-2">
                                <div class="col-md-4"><label class="form-label small">Intensitas Konflik (0-1)</label><input name="conflict_intensity" type="number" step="0.01" class="form-control" value="0.6"></div>
                                <div class="col-md-4"><label class="form-label small">Tipe</label>
                                    <select name="conflict_type" class="form-select">
                                        <option value="conventional">Konvensional</option>
                                        <option value="insurgency">Insurgency</option>
                                        <option value="counter_insurgency">Counter-Insurgency</option>
                                        <option value="urban">Urban Warfare</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="maritimeFields" class="col-12 param-fields" style="display:none">
                            <div class="row g-2">
                                <div class="col-md-4"><label class="form-label small">Ancaman Maritim (0-1)</label><input name="maritime_threat_level" type="number" step="0.01" class="form-control" value="0.7"></div>
                                <div class="col-md-4"><label class="form-label small">Unit AL Lawan</label><input name="enemy_naval_units" type="number" class="form-control" value="5"></div>
                            </div>
                        </div>

                        <div id="airFields" class="col-12 param-fields" style="display:none">
                            <div class="row g-2">
                                <div class="col-md-4"><label class="form-label small">Ancaman Udara (0-1)</label><input name="air_threat_level" type="number" step="0.01" class="form-control" value="0.6"></div>
                                <div class="col-md-4"><label class="form-label small">Pesawat Lawan</label><input name="enemy_aircraft" type="number" class="form-control" value="6"></div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100" id="btnRun">
                                <i class="bi bi-play-circle me-1"></i>Jalankan Simulasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-bold"><i class="bi bi-info-circle me-2"></i>Panduan Cepat</div>
            <div class="card-body small">
                <p><strong>31 Tipe Simulasi Tersedia:</strong></p>
                <p class="mb-1"><span class="badge bg-danger">26 Bencana Alam Indonesia</span></p>
                <p class="mb-1"><span class="badge bg-warning text-dark">5 Operasi Militer</span></p>
                <hr>
                <p class="mb-1">• <strong>Preset</strong>: Natuna, Papua, Timor — parameter otomatis terisi</p>
                <p class="mb-1">• <strong>Skala</strong>: 0 (ringan) → 1 (kritis)</p>
                <p class="mb-0">• <strong>Parameter</strong>: Isi sesuai tipe yang dipilih</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const typeMap = {
    'earthquake':  'earthquakeFields',
    'volcano':     'volcanoFields',
    'flood':       'floodFields',
    'flash_flood': 'floodFields',
    'forest_fire': 'fireFields',
    'conflict':    'conflictFields',
    'maritime':    'maritimeFields',
    'air':         'airFields',
    'combined':    ['conflictFields','maritimeFields','airFields'],
};

document.querySelector('[name="disaster_type"]').addEventListener('change', function(){
    document.querySelectorAll('.param-fields').forEach(el => el.style.display='none');
    const key = this.value;
    const ids = typeMap[key] || [];
    (Array.isArray(ids)?ids:[ids]).forEach(id => { const el=document.getElementById(id); if(el) el.style.display='block'; });
});

document.getElementById('presetSelect').addEventListener('change', function(){
    const opt = this.selectedOptions[0];
    if(!opt) return;
    const lat=opt.dataset.lat, lon=opt.dataset.lon, pop=opt.dataset.pop, area=opt.dataset.area;
    if(lat) document.querySelector('[name="lat"]').value=lat;
    if(lon) document.querySelector('[name="lon"]').value=lon;
    if(pop) document.querySelector('[name="population"]').value=pop;
    if(area) document.querySelector('[name="area_km2"]').value=area;
    if(pop) document.querySelector('[name="location"]').value=opt.text.split('(')[0].trim();
});

document.getElementById('btnRun').addEventListener('click', function(){
    this.disabled=true; this.innerHTML='<i class="bi bi-hourglass-split me-1"></i>Menghitung...';
});
</script>
@endpush