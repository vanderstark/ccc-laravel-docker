@extends('layouts.app')

@section('title', 'Riwayat Simulasi')

@section('content')
<h4 class="mb-4"><i class="bi bi-clock-history me-2"></i>Riwayat Simulasi</h4>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari lokasi..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>#</th><th>Tipe</th><th>Lokasi</th><th>Klasifikasi</th><th>Level</th><th>Terdampak</th><th>Waktu</th><th></th></tr></thead>
            <tbody>
                @php $colors = ['merah'=>'danger','oranye'=>'warning','kuning'=>'info','hijau'=>'success']; @endphp
                @forelse($simulations as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ $s->disasterType?->nama }}</td>
                    <td>{{ $s->location }}</td>
                    <td>{{ $s->classification }}</td>
                    <td><span class="badge badge-alert bg-{{ $colors[$s->alert_level] ?? 'secondary' }}">{{ $s->alert_level }}</span></td>
                    <td>{{ number_format($s->affected_population) }}</td>
                    <td class="text-muted small">{{ $s->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <a href="{{ route('simulations.show', $s) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        <form method="POST" action="{{ route('simulations.destroy', $s) }}" class="d-inline" onsubmit="return confirm('Hapus simulasi ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada riwayat simulasi.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $simulations->links() }}
    </div>
</div>
@endsection