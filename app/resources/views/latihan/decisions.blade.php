@extends('layouts.app')
@section('title', 'Log Keputusan')
@section('header', '<i class="bi bi-journal-check"></i> Log Keputusan (Waktu + PIC)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Sesi: {{ $session->nama }} <small class="text-muted">({{ $session->kode }})</small></h5>
    <a href="{{ route('latihan.show', $session) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@php $satkers = \App\Models\OrbatUnit::SATKER; @endphp

<div class="card mb-3">
    <div class="card-header"><i class="bi bi-plus-circle"></i> Tambah Keputusan Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('latihan.decision.store', $session) }}" class="row g-2">
            @csrf
            <div class="col-md-2">
                <label class="form-label text-muted small">Satker</label>
                <select name="satker" class="form-select bg-dark text-light">
                    @foreach ($satkers as $code => $nama)
                        <option value="{{ $code }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label text-muted small">Keputusan</label>
                <input name="keputusan" type="text" class="form-control bg-dark text-light" placeholder="Evakuasi, kerahkan unit, eskalasi..." required>
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small">PIC</label>
                <input name="pic" type="text" class="form-control bg-dark text-light" placeholder="AKP Budi" required>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100"><i class="bi bi-check-lg"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-clock-history"></i> Riwayat Keputusan ({{ $decisions->count() }})</div>
    <div class="card-body p-0">
        <table class="table table-sm table-striped mb-0">
            <thead><tr><th>Waktu</th><th>Satker</th><th>Keputusan</th><th>PIC</th></tr></thead>
            <tbody>
                @forelse ($decisions as $d)
                <tr>
                    <td class="text-nowrap"><strong class="text-info">T+{{ gmdate('i:s', $d->t_plus_sec) }}</strong></td>
                    <td><span class="badge bg-secondary">{{ $satkers[$d->satker] ?? $d->satker }}</span></td>
                    <td>{{ $d->keputusan }}</td>
                    <td>{{ $d->pic }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada keputusan tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection