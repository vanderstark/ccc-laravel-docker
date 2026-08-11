@extends('layouts.app')
@section('title', 'Buat Penilaian Kepemimpinan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Buat Penilaian Kepemimpinan</h4>
        <a href="{{ route('leadership.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('leadership.store') }}" class="card">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Peserta *</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">— Pilih Peserta —</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Simulasi Terkait</label>
                    <select name="simulation_id" class="form-select">
                        <option value="">— Tanpa Simulasi —</option>
                        @foreach($simulations as $s)
                            <option value="{{ $s->id }}">#{{ $s->id }} — {{ $s->disasterType?->nama ?? $s->disaster_type }} ({{ $s->location }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kualitas Keputusan (0-100) *</label>
                    <input type="number" name="skor_keputusan" class="form-control" min="0" max="100" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kecepatan Respons (0-100) *</label>
                    <input type="number" name="skor_kecepatan" class="form-control" min="0" max="100" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kolaborasi (0-100) *</label>
                    <input type="number" name="skor_kolaborasi" class="form-control" min="0" max="100" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Komunikasi Krisis (0-100) *</label>
                    <input type="number" name="skor_komunikasi" class="form-control" min="0" max="100" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Integritas (0-100) *</label>
                    <input type="number" name="skor_integritas" class="form-control" min="0" max="100" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Manajemen Risiko (0-100) *</label>
                    <input type="number" name="skor_risiko" class="form-control" min="0" max="100" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Catatan / Catatan Assessor</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan penilaian, observasi, rekomendasi..."></textarea>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Penilaian</button>
        </div>
    </form>

    <div class="alert alert-info mt-4 mb-0">
        <i class="bi bi-info-circle me-1"></i>
        <strong>Skala:</strong> 90+ = A (Sangat Baik) | 80-89 = B (Baik) | 70-79 = C (Cukup) | 60-69 = D (Kurang) | &lt;60 = E (Sangat Kurang)
    </div>
</div>
@endsection
