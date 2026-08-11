@extends('layouts.app')
@section('title', 'Marker Unit / Incident / Asset')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Marker Taktis</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMarkerModal">
        <i class="bi bi-plus-lg"></i> Tambah Marker
    </button>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr><th>ID</th><th>Tipe</th><th>Nama</th><th>Kategori</th><th>Koordinat</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($markers as $m)
                <tr>
                    <td>#{{ $m->id }}</td>
                    <td>
                        @php $badge = ['unit'=>'primary','incident'=>'danger','asset'=>'warning'][$m->type] ?? 'secondary'; @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($m->type) }}</span>
                    </td>
                    <td>{{ $m->nama }}</td>
                    <td>{{ $m->kategori ?? '-' }}</td>
                    <td><small>{{ $m->lat }}, {{ $m->lon }}</small></td>
                    <td><span class="badge bg-secondary">{{ $m->status }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('tactical.markers.destroy', $m) }}" class="d-inline" onsubmit="return confirm('Hapus marker ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada marker</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $markers->links() }}

<!-- Modal Tambah Marker -->
<div class="modal fade" id="addMarkerModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('tactical.markers.store') }}" class="modal-content bg-dark">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Marker</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-select" required>
                        <option value="unit">Unit</option>
                        <option value="incident">Incident</option>
                        <option value="asset">Asset</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" placeholder="dalmas, samapta, dst">
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Lat</label>
                        <input type="number" step="any" name="lat" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Lon</label>
                        <input type="number" step="any" name="lon" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
