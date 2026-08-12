@extends('layouts.app')

@section('title', 'Menu Latihan — Sesi')
@section('header', 'Latihan Taktis (Sesi)')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <div class="input-group">
            <span class="input-group-text bg-secondary bg-opacity-25 text-light"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control bg-dark text-light" placeholder="Cari sesi latihan..." id="searchSesi">
        </div>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('latihan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Sesi Baru
        </a>
    </div>
</div>

<div class="row">
    @forelse ($sessions as $sesi)
    <div class="col-md-6 col-lg-4 mb-3 sesi-card">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-1">{{ $sesi->nama }}</h5>
                        <small class="text-muted">{{ $sesi->kode }}</small>
                    </div>
                    @php
                        $badge = match($sesi->status) {
                            'draft' => 'secondary',
                            'briefing' => 'info',
                            'running' => 'success',
                            'paused' => 'warning',
                            'ended' => 'dark',
                            default => 'secondary',
                        };
                    @endphp
                    <span class="badge bg-{{ $badge }}">{{ strtoupper($sesi->status) }}</span>
                </div>

                <div class="mt-3">
                    @if ($sesi->simulation)
                        <div><i class="bi bi-broadcast"></i> <strong>Skenario:</strong> {{ $sesi->simulation->nama }}</div>
                    @endif
                    @if ($sesi->preset)
                        <div><i class="bi bi-geo-alt"></i> <strong>Wilayah:</strong> {{ $sesi->preset->nama }}</div>
                    @endif
                    <div><i class="bi bi-hourglass"></i> <strong>Durasi:</strong> {{ $sesi->durasi_menit }} menit</div>
                    <div><i class="bi bi-clock"></i> <strong>T+:</strong> {{ gmdate('H:i:s', $sesi->t_plus_detik) }}</div>
                </div>

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-person"></i> {{ $sesi->creator?->name ?? '-' }}
                    </small>
                    <a href="{{ route('latihan.show', $sesi->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-right-circle"></i> Buka
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Belum ada sesi latihan. Buat sesi pertama!
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">{{ $sessions->links() }}</div>

<script>
    document.getElementById('searchSesi')?.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.sesi-card').forEach(card => {
            card.style.display = card.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>
@endsection