@extends('layouts.app')

@section('title', 'Buat Sesi Latihan')
@section('header', 'Buat Sesi Latihan Baru')

@section('content')
<form method="POST" action="{{ route('latihan.store') }}">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-clipboard"></i> Informasi Dasar</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Sesi</label>
                            <input type="text" name="nama" class="form-control bg-dark text-light" required placeholder="Latihan Penanganan Terorisme">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode Sesi</label>
                            <input type="text" name="kode" class="form-control bg-dark text-light" required placeholder="LT-TER-2026-01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Skenario</label>
                            <select name="simulation_id" class="form-select bg-dark text-light">
                                <option value="">— Pilih skenario —</option>
                                @foreach ($simulations as $sim)
                                    <option value="{{ $sim->id }}">{{ $sim->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preset Wilayah</label>
                            <select name="preset_id" class="form-select bg-dark text-light">
                                <option value="">— Pilih wilayah —</option>
                                @foreach ($presets as $preset)
                                    <option value="{{ $preset->id }}">{{ $preset->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Durasi (menit)</label>
                            <input type="number" name="durasi_menit" class="form-control bg-dark text-light" value="120">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-bullseye"></i> Objectives (1-3 SMART)</div>
                <div class="card-body">
                    <textarea name="objectives" class="form-control bg-dark text-light" rows="5"
                        placeholder="- Peserta mampu menyusun salur komando dalam fog of war < 15 menit&#10;- Peserta mampu mengidentifikasi faktor kritis dengan informasi terbatas"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><i class="bi bi-shield-check"></i> Rules of Engagement (ROE)</div>
        <div class="card-body">
            <textarea name="roe" class="form-control bg-dark text-light" rows="4"
                placeholder="Aturan penggunaan kekuatan, batasan wilayah operasi, prosedur eskalasi..."></textarea>
        </div>
    </div>

    <div class="text-end">
        <a href="{{ route('latihan.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Buat Sesi</button>
    </div>
</form>
@endsection