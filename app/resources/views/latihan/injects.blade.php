@extends('layouts.app')
@section('title', 'EXCON — Inject Queue')
@section('header', '<i class="bi bi-lightning-charge"></i> EXCON — Inject Queue')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Sesi: {{ $session->nama }} <small class="text-muted">({{ $session->kode }})</small></h5>
    <a href="{{ route('latihan.show', $session) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

{{-- Form inject baru --}}
<div class="card mb-3">
    <div class="card-header"><i class="bi bi-plus-circle"></i> Tambah Inject Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('latihan.inject.store', $session) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-2"><input name="kode" class="form-control bg-dark text-light" placeholder="INJ-01" required></div>
                <div class="col-md-3"><input name="title" class="form-control bg-dark text-light" placeholder="Judul inject" required></div>
                <div class="col-md-5"><input name="message" class="form-control bg-dark text-light" placeholder="Pesan situasi..." required></div>
                <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Tambah</button></div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-2">
                    <select name="visible_to" class="form-select bg-dark text-light">
                        <option value="all">Semua Satker</option>
                        <option value="ai">Analisis Informasi</option>
                        <option value="reserse">Reserse</option>
                        <option value="brimob">Brimob</option>
                        <option value="lantas">Lantas</option>
                        <option value="sabhara">Sabhara</option>
                        <option value="binmas">Binmas</option>
                        <option value="manajemen_konflik">Manajemen Konflik</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">T+ (detik)</label>
                    <input name="t_plus_sec" type="number" class="form-control bg-dark text-light" value="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">Requires Action</label>
                    <input name="requires_action" class="form-control bg-dark text-light" placeholder="reserse;brimob">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small">Fail Effect</label>
                    <input name="fail_effect" class="form-control bg-dark text-light" placeholder="-20 poin jika > 10 menit">
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabel inject --}}
<div class="card">
    <div class="card-header">
        <i class="bi bi-list-ol"></i> Inject Queue ({{ $injects->count() }} inject)
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-striped mb-0">
            <thead>
                <tr>
                    <th>Kode</th><th>Judul</th><th>Pesan</th><th>Visible To</th>
                    <th>T+</th><th>Aksi</th><th>Status</th><th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($injects as $inj)
                <tr>
                    <td><code>{{ $inj->kode }}</code></td>
                    <td>{{ $inj->title }}</td>
                    <td class="text-truncate" style="max-width:250px" title="{{ $inj->message }}">{{ $inj->message }}</td>
                    <td><span class="badge bg-info">{{ $inj->visible_to }}</span></td>
                    <td>T+{{ gmdate('i:s', $inj->t_plus_sec) }}</td>
                    <td>
                        @if ($inj->status === 'queued')
                        <form method="POST" action="{{ route('latihan.inject.deliver', $inj) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-success" title="Kirim sekarang">
                                <i class="bi bi-send"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                    <td>
                        @php $s = match($inj->status) { 'queued'=>'secondary','delivered'=>'info','resolved'=>'success','skipped'=>'dark',default=>'secondary'}; @endphp
                        <span class="badge bg-{{ $s }}">{{ strtoupper($inj->status) }}</span>
                    </td>
                    <td class="text-muted small">{{ $inj->delivered_at?->format('H:i:s') ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada inject. Buat inject pertama untuk mengisi situasi latihan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection