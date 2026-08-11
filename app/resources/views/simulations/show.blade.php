@extends('layouts.app')

@section('title', 'Hasil Simulasi #'.$simulation->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Hasil Simulasi #{{ $simulation->id }}</h4>
    <a href="{{ route('simulations.create') }}" class="btn btn-primary"><i class="bi bi-plus me-1"></i>Simulasi Baru</a>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="card stat-card"><div class="card-body"><div class="text-muted small">Klasifikasi</div><div class="fs-5 fw-bold">{{ $simulation->classification }}</div></div></div></div>
    <div class="col-md-3"><div class="card stat-card {{ $simulation->alert_level=='merah'?'red':($simulation->alert_level=='oranye'?'orange':($simulation->alert_level=='kuning'?'':'green')) }}"><div class="card-body"><div class="text-muted small">Level Alert</div><div class="fs-5 fw-bold">{{ $simulation->alert_level }}</div></div></div></div>
    <div class="col-md-3"><div class="card stat-card red"><div class="card-body"><div class="text-muted small">Meninggal</div><div class="fs-5 fw-bold">{{ number_format($simulation->estimated_deaths) }}</div></div></div></div>
    <div class="col-md-3"><div class="card stat-card green"><div class="card-body"><div class="text-muted small">Kerusakan Ekonomi</div><div class="fs-5 fw-bold">$ {{ number_format($simulation->economic_damage_usd,0) }}</div></div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-bold"><i class="bi bi-people me-2"></i>Dampak Populasi</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between"><span>Terdampat</span><span class="fw-bold">{{ number_format($simulation->affected_population) }}</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Terluka</span><span class="fw-bold text-warning">{{ number_format($simulation->estimated_injured) }}</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Meninggal</span><span class="fw-bold text-danger">{{ number_format($simulation->estimated_deaths) }}</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Pengungsi</span><span class="fw-bold">{{ number_format($simulation->displaced) }}</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Bangunan Rusak</span><span class="fw-bold">{{ number_format($simulation->damaged_buildings) }}</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Bangunan Hancur</span><span class="fw-bold text-danger">{{ number_format($simulation->destroyed_buildings) }}</span></li>
                </ul>
                <p class="text-muted small mt-2 mb-0">Lokasi: {{ $simulation->location }} · Populasi: {{ number_format($simulation->population) }}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card h-100">
            <div class="card-header fw-bold"><i class="bi bi-box-seam me-2"></i>Sumber Daya</div>
            <div class="card-body">
                @if($simulation->resources)
                <ul class="list-group list-group-flush small">
                    @foreach($simulation->resources as $label => $val)
                    <li class="list-group-item d-flex justify-content-between">{{ $label }}<span class="fw-bold">{{ $val }}</span></li>
                    @endforeach
                </ul>
                @else
                <p class="text-muted">Tidak tersedia.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card h-100">
            <div class="card-header fw-bold"><i class="bi bi-lightning-charge me-2"></i>Aksi (4 Fase)</div>
            <div class="card-body">
                @if($simulation->actions)
                @foreach($simulation->actions as $fase => $items)
                <h6 class="small text-uppercase text-muted">{{ ucfirst(str_replace('_',' ',$fase)) }}</h6>
                <ul class="list-group list-group-flush mb-2">
                    @foreach($items as $i => $item)
                    <li class="list-group-item small py-1">{{ $item }}</li>
                    @endforeach
                </ul>
                @endforeach
                @endif
            </div>
        </div>
    </div>

    @if($simulation->impact_detail)
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header fw-bold"><i class="bi bi-graph-up me-2"></i>Detail Dampak (JSON)</div>
            <div class="card-body">
                <pre class="text-muted small mb-0">{{ json_encode($simulation->impact_detail, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="mt-3">
    <a href="{{ route('maps') }}" class="btn btn-success"><i class="bi bi-geo-alt me-1"></i>Lihat di Peta</a>
    <a href="{{ route('simulations.history') }}" class="btn btn-outline-secondary"><i class="bi bi-clock me-1"></i>Riwayat</a>
</div>
@endsection