<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CCC — Crisis Command Center') | Sistem Simulasi Bencana & Operasi Militer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background:#0f1923; color:#e6edf3; font-family:'Segoe UI',system-ui,sans-serif; }
        .navbar { background:#161b22; border-bottom:2px solid #1f6feb; }
        .navbar-brand { font-weight:700; letter-spacing:-.5px; }
        .card { background:#161b22; border:1px solid #30363d; border-radius:12px; }
        .stat-card { border-left:4px solid #1f6feb; }
        .stat-card.red { border-left-color:#f85149; }
        .stat-card.orange { border-left-color:#d29922; }
        .stat-card.green { border-left-color:#3fb950; }
        .table { color:#e6edf3; }
        .table thead th { border-bottom:2px solid #30363d; }
        .badge-alert { text-transform:uppercase; font-size:.72rem; letter-spacing:.5px; }
        .footer { color:#8b949e; font-size:.85rem; border-top:1px solid #21262d; }
        .btn-primary { background:#1f6feb; border-color:#1f6feb; }
        .form-control, .form-select { background:#0d1117; color:#e6edf3; border:1px solid #30363d; }
        .form-control:focus, .form-select:focus { background:#0d1117; color:#e6edf3; border-color:#1f6feb; box-shadow:0 0 0 .2rem rgba(31,111,235,.25); }
        .modal-content { background:#161b22; color:#e6edf3; }
        a { color:#58a6ff; }
        .list-group-item { background:#0d1117; color:#e6edf3; border-color:#30363d; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="bi bi-shield-fill-check me-2"></i>CCC — Crisis Command Center
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('simulations.create') }}"><i class="bi bi-plus-circle me-1"></i>Simulasi Baru</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('simulations.history') }}"><i class="bi bi-clock-history me-1"></i>Riwayat</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('maps') }}"><i class="bi bi-map me-1"></i>Peta</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#"><i class="bi bi-grid-1x2 me-1"></i>Taktis</a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="{{ route('tactical.markers') }}"><i class="bi bi-geo-alt me-2"></i>Marker (Unit/Incident/Asset)</a></li>
                        <li><a class="dropdown-item" href="{{ route('tactical.zones') }}"><i class="bi bi-map me-2"></i>Zona / Route / Objective</a></li>
                        <li><a class="dropdown-item" href="{{ route('tactical.organizations') }}"><i class="bi bi-building me-2"></i>Instansi (POLRI/HANKAM/PEMDA)</a></li>
                        <li><a class="dropdown-item" href="{{ route('tactical.audit') }}"><i class="bi bi-journal-check me-2"></i>Audit Trail</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('export.csv') }}"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Export CSV</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav">
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                        <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        @if(auth()->user()->role)<span class="badge bg-secondary ms-1">{{ auth()->user()->role->nama }}</span>@endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><form method="POST" action="{{ route('logout') }}">@csrf<button class="dropdown-item"><i class="bi bi-box-arrow-right me-1"></i>Keluar</button></form></li>
                    </ul>
                </li>
                @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i>Masuk</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container-fluid px-4 py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle me-1"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @yield('content')
</main>

<footer class="footer text-center py-3 mt-4">
    <p class="mb-0">© {{ date('Y') }} Crisis Command Center — Akademi Kepolisian · Sistem Simulasi Bencana & Operasi Militer<br>
    <small>Estimasi untuk perencanaan (decision support), bukan klaim presisi mutlak.</small></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>