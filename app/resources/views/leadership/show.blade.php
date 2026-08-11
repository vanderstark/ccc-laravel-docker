@extends('layouts.app')
@section('title', 'Detail Penilaian')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-person-badge me-2"></i>Detail Penilaian #{{ $assessment->id }}</h4>
        <div>
            <a href="{{ route('leadership.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
            <a href="{{ route('aar.index') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-journal-check me-1"></i>Buka AAR</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">Rincian Penilaian</div>
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <tr><th style="width:220px">Peserta</th><td><strong>{{ $assessment->user?->name ?? '—' }}</strong></td></tr>
                        <tr><th>Skenario</th><td>{{ $assessment->scenario_name }} <span class="badge bg-secondary">{{ $assessment->scenario_type }}</span></td></tr>
                        <tr><th>Simulasi</th><td>{{ $assessment->simulation_id ? '#' . $assessment->simulation_id : '—' }}</td></tr>
                        <tr><th>Tanggal</th><td>{{ $assessment->created_at?->format('d M Y H:i') }}</td></tr>
                        <tr><th>Total Skor</th><td><span class="badge bg-primary fs-6">{{ $assessment->skor_total }}</span></td></tr>
                        <tr><th>Grade</th><td>
                            <span class="badge fs-6 bg-{{ $assessment->grade == 'A' ? 'success' : ($assessment->grade == 'B' ? 'info' : ($assessment->grade == 'C' ? 'warning' : 'danger')) }}">{{ $assessment->grade }}</span>
                        </td></tr>
                    </table>

                    <h6 class="mt-4 mb-3">Dimensi Penilaian</h6>
                    @php
                        $dims = [
                            'Kualitas Keputusan' => $assessment->skor_keputusan,
                            'Kecepatan Respons' => $assessment->skor_kecepatan,
                            'Kolaborasi' => $assessment->skor_kolaborasi,
                            'Komunikasi Krisis' => $assessment->skor_komunikasi,
                            'Integritas' => $assessment->skor_integritas,
                            'Manajemen Risiko' => $assessment->skor_risiko,
                        ];
                    @endphp
                    @foreach($dims as $label => $val)
                        <div class="d-flex align-items-center mb-2">
                            <span class="me-2" style="width:160px">{{ $label }}</span>
                            <div class="progress flex-grow-1" style="height:16px">
                                <div class="progress-bar {{ $val >= 80 ? 'bg-success' : ($val >= 60 ? 'bg-warning' : 'bg-danger') }}" style="width:{{ $val }}%">{{ $val }}</div>
                            </div>
                        </div>
                    @endforeach

                    @if($assessment->catatan)
                        <h6 class="mt-4">Catatan Assessor</h6>
                        <div class="alert alert-secondary mb-0">{{ $assessment->catatan }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">Riwayat AAR Terkait</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($aar as $s)
                            <li class="list-group-item">
                                <span class="badge bg-secondary text-uppercase">{{ $s->tahap }}</span>
                                <strong class="ms-1">{{ $s->judul }}</strong>
                                <div class="text-muted small">{{ $s->created_at?->format('d M H:i') }} — {{ $s->user?->name }}</div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center py-4">Belum ada catatan AAR untuk sesi ini</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
