@extends('layouts.app')
@section('title', 'Integrasi Data Instansi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-building me-2"></i>Instansi Terintegrasi</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addOrgModal">
        <i class="bi bi-plus-lg"></i> Tambah Instansi
    </button>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-3 mb-4">
    @forelse($organizations as $o)
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                @php $icon = ['polri'=>'shield','hankam'=>'shield-lock','pemda'=>'building','instansi'=>'globe'][$o->jenis] ?? 'building'; @endphp
                <h5><i class="bi bi-{{ $icon }} me-2"></i>{{ $o->code }}</h5>
                <p class="text-muted small mb-1">{{ $o->nama }}</p>
                <span class="badge bg-secondary">{{ $o->jenis }}</span>
                <span class="badge bg-info text-dark ms-1">{{ $o->simulations_count }} simulasi</span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center text-muted py-4">Belum ada instansi</div>
    @endforelse
</div>

<!-- Modal Tambah Instansi -->
<div class="modal fade" id="addOrgModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('tactical.organizations.store') }}" class="modal-content bg-dark">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Instansi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Kode</label>
                    <input type="text" name="code" class="form-control" required maxlength="20" placeholder="POLRI, HANKAM, PEMDA">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        <option value="polri">POLRI</option>
                        <option value="hankam">HANKAM</option>
                        <option value="pemda">PEMDA</option>
                        <option value="instansi">Instansi Lain</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="2" maxlength="500"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection