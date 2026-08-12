@extends('layouts.app')

@section('title', $session->nama . ' — Detail Sesi')
@section('header', $session->nama . ' <small class="text-muted">(' . $session->kode . ')</small>')

@section('content')
@php
    $badge = match($session->status) {
        'draft' => 'secondary', 'briefing' => 'info', 'running' => 'success',
        'paused' => 'warning', 'ended' => 'dark', default => 'secondary',
    };
    $satkers = \App\Models\OrbatUnit::SATKER;
@endphp

{{-- ===== STATUS BAR ===== --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div>
                <span class="badge bg-{{ $badge }}" style="font-size:1rem">{{ strtoupper($session->status) }}</span>
            </div>
            <div class="display-6 fw-bold" id="timerT" style="font-variant-numeric:tabular-nums">
                {{ gmdate('H:i:s', $session->t_plus_detik) }}
            </div>
            <div class="text-muted small">T+ Timer ({{ $session->durasi_menit }} menit)</div>

            <div class="ms-auto d-flex gap-2">
                @if ($session->canTransition('briefing') && $session->status === 'draft')
                    <form method="POST" action="{{ route('latihan.transition', $session) }}">
                        @csrf <input type="hidden" name="status" value="briefing">
                        <button class="btn btn-outline-info"><i class="bi bi-megaphone"></i> Mulai Briefing</button>
                    </form>
                @endif
                @if ($session->canTransition('running') && in_array($session->status, ['briefing', 'paused']))
                    <form method="POST" action="{{ route('latihan.transition', $session) }}">
                        @csrf <input type="hidden" name="status" value="running">
                        <button class="btn btn-success"><i class="bi bi-play-fill"></i> Mulai Latihan</button>
                    </form>
                @endif
                @if ($session->status === 'running')
                    <form method="POST" action="{{ route('latihan.transition', $session) }}">
                        @csrf <input type="hidden" name="status" value="paused">
                        <button class="btn btn-warning"><i class="bi bi-pause-fill"></i> Pause</button>
                    </form>
                @endif
                @if (in_array($session->status, ['running', 'paused']))
                    <form method="POST" action="{{ route('latihan.transition', $session) }}"
                        onsubmit="return confirm('Akhiri sesi? Replay & AAR tetap tersedia.')">
                        @csrf <input type="hidden" name="status" value="ended">
                        <button class="btn btn-danger"><i class="bi bi-stop-fill"></i> Akhiri Sesi</button>
                    </form>
                @endif
                @if ($session->status === 'ended')
                    <a href="{{ route('replay.show', $session) }}" class="btn btn-outline-primary">
                        <i class="bi bi-camera-replay"></i> Replay & AAR
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@if ($session->objectives)
<div class="card mb-3 border-info">
    <div class="card-header bg-info bg-opacity-10"><i class="bi bi-bullseye"></i> Objectives Pembelajaran</div>
    <div class="card-body">
        {!! nl2br(e(is_array($session->objectives) ? implode("\n", $session->objectives) : $session->objectives)) !!}
    </div>
</div>
@endif

@if ($session->roe)
<div class="card mb-3 border-warning">
    <div class="card-header bg-warning bg-opacity-10"><i class="bi bi-shield-check"></i> Rules of Engagement (ROE)</div>
    <div class="card-body"><pre class="mb-0 text-light">{{ $session->roe }}</pre></div>
</div>
@endif

<div class="row">
    {{-- ===== ORBAT / KEKUATAN ===== --}}
    <div class="col-lg-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-diagram-3"></i> ORBAT — Kekuatan Satker</span>
                <a href="{{ route('operasi.orbat', $session) }}" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-arrows-fullscreen"></i> Kelola
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Satker</th><th>Unit</th><th>Kekuatan</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach ($session->orbatUnits as $unit)
                        <tr>
                            <td>{{ $satkers[$unit->satker] ?? $unit->satker }}</td>
                            <td>{{ $unit->nama_unit }}</td>
                            <td>{{ $unit->kekuatan }} <small class="text-muted">{{ $unit->jenis }}</small></td>
                            <td>
                                @php
                                    $sBadge = match($unit->status) {
                                        'siaga' => 'success', 'bergerak' => 'info',
                                        'bertugas' => 'primary', 'pulang' => 'secondary', default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $sBadge }}">{{ strtoupper($unit->status) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== ORDER BOARD ===== --}}
    <div class="col-lg-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-earmark-text"></i> Order Board (Operasi)</span>
                <a href="{{ route('operasi.index', $session) }}" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-arrows-fullscreen"></i> Buka
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>No.</th><th>Jenis</th><th>Tujuan</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($session->orders->take(8) as $order)
                        <tr>
                            <td>{{ $order->nomor }}</td>
                            <td>{{ $order->jenis }}</td>
                            <td>{{ $order->tujuan_satker }}</td>
                            <td><span class="badge bg-secondary">{{ strtoupper($order->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum ada order</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- ===== INJECTS ===== --}}
    <div class="col-lg-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-lightning-charge"></i> Inject Queue (EXCON)</span>
                <a href="{{ route('latihan.injects', $session) }}" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-arrows-fullscreen"></i> EXCON
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Kode</th><th>Judul</th><th>T+</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($session->injects->take(8) as $inject)
                        <tr>
                            <td>{{ $inject->kode }}</td>
                            <td>{{ $inject->title }}</td>
                            <td>{{ gmdate('i:s', $inject->t_plus_sec) }}</td>
                            <td>
                                @php
                                    $iBadge = match($inject->status) {
                                        'queued' => 'secondary', 'delivered' => 'info',
                                        'resolved' => 'success', 'skipped' => 'dark', default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $iBadge }}">{{ strtoupper($inject->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum ada inject</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== DECISION LOG ===== --}}
    <div class="col-lg-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-journal-check"></i> Log Keputusan (Waktu + PIC)</span>
                <a href="{{ route('latihan.decisions', $session) }}" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-arrows-fullscreen"></i> Log
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('latihan.decision.store', $session) }}" class="row g-2 mb-2">
                    @csrf
                    <div class="col-md-3">
                        <select name="satker" class="form-select form-select-sm bg-dark text-light">
                            @foreach ($satkers as $code => $nama)
                                <option value="{{ $code }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="keputusan" class="form-control form-control-sm bg-dark text-light" placeholder="Keputusan..." required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="pic" class="form-control form-control-sm bg-dark text-light" placeholder="PIC" required>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-sm btn-primary w-100"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </form>
                <div style="max-height:220px;overflow-y:auto">
                    @forelse ($session->decisionLogs->take(20)->sortByDesc('t_plus_sec') as $log)
                    <div class="border-bottom py-1 small">
                        <strong class="text-info">T+{{ gmdate('i:s', $log->t_plus_sec) }}</strong>
                        <span class="badge bg-secondary">{{ $log->satker }}</span>
                        <div>{{ $log->keputusan }} <small class="text-muted">— PIC: {{ $log->pic }}</small></div>
                    </div>
                    @empty
                    <p class="text-muted small py-2">Belum ada keputusan tercatat.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Live T+ timer polling
setInterval(async () => {
    @if (in_array($session->status, ['running', 'paused']))
    try {
        const r = await fetch('{{ route('latihan.timer', $session) }}');
        const d = await r.json();
        const t = d.t_plus_detik;
        document.getElementById('timerT').textContent =
            [Math.floor(t/3600), Math.floor(t%3600/60), t%60].map(n => String(n).padStart(2,'0')).join(':');
    } catch (e) {}
    @endif
}, 1000);
</script>
@endsection