@extends('layouts.app')
@section('title', 'Dashboard Kepemimpinan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-award me-2"></i>Dashboard Kepemimpinan</h4>
        <div>
            <a href="{{ route('leadership.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Penilaian Baru</a>
            <a href="{{ route('aar.index') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-journal-check me-1"></i>AAR Workflow</a>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" class="row g-2 mb-4">
        <div class="col-auto">
            <select name="user_id" class="form-select form-select-sm">
                <option value="">Semua Peserta</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="grade" class="form-select form-select-sm">
                <option value="">Semua Grade</option>
                @foreach(['A','B','C','D','E'] as $g)
                    <option value="{{ $g }}" @selected(request('grade') == $g)>Grade {{ $g }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-outline-secondary">Filter</button>
        </div>
    </form>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Total Penilaian</h6>
                    <h3 class="mb-0 text-primary">{{ $kpi['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Rata-rata Total</h6>
                    <h3 class="mb-0 text-success">{{ $kpi['rata_total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Grade A</h6>
                    <h3 class="mb-0 text-warning">{{ $kpi['grade_a'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-info h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Sesi AAR</h6>
                    <h3 class="mb-0 text-info">{{ $aar->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Radar dimensi + ranking -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><i class="bi bi-bar-chart me-1"></i>Rata-rata Dimensi Penilaian</div>
                <div class="card-body">
                    @php
                        $dims = [
                            'Keputusan' => $kpi['rata_keputusan'],
                            'Kecepatan' => $kpi['rata_kecepatan'],
                            'Kolaborasi' => $kpi['rata_kolaborasi'],
                            'Komunikasi' => $kpi['rata_komunikasi'],
                            'Integritas' => $kpi['rata_integritas'],
                            'Risiko' => $kpi['rata_risiko'],
                        ];
                    @endphp
                    @foreach($dims as $label => $val)
                        <div class="d-flex align-items-center mb-2">
                            <span class="me-2" style="width:110px">{{ $label }}</span>
                            <div class="progress flex-grow-1" style="height:14px">
                                <div class="progress-bar {{ $val >= 80 ? 'bg-success' : ($val >= 60 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $val }}%">{{ $val }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><i class="bi bi-trophy me-1"></i>Ranking Peserta</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead><tr><th>#</th><th>Peserta</th><th>Penilaian</th><th>Rata-rata</th><th>Grade</th></tr></thead>
                        <tbody>
                        @forelse($rankings as $i => $r)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $r->user?->name ?? '—' }}</td>
                                <td>{{ $r->total_penilaian }}</td>
                                <td><strong>{{ $r->rata_skor }}</strong></td>
                                <td><span class="badge bg-{{ $r->rata_skor >= 80 ? 'success' : ($r->rata_skor >= 60 ? 'warning' : 'danger') }}">{{ $r->rata_skor >= 90 ? 'A' : ($r->rata_skor >= 80 ? 'B' : ($r->rata_skor >= 70 ? 'C' : ($r->rata_skor >= 60 ? 'D' : 'E'))) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Belum ada penilaian</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel detail -->
    <div class="card">
        <div class="card-header"><i class="bi bi-table me-1"></i>Riwayat Penilaian</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th><th>Peserta</th><th>Skenario</th>
                            <th>Keputusan</th><th>Kecepatan</th><th>Kolaborasi</th><th>Komunikasi</th><th>Integritas</th><th>Risiko</th>
                            <th>Total</th><th>Grade</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($assessments as $a)
                        <tr>
                            <td>{{ $a->id }}</td>
                            <td>{{ $a->user?->name ?? '—' }}</td>
                            <td>{{ $a->scenario_name }}</td>
                            <td>{{ $a->skor_keputusan }}</td>
                            <td>{{ $a->skor_kecepatan }}</td>
                            <td>{{ $a->skor_kolaborasi }}</td>
                            <td>{{ $a->skor_komunikasi }}</td>
                            <td>{{ $a->skor_integritas }}</td>
                            <td>{{ $a->skor_risiko }}</td>
                            <td><strong>{{ $a->skor_total }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $a->grade == 'A' ? 'success' : ($a->grade == 'B' ? 'info' : ($a->grade == 'C' ? 'warning' : 'danger')) }}">
                                    {{ $a->grade }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('leadership.show', $a) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                <form action="{{ route('leadership.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus penilaian ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="12" class="text-center text-muted py-4">Belum ada data penilaian kepemimpinan. <a href="{{ route('leadership.create') }}">Buat penilaian pertama</a></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $assessments->links() }}</div>
        </div>
    </div>
</div>
@endsection
