@extends('layouts.app')
@section('title', 'After Action Review')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-journal-check me-2"></i>After Action Review (AAR)</h4>
        <div>
            <a href="{{ route('aar.report') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-download me-1"></i>Laporan AAR</a>
            <a href="{{ route('leadership.dashboard') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-award me-1"></i>Dashboard Kepemimpinan</a>
        </div>
    </div>

    <!-- Alur -->
    <div class="card mb-4">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                @php
                    $steps = ['briefing' => '📋 Briefing', 'simulation' => '🎮 Simulation', 'decision' => '🧭 Decision', 'aar' => '🔍 AAR', 'feedback' => '💬 Feedback'];
                    $counts = $sessions->groupBy('tahap')->map->count();
                @endphp
                @foreach($steps as $key => $label)
                    <div class="text-center px-3">
                        <div class="fs-4">{{ $label }}</div>
                        <span class="badge {{ ($counts[$key] ?? 0) > 0 ? 'bg-success' : 'bg-secondary' }}">{{ $counts[$key] ?? 0 }} catatan</span>
                    </div>
                    @if(!$loop->last)<i class="bi bi-arrow-right text-muted"></i>@endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Form tambah tahap -->
    <div class="card mb-4">
        <div class="card-header">Tambah Catatan Tahap</div>
        <div class="card-body">
            <form method="POST" action="{{ route('aar.store') }}" class="row g-2">
                @csrf
                <div class="col-md-2">
                    <select name="tahap" class="form-select" required>
                        @foreach($tahaps as $t)
                            <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="simulation_id" class="form-select">
                        <option value="">— Tanpa Simulasi —</option>
                        @foreach($simulations as $s)
                            <option value="{{ $s->id }}">#{{ $s->id }} — {{ $s->disasterType?->nama ?? $s->disaster_type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input name="judul" class="form-control" placeholder="Judul catatan (mis. Keputusan evakuasi)" required>
                </div>
                <div class="col-md-3">
                    <input name="konten" class="form-control" placeholder="Isi catatan / keputusan...">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100"><i class="bi bi-plus"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="tahap" class="form-select form-select-sm">
                <option value="">Semua Tahap</option>
                @foreach($tahaps as $t)
                    <option value="{{ $t }}" @selected(request('tahap') == $t)>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-outline-secondary">Filter</button>
        </div>
    </form>

    <!-- Timeline -->
    <div class="card">
        <div class="card-header">Kronologi Sesi</div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($sessions as $s)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="badge bg-{{ $s->tahap == 'briefing' ? 'info' : ($s->tahap == 'simulation' ? 'primary' : ($s->tahap == 'decision' ? 'warning' : ($s->tahap == 'aar' ? 'secondary' : 'success'))) }} text-uppercase">{{ $s->tahap }}</span>
                                <strong class="ms-2">{{ $s->judul }}</strong>
                                @if($s->simulation_id)
                                    <span class="badge bg-outline-secondary ms-1">Sim #{{ $s->simulation_id }}</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-muted small me-3">{{ $s->created_at?->format('d M H:i') }} — {{ $s->user?->name }}</span>
                                <form action="{{ route('aar.destroy', $s) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                        @if($s->konten)
                            <div class="mt-1 ps-1 text-muted small border-start border-2 ps-2">{{ $s->konten }}</div>
                        @endif
                    </div>
                @empty
                    <div class="list-group-item text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Belum ada catatan AAR. Mulai dengan <strong>Briefing</strong>!
                    </div>
                @endforelse
            </div>
            <div class="p-3">{{ $sessions->links() }}</div>
        </div>
    </div>
</div>
@endsection
