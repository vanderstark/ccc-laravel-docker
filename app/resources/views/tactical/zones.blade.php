@extends('layouts.app')
@section('title', 'Zona / Route / Objective')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-map me-2"></i>Zona, Route & Objective</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addZoneModal">
        <i class="bi bi-plus-lg"></i> Tambah
    </button>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr><th>ID</th><th>Jenis</th><th>Nama</th><th>Warna</th><th>Simulasi</th><th>Organisasi</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($zones as $z)
                <tr>
                    <td>#{{ $z->id }}</td>
                    <td><span class="badge bg-info text-dark">{{ ucfirst($z->jenis) }}</span></td>
                    <td>{{ $z->nama }}</td>
                    <td><span class="badge" style="background:{{ $z->warna }};">{{ $z->warna }}</span></td>
                    <td>{{ $z->simulation?->location ?? '-' }}</td>
                    <td>{{ $z->organization?->code ?? '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('tactical.zones.destroy', $z) }}" class="d-inline" onsubmit="return confirm('Hapus zona ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada zona/route/objective</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $zones->links() }}

<!-- Modal Tambah Zone -->
<div class="modal fade" id="addZoneModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('tactical.zones.store') }}" class="modal-content bg-dark">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Zona / Route / Objective</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        <option value="zona">Zona</option>
                        <option value="route">Route</option>
                        <option value="objective">Objective</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna (hex)</label>
                    <input type="color" name="warna" class="form-control form-control-color" value="#1f6feb" title="Warna">
                </div>
                <div class="mb-3">
                    <label class="form-label">Geometry (JSON array [lat,lon] atau polygon)</label>
                    <textarea name="geometry" class="form-control" rows="3" required placeholder='[[lat,lon],[lat,lon],...]'></textarea>
                    <small class="text-muted">Format: array koordinat [lat,lon]. Untuk route: garis. Untuk zona: polygon (tutup).</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Simulasi (opsional)</label>
                    <select name="simulation_id" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Simulation::latest()->limit(20)->get() as $s)
                        <option value="{{ $s->id }}">{{ $s->location }} ({{ $s->disaster_type }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Organisasi (opsional)</label>
                    <select name="organization_id" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach(\App\Models\Organization::all() as $o)
                        <option value="{{ $o->id }}">{{ $o->code }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection