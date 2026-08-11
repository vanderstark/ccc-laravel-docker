@extends('layouts.app')

@section('title', 'Simulasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-activity me-2"></i>Simulasi</h4>
    <a href="{{ route('simulations.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Simulasi Baru</a>
</div>

<div class="card">
    <div class="card-body">
        <p>Tersedia <strong>{{ $disasterTypes->count() }}</strong> tipe simulasi:
        <span class="badge bg-danger">26 Bencana Indonesia</span>
        <span class="badge bg-warning text-dark">5 Operasi Militer</span></p>
        <p class="text-muted small mb-0">Pilih tombol di atas untuk memulai simulasi baru, atau lihat riwayat simulasi sebelumnya.</p>
    </div>
</div>
@endsection