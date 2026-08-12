@extends('layouts.app')
@section('title', 'EXCON — Fog of War Control')
@section('header', '<i class="bi bi-eye-slash"></i> EXCON — Fog of War Control')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Sesi: {{ $session->nama }} <small class="text-muted">({{ $session->kode }})</small></h5>
    <a href="{{ route('latihan.show', $session) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card mb-3">
    <div class="card-header"><i class="bi bi-info-circle"></i> Apa itu Fog of War?</div>
    <div class="card-body text-muted">
        Fog of War membatasi informasi yang dilihat setiap satker. Saat <strong>ON</strong>, satker tersebut tidak melihat layer terkait.
        EXCON mengaktifkan/menonaktifkan fog untuk mensimulasikan "keterbatasan informasi" (intentional fog of war).
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-grid-3x3"></i> Kontrol Fog of War per Satker</div>
    <div class="card-body">
        @php $satkers = \App\Models\OrbatUnit::SATKER; @endphp
        <div class="row g-3">
            @foreach ($satkers as $code => $nama)
                @php
                    $fog = $fogList->firstWhere('satker', $code);
                    $isOn = $fog?->enabled ?? true;
                @endphp
                <div class="col-md-3">
                    <div class="card bg-dark border {{ $isOn ? 'border-warning' : 'border-success' }}">
                        <div class="card-body text-center">
                            <div class="mb-2 fw-bold">{{ $nama }}</div>
                            <div class="mb-2">
                                @if ($isOn)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-eye-slash"></i> TERFOG</span>
                                @else
                                    <span class="badge bg-success"><i class="bi bi-eye"></i> JELAS</span>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('latihan.fog.toggle', $session) }}">
                                @csrf
                                <input type="hidden" name="satker" value="{{ $code }}">
                                <button class="btn btn-sm {{ $isOn ? 'btn-outline-success' : 'btn-outline-warning' }}">
                                    {{ $isOn ? 'Hapus Fog' : 'Aktifkan Fog' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection