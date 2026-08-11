@extends('layouts.app')
@section('title', 'Komunikasi Krisis & Analitik')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-graph-up-arrow me-2"></i>Komunikasi Krisis & Analitik Sosial</h4>
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
    </div>

    <!-- Analitik Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-info h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Konten Media Sosial</h6>
                    <h3 class="mb-0 text-info">{{ $analitik['total_medsos'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Hoax Terkonfirmasi</h6>
                    <h3 class="mb-0 text-danger">{{ $analitik['hoax'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Sentimen Negatif</h6>
                    <h3 class="mb-0 text-warning">{{ $analitik['sentimen']['negatif'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Komunikasi (Terbit)</h6>
                    <h3 class="mb-0 text-success">{{ $analitik['komunikasi']['terbit'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekomendasi AI -->
    @if($ringkasan)
        <div class="card mb-4 border-{{ $ringkasan['tingkat'] === 'KRITIS' ? 'danger' : ($ringkasan['tingkat'] === 'TINGGI' ? 'warning' : 'info') }}">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-lightbulb me-1"></i> Ringkasan Situasi & Rekomendasi AI</span>
                <span class="badge bg-{{ $ringkasan['tingkat'] === 'KRITIS' ? 'danger' : ($ringkasan['tingkat'] === 'TINGGI' ? 'warning' : 'info') }}">{{ $ringkasan['tingkat'] }}</span>
            </div>
            <div class="card-body">
                <p class="mb-2">{{ $ringkasan['ringkasan'] }}</p>
                <div class="mt-3">
                    @foreach($rekomendasi as $r)
                        <div class="alert alert-{{ $r['prioritas'] === 'SANGAT TINGGI' ? 'danger' : ($r['prioritas'] === 'TINGGI' ? 'warning' : 'secondary') }} py-2 mb-2">
                            <strong>{{ $r['prioritas'] }}:</strong> {{ $r['tindakan'] }} <small class="d-block text-muted">{{ $r['alasan'] }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Filter + Form -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <select name="simulation_id" class="form-select form-select-sm">
                <option value="">— Semua Simulasi —</option>
                @foreach($simulations as $s)
                    <option value="{{ $s->id }}" @selected(request('simulation_id') == $s->id)>#{{ $s->id }} — {{ $s->disasterType?->nama ?? $s->disaster_type }} ({{ $s->location }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="sentimen" class="form-select form-select-sm">
                <option value="">— Semua Sentimen —</option>
                <option value="positif" @selected(request('sentimen') == 'positif')>Positif</option>
                <option value="negatif" @selected(request('sentimen') == 'negatif')>Negatif</option>
                <option value="netral" @selected(request('sentimen') == 'netral')>Netral</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">— Semua Status —</option>
                <option value="aktif" @selected(request('status') == 'aktif')>Aktif</option>
                <option value="ditangani" @selected(request('status') == 'ditangani')>Ditangani</option>
                <option value="hoax_terkonfirmasi" @selected(request('status') == 'hoax_terkonfirmasi')>Hoax Terkonfirmasi</option>
            </select>
        </div>
        <div class="col-auto"><button class="btn btn-sm btn-outline-secondary">Filter</button></div>
    </form>

    <ul class="nav nav-tabs mb-3" id="krisisTab" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#medsos">📱 Media Sosial</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#komunikasi">📢 Komunikasi Krisis</button></li>
    </ul>

    <div class="tab-content">
        <!-- Media Sosial -->
        <div class="tab-pane fade show active" id="medsos">
            <div class="card mb-3">
                <div class="card-header"> Tambah Konten Media Sosial</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('krisis.medsos.store') }}" class="row g-2">
                        @csrf
                        <div class="col-md-2"><input name="platform" class="form-control form-control-sm" placeholder="Platform (X, FB, dll)" required></div>
                        <div class="col-md-2">
                            <select name="jenis_konten" class="form-select form-select-sm" required>
                                <option value="">Jenis</option>
                                <option value="berita">Berita</option>
                                <option value="rumor">Rumor</option>
                                <option value="hoax">Hoax</option>
                                <option value="seruan">Seruan</option>
                                <option value="info_resmi">Info Resmi</option>
                            </select>
                        </div>
                        <div class="col-md-3"><input name="judul" class="form-control form-control-sm" placeholder="Judul" required></div>
                        <div class="col-md-3"><input name="sumber" class="form-control form-control-sm" placeholder="Sumber/akun/URL"></div>
                        <div class="col-md-1"><input name="jangkauan" class="form-control form-control-sm" type="number" placeholder="Reach"></div>
                        <div class="col-md-12 mt-2"><textarea name="konten" class="form-control form-control-sm" rows="2" placeholder="Isi konten (akan dianalisis otomatis untuk deteksi hoax/rumor)" required></textarea></div>
                        <div class="col-12 mt-2">
                            <select name="simulation_id" class="form-select form-select-sm">
                                <option value="">— Tanpa Simulasi —</option>
                                @foreach($simulations as $s)
                                    <option value="{{ $s->id }}">#{{ $s->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-2"><button class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i>Simpan & Analisis</button></div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead><tr>
                        <th>#</th><th>Platform</th><th>Judul</th><th>Konten</th><th>Sentimen</th><th>Status</th><th>Analisis</th><th>Aksi</th>
                    </tr></thead>
                    <tbody>
                    @forelse($medsos as $m)
                        <tr>
                            <td>{{ $m->id }}</td>
                            <td>{{ $m->platform }}</td>
                            <td>{{ Str::limit($m->judul, 40) }}</td>
                            <td>{{ Str::limit($m->konten, 60) }}</td>
                            <td>
                                <span class="badge bg-{{ $m->sentimen === 'negatif' ? 'danger' : ($m->sentimen === 'positif' ? 'success' : 'secondary') }}">{{ ucfirst($m->sentimen) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $m->status === 'hoax_terkonfirmasi' ? 'danger' : ($m->status === 'ditangani' ? 'warning' : 'info') }}">{{ ucfirst(str_replace('_', ' ', $m->status)) }}</span>
                            </td>
                            <td>
                                @if($m->analisis)
                                    @if(($m->analisis['is_hoax'] ?? false)) <span class="badge bg-danger">HOAX</span> @endif
                                    @if(($m->analisis['is_rumor'] ?? false)) <span class="badge bg-warning">RUMOR</span> @endif
                                    @if(($m->analisis['urgency'] ?? '') === 'tinggi') <span class="badge bg-orange">URGENT</span> @endif
                                @else — @endif
                            </td>
                            <td>
                                <form action="{{ route('krisis.medsos.update', $m) }}" method="POST" class="d-flex gap-1">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="aktif" @selected($m->status == 'aktif')>Aktif</option>
                                        <option value="ditangani" @selected($m->status == 'ditangani')>Ditangani</option>
                                        <option value="hoax_terkonfirmasi" @selected($m->status == 'hoax_terkonfirmasi')>Hoax</option>
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-check"></i></button>
                                </form>
                                <form action="{{ route('krisis.medsos.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus?')" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">Belum ada data media sosial</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 pb-3">{{ $medsos->links() }}</div>
        </div>

        <!-- Komunikasi Krisis -->
        <div class="tab-pane fade" id="komunikasi">
            <div class="card mb-3">
                <div class="card-header"> Buat Komunikasi Krisis</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('krisis.store') }}" class="row g-2">
                        @csrf
                        <div class="col-md-3">
                            <select name="jenis" class="form-select form-select-sm" required>
                                <option value="">Jenis</option>
                                <option value="siaran_pers">Siaran Pers</option>
                                <option value="briefing_media">Briefing Media</option>
                                <option value="pernyataan_pimpinan">Pernyataan Pimpinan</option>
                                <option value="klarifikasi">Klarifikasi</option>
                            </select>
                        </div>
                        <div class="col-md-3"><input name="judul" class="form-control form-control-sm" placeholder="Judul" required></div>
                        <div class="col-md-2"><input name="audiens" class="form-control form-control-sm" placeholder="Audiens"></div>
                        <div class="col-md-2">
                            <select name="status" class="form-select form-select-sm" required>
                                <option value="draf">Draf</option>
                                <option value="terbit">Terbit</option>
                            </select>
                        </div>
                        <div class="col-md-2"><select name="simulation_id" class="form-select form-select-sm">
                            <option value="">— Tanpa Simulasi —</option>
                            @foreach($simulations as $s)<option value="{{ $s->id }}">#{{ $s->id }}</option>@endforeach
                        </select></div>
                        <div class="col-12 mt-2"><textarea name="isi" class="form-control form-control-sm" rows="3" placeholder="Isi komunikasi (isi otomatis template tersedia)" required></textarea></div>
                        <div class="col-12 mt-2"><button class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i>Simpan</button></div>
                    </form>
                </div>
            </div>

            <table class="table table-sm table-hover">
                <thead><tr><th>#</th><th>Jenis</th><th>Judul</th><th>Audiens</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($komunikasi as $k)
                    <tr>
                        <td>{{ $k->id }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $k->jenis)) }}</td>
                        <td>{{ Str::limit($k->judul, 40) }}</td>
                        <td>{{ $k->audiens ?? '—' }}</td>
                        <td>
                            <form action="{{ route('krisis.update', $k) }}" method="POST" class="d-flex gap-1">
                                @csrf
                                <select name="status" class="form-select form-select-sm">
                                    <option value="draf" @selected($k->status == 'draf')>Draf</option>
                                    <option value="terbit" @selected($k->status == 'terbit')>Terbit</option>
                                    <option value="edit" @selected($k->status == 'edit')>DiEdit</option>
                                </select>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-check"></i></button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('krisis.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus?')" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada data komunikasi krisis</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
