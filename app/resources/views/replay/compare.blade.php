@extends('layouts.app')
@section('title', 'Perbandingan Sesi (Side-by-Side)')
@section('header', '<i class="bi bi-layout-split"></i> Perbandingan Sesi Latihan')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card border-primary">
            <div class="card-header bg-primary bg-opacity-25"><i class="bi bi-1-circle"></i> {{ $stats['a']['nama'] }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><td>Total Keputusan</td><td class="text-end fw-bold">{{ $stats['a']['total_decisions'] }}</td></tr>
                    <tr><td>Total Inject</td><td class="text-end fw-bold">{{ $stats['a']['total_injects'] }}</td></tr>
                    <tr><td>Durasi (detik)</td><td class="text-end fw-bold">{{ $stats['a']['durasi'] }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-success">
            <div class="card-header bg-success bg-opacity-25"><i class="bi bi-2-circle"></i> {{ $stats['b']['nama'] }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><td>Total Keputusan</td><td class="text-end fw-bold">{{ $stats['b']['total_decisions'] }}</td></tr>
                    <tr><td>Total Inject</td><td class="text-end fw-bold">{{ $stats['b']['total_injects'] }}</td></tr>
                    <tr><td>Durasi (detik)</td><td class="text-end fw-bold">{{ $stats['b']['durasi'] }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Timeline Keputusan — {{ $sessionA->nama }}</div>
            <div class="card-body" style="max-height:300px;overflow-y:auto">
                @forelse ($sessionA->decisionLogs->sortBy('t_plus_sec') as $d)
                    <div class="small border-bottom py-1">
                        <strong class="text-info">T+{{ gmdate('i:s', $d->t_plus_sec) }}</strong> — {{ $d->keputusan }}
                    </div>
                @empty
                    <p class="text-muted small">Tidak ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Timeline Keputusan — {{ $sessionB->nama }}</div>
            <div class="card-body" style="max-height:300px;overflow-y:auto">
                @forelse ($sessionB->decisionLogs->sortBy('t_plus_sec') as $d)
                    <div class="small border-bottom py-1">
                        <strong class="text-success">T+{{ gmdate('i:s', $d->t_plus_sec) }}</strong> — {{ $d->keputusan }}
                    </div>
                @empty
                    <p class="text-muted small">Tidak ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection