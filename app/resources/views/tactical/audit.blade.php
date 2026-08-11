@extends('layouts.app')
@section('title', 'Log Aktivitas & Audit Trail')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-journal-check me-2"></i>Audit Trail</h4>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-auto">
                <select name="entity" class="form-select form-select-sm">
                    <option value="">Semua Entitas</option>
                    @foreach(['simulation','marker','zone','user','export'] as $e)
                    <option value="{{ $e }}" @selected(request('entity')==$e)>{{ ucfirst($e) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="action" class="form-select form-select-sm">
                    <option value="">Semua Aksi</option>
                    @foreach(['create','update','delete','export','login'] as $a)
                    <option value="{{ $a }}" @selected(request('action')==$a)>{{ ucfirst($a) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-light">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr><th>Waktu</th><th>User</th><th>Aksi</th><th>Entitas</th><th>Detail</th><th>IP</th></tr>
            </thead>
            <tbody>
                @forelse($logs as $l)
                <tr>
                    <td><small>{{ $l->created_at?->format('d M Y H:i:s') }}</small></td>
                    <td>{{ $l->user?->name ?? '<span class="text-muted">System</span>' }}</td>
                    <td>
                        @php $b = ['create'=>'success','update'=>'info','delete'=>'danger','export'=>'warning','login'=>'primary'][$l->action] ?? 'secondary'; @endphp
                        <span class="badge bg-{{ $b }}">{{ strtoupper($l->action) }}</span>
                    </td>
                    <td>{{ $l->entity }} #{{ $l->entity_id }}</td>
                    <td><small>{{ $l->data ? json_encode($l->data) : '-' }}</small></td>
                    <td><small>{{ $l->ip ?? '-' }}</small></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada aktivitas tercatat</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $logs->links() }}
@endsection