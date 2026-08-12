@extends('layouts.app')
@section('title', 'Operasi')
@section('header', '<i class="bi bi-diagram-2"></i> Operasi — Order Board')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Sesi: {{ $session->nama }} <small class="text-muted">({{ $session->kode }})</small></h5>
    <div class="d-flex gap-2">
        <a href="{{ route('latihan.show', $session) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('operasi.orbat', $session) }}" class="btn btn-sm btn-outline-info">
            <i class="bi bi-diagram-3"></i> ORBAT Board
        </a>
    </div>
</div>

{{-- Tambah order --}}
<div class="card mb-3">
    <div class="card-header"><i class="bi bi-plus-circle"></i> Buat Order Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('operasi.store', $session) }}" class="row g-2">
            @csrf
            <div class="col-md-2">
                <label class="form-label text-muted small">Nomor</label>
                <input name="nomor" type="text" class="form-control bg-dark text-light" placeholder="OP-001" required>
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">Jenis</label>
                <select name="jenis" class="form-select bg-dark text-light">
                    <option value="perintah">Perintah</option>
                    <option value="informasi">Informasi</option>
                    <option value="instruksi">Instruksi</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">Tujuan</label>
                <input name="tujuan_satker" type="text" class="form-control bg-dark text-light" placeholder="all | rese;brimob">
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small">Isi Order</label>
                <input name="isi" type="text" class="form-control bg-dark text-light" placeholder="Isi order..." required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100"><i class="bi bi-send"></i> Kirim</button>
            </div>
        </form>
    </div>
</div>

{{-- Tabel order board --}}
<div class="card">
    <div class="card-header"><i class="bi bi-list-ol"></i> Order Board ({{ $orders->count() }})</div>
    <div class="card-body p-0">
        <table class="table table-sm table-striped mb-0">
            <thead>
                <tr><th>No</th><th>Jenis</th><th>Isi</th><th>Tujuan</th><th>PIC</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse ($orders as $o)
                <tr>
                    <td><code>{{ $o->nomor }}</code></td>
                    <td>{{ $o->jenis }}</td>
                    <td>{{ Str::limit($o->isi, 60) }}</td>
                    <td>{{ $o->tujuan_satker }}</td>
                    <td>{{ $o->maker?->name ?? '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('operasi.update', [$session, $o]) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="form-select form-select-sm bg-dark text-light">
                                @foreach (\App\Models\OrderBoard::STATUS as $s)
                                    <option value="{{ $s }}" {{ $s === $o->status ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-3">Belum ada order.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection