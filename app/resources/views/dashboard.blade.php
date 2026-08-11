@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-grid-1x2 me-2"></i>Dashboard Komando</h4>
    <a href="{{ route('simulations.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Simulasi Baru</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="text-muted small">Total Simulasi</div>
                <div class="fs-3 fw-bold">{{ number_format($total) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card orange h-100">
            <div class="card-body">
                <div class="text-muted small">Populasi Terdampak</div>
                <div class="fs-3 fw-bold">{{ number_format($totalAffected) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card red h-100">
            <div class="card-body">
                <div class="text-muted small">Total Meninggal</div>
                <div class="fs-3 fw-bold">{{ number_format($totalDeaths) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card green h-100">
            <div class="card-body">
                <div class="text-muted small">Kerugian Ekonomi</div>
                <div class="fs-3 fw-bold">${{ number_format($totalDmg, 0) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header fw-bold"><i class="bi bi-pie-chart me-2"></i>Status Kewaspadaan</div>
            <div class="card-body">
                @php $colors = ['merah'=>'danger','oranye'=>'warning','kuning'=>'info','hijau'=>'success']; @endphp
                @foreach($colors as $key=>$color)
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <span><span class="badge bg-{{ $color }} badge-alert">{{ $key }}</span></span>
                    <span class="fw-bold">{{ $byAlert[$key] ?? 0 }} simulasi</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header fw-bold"><i class="bi bi-clock-history me-2"></i>Simulasi Terbaru</div>
            <div class="card-body">
                <table class="table table-sm align-middle">
                    <thead><tr><th>#</th><th>Tipe</th><th>Lokasi</th><th>Level</th><th>Waktu</th></tr></thead>
                    <tbody>
                        @forelse($recent as $r)
                        <tr>
                            <td>{{ $r['id'] }}</td>
                            <td>{{ $r['tipe'] }}</td>
                            <td>{{ $r['lokasi'] }}</td>
                            <td><span class="badge badge-alert bg-{{ $colors[$r['alert']] ?? 'secondary' }}">{{ $r['alert'] }}</span></td>
                            <td class="text-muted">{{ $r['waktu'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada simulasi. Mulai sekarang →</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection