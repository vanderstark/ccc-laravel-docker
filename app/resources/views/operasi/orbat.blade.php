@extends('layouts.app')
@section('title', 'ORBAT Board')
@section('header', '<i class="bi bi-diagram-3"></i> ORBAT Board — Kekuatan Satker')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Sesi: {{ $session->nama }} <small class="text-muted">({{ $session->kode }})</small></h5>
    <a href="{{ route('operasi.index', $session) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Order Board
    </a>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-table"></i> ORBAT Board ({{ $units->count() }} satker)</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th><th>Satker</th><th>Nama Unit</th><th>Jenis</th>
                    <th>Kekuatan</th><th>Lokasi</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($units as $i => $u)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ \App\Models\OrbatUnit::SATKER[$u->satker] ?? $u->satker }}</td>
                    <td>{{ $u->nama_unit }}</td>
                    <td>{{ $u->jenis }}</td>
                    <td>
                        <form method="POST" action="{{ route('operasi.orbat.update', [$session, $u]) }}" class="d-inline">
                            @csrf @method('PUT')
                            <input name="kekuatan" type="number" value="{{ $u->kekuatan }}"
                                class="form-control form-control-sm bg-dark text-light" style="width:80px">
                        </form>
                    </td>
                    <td class="text-muted small">
                        {{ $u->latitude ? $u->latitude . ', ' . $u->longitude : '-' }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('operasi.orbat.update', [$session, $u]) }}" class="d-inline">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="form-select form-select-sm bg-dark text-light">
                                <option value="siaga" {{ $u->status=='siaga'?'selected':'' }}>Siaga</option>
                                <option value="bergerak" {{ $u->status=='bergerak'?'selected':'' }}>Bergerak</option>
                                <option value="bertugas" {{ $u->status=='bertugas'?'selected':'' }}>Bertugas</option>
                                <option value="pulang" {{ $u->status=='pulang'?'selected':'' }}>Pulang</option>
                            </select>
                        </form>
                    </td>
                    <td><span class="text-muted small"><i class="bi bi-check-circle"></i> Auto-save</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection