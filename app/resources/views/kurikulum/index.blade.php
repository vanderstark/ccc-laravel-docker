@extends('layouts.app')
@section('title', 'Kurikulum Sespimmen/Sespimti')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-mortarboard me-2"></i>Integrasi Kurikulum Sespimmen &amp; Sespimti</h4>
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
    </div>

    <!-- Level Cards -->
    <div class="row g-3 mb-4">
        @foreach($levels as $lv)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                        <strong>{{ $lv->nama }}</strong>
                        <span class="badge bg-secondary">{{ ucfirst($lv->tingkat) }}</span>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">{{ $lv->deskripsi }}</p>
                        <p class="mb-1"><i class="bi bi-clock me-1"></i>{{ $lv->durasi_hari }} hari</p>
                        <p class="mb-0"><i class="bi bi-diagram-3 me-1"></i>{{ $lv->mappings->count() }} skenario terpetakan</p>
                        <hr>
                        <ul class="list-unstyled small mb-0">
                            @foreach($lv->mappings as $m)
                                <li class="mb-1"><i class="bi bi-dot"></i> {{ $m->nama_skenario }} <span class="text-muted">({{ $m->jam_pelatihan }} jam)</span></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Form Progress -->
    <div class="card mb-4">
        <div class="card-header">Catat Progress Peserta</div>
        <div class="card-body">
            <form method="POST" action="{{ route('kurikulum.progress.store') }}" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <select name="user_id" class="form-select form-select-sm" required>
                        <option value="">— Peserta —</option>
                        @foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="kurikulum_level_id" class="form-select form-select-sm" required>
                        <option value="">— Level Kurikulum —</option>
                        @foreach($levels as $lv)<option value="{{ $lv->id }}">{{ $lv->nama }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm" required>
                        <option value="belum">Belum Mulai</option>
                        <option value="berlangsung">Berlangsung</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="skor" class="form-control form-control-sm" min="0" max="100" placeholder="Skor">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-sm btn-primary w-100"><i class="bi bi-plus"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="level" class="form-select form-select-sm">
                <option value="">Semua Level</option>
                @foreach($levels as $lv)<option value="{{ $lv->id }}" @selected(request('level') == $lv->id)>{{ $lv->nama }}</option>@endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="belum" @selected(request('status') == 'belum')>Belum Mulai</option>
                <option value="berlangsung" @selected(request('status') == 'berlangsung')>Berlangsung</option>
                <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option>
            </select>
        </div>
        <div class="col-auto"><button class="btn btn-sm btn-outline-secondary">Filter</button></div>
    </form>

    <!-- Progress Table -->
    <div class="card">
        <div class="card-header">Progress Peserta</div>
        <div class="card-body p-0">
            <table class="table table-sm table-hover mb-0">
                <thead><tr><th>#</th><th>Peserta</th><th>Level</th><th>Status</th><th>Skor</th><th>Mulai</th><th>Selesai</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($progress as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->user?->name ?? '—' }}</td>
                        <td>{{ $p->level?->nama ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $p->status == 'selesai' ? 'success' : ($p->status == 'berlangsung' ? 'warning' : 'secondary') }}">{{ ucfirst($p->status) }}</span>
                        </td>
                        <td>{{ $p->skor ?? '—' }}</td>
                        <td>{{ $p->mulai?->format('d M Y') ?? '—' }}</td>
                        <td>{{ $p->selesai?->format('d M Y') ?? '—' }}</td>
                        <td>
                            <form action="{{ route('kurikulum.progress.update', $p) }}" method="POST" class="d-flex gap-1">
                                @csrf @method('PUT')
                                <select name="status" class="form-select form-select-sm">
                                    <option value="belum" @selected($p->status == 'belum')>Belum</option>
                                    <option value="berlangsung" @selected($p->status == 'berlangsung')>Berlangsung</option>
                                    <option value="selesai" @selected($p->status == 'selesai')>Selesai</option>
                                </select>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-check"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada progress peserta tercatat</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="p-3">{{ $progress->links() }}</div>
        </div>
    </div>
</div>
@endsection
