@extends('layouts.app')
@section('title', 'CCC — Crisis Command Center')
@section('content')
<div class="container text-center py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <i class="bi bi-shield-fill-check" style="font-size:5rem; color:#1f6feb;"></i>
            </div>
            <h1 class="fw-bold mb-3">Crisis Command Center</h1>
            <p class="lead text-muted mb-4">
                Sistem Simulasi Bencana & Operasi Militer untuk Akademi Kepolisian Indonesia
            </p>
            <p class="text-muted mb-5">
                31 tipe simulasi (26 bencana Indonesia + 5 operasi militer) · Preset Natuna/Papua/Timor
                · Estimasi dampak instan · Alokasi sumber daya otomatis · Rencana aksi 4 fase
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right me-1"></i>Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg"><i class="bi bi-person-plus me-1"></i>Daftar</a>
            </div>

            <hr class="my-5">

            <div class="row text-start">
                <div class="col-md-4 mb-3">
                    <div class="card h-100"><div class="card-body">
                        <h6><i class="bi bi-exclamation-triangle text-danger me-1"></i>26 Bencana Indonesia</h6>
                        <small class="text-muted">Gempa, tsunami, gunung api, banjir, longsor, kekeringan, tornado, wabah, kebakaran, abrasi, dsb.</small>
                    </div></div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100"><div class="card-body">
                        <h6><i class="bi bi-tank text-warning me-1"></i>5 Operasi Militer</h6>
                        <small class="text-muted">Konflik darat, maritim, udara, gabungan, + 45 perang historis Indonesia.</small>
                    </div></div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100"><div class="card-body">
                        <h6><i class="bi bi-map text-info me-1"></i>Preset Wilayah</h6>
                        <small class="text-muted">Natuna (maritim), Papua (khusus), Timor (perbatasan) — parameter otomatis.</small>
                    </div></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection