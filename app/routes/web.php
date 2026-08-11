<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\LeadershipController;
use App\Http\Controllers\AarController;
use App\Http\Controllers\KomunikasiKrisisController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\Api\TacticalApiController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Landing
Route::get('/', fn () => view('welcome'));

// Auth (Breeze-style manual)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// === Aplikasi Utama (wajib login) ===
Route::middleware('auth')->group(function () {
    // Dashboard + Peta
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/peta', [DashboardController::class, 'maps'])->name('maps');

    // Simulasi (CRUD)
    Route::get('/simulasi/riwayat', [SimulationController::class, 'history'])->name('simulations.history');
    Route::resource('simulasi', SimulationController::class)
        ->parameters(['simulasi' => 'simulation'])
        ->names('simulations');

    // === MARKER (Unit, Incident, Asset) ===
    Route::get('/taktis/marker', [MarkerController::class, 'index'])->name('tactical.markers');
    Route::post('/taktis/marker', [MarkerController::class, 'store'])->name('tactical.markers.store');
    Route::put('/taktis/marker/{marker}', [MarkerController::class, 'update'])->name('tactical.markers.update');
    Route::delete('/taktis/marker/{marker}', [MarkerController::class, 'destroy'])->name('tactical.markers.destroy');

    // === ZONA, ROUTE, OBJECTIVE ===
    Route::get('/taktis/zona', [ZoneController::class, 'index'])->name('tactical.zones');
    Route::post('/taktis/zona', [ZoneController::class, 'store'])->name('tactical.zones.store');
    Route::put('/taktis/zona/{zone}', [ZoneController::class, 'update'])->name('tactical.zones.update');
    Route::delete('/taktis/zona/{zone}', [ZoneController::class, 'destroy'])->name('tactical.zones.destroy');

    // === AUDIT LOG ===
    Route::get('/taktis/audit', [AuditController::class, 'index'])->name('tactical.audit');

    // === EXPORT ===
    Route::get('/export/csv', [ExportController::class, 'simulationCsv'])->name('export.csv');
    Route::get('/export/briefing/{simulation}', [ExportController::class, 'briefing'])->name('export.briefing');

    // === ORGANISASI (POLRI, HANKAM, PEMDA) ===
    Route::get('/taktis/organisasi', [OrganizationController::class, 'index'])->name('tactical.organizations');
    Route::post('/taktis/organisasi', [OrganizationController::class, 'store'])->name('tactical.organizations.store');

    // === LEADERSHIP (Dashboard, Penilaian Kepemimpinan) ===
    Route::get('/leadership', [LeadershipController::class, 'dashboard'])->name('leadership.dashboard');
    Route::get('/leadership/buat', [LeadershipController::class, 'create'])->name('leadership.create');
    Route::post('/leadership/simpan', [LeadershipController::class, 'store'])->name('leadership.store');
    Route::get('/leadership/{assessment}', [LeadershipController::class, 'show'])->name('leadership.show');
    Route::delete('/leadership/{assessment}', [LeadershipController::class, 'destroy'])->name('leadership.destroy');

    // === AAR (After Action Review) ===
    Route::get('/aar', [AarController::class, 'index'])->name('aar.index');
    Route::post('/aar/simpan', [AarController::class, 'store'])->name('aar.store');
    Route::delete('/aar/{session}', [AarController::class, 'destroy'])->name('aar.destroy');
    Route::get('/aar/laporan', [AarController::class, 'report'])->name('aar.report');
    Route::get('/aar/laporan/simulasi/{simulation}', [AarController::class, 'report'])->name('aar.report.simulation');

    // === KOMUNIKASI KRISIS + MEDIA SOSIAL + ANALITIK (PDF poin 3b) ===
    Route::get('/krisis', [KomunikasiKrisisController::class, 'index'])->name('krisis.index');
    Route::post('/krisis/medsos/store', [KomunikasiKrisisController::class, 'storeMedsos'])->name('krisis.medsos.store');
    Route::post('/krisis/krisis/store', [KomunikasiKrisisController::class, 'storeKrisis'])->name('krisis.store');
    Route::post('/krisis/medsos/{media}/status', [KomunikasiKrisisController::class, 'updateStatus'])->name('krisis.medsos.update');
    Route::post('/krisis/krisis/{krisis}/status', [KomunikasiKrisisController::class, 'updateKrisis'])->name('krisis.krisis.update');
    Route::delete('/krisis/medsos/{media}', [KomunikasiKrisisController::class, 'destroyMedsos'])->name('krisis.medsos.destroy');
    Route::delete('/krisis/krisis/{krisis}', [KomunikasiKrisisController::class, 'destroyKrisis'])->name('krisis.krisis.destroy');

    // === KURIKULUM SESPIM / SESP IMMEN / SESPIMTI (PDF poin 2g & 3e) ===
    Route::get('/kurikulum', [KurikulumController::class, 'index'])->name('kurikulum.index');
    Route::post('/kurikulum/mapping', [KurikulumController::class, 'storeMapping'])->name('kurikulum.mapping.store');
    Route::post('/kurikulum/progress/store', [KurikulumController::class, 'storeProgress'])->name('kurikulum.progress.store');
    Route::put('/kurikulum/progress/{progress}', [KurikulumController::class, 'updateProgress'])->name('kurikulum.progress.update');
});

// === API (untuk live sync) ===
Route::middleware('auth')->prefix('api/v1')->group(function () {
    Route::get('/sync', [TacticalApiController::class, 'sync'])->name('api.sync');
    Route::get('/replay', [TacticalApiController::class, 'replay'])->name('api.replay');
    Route::get('/timeline', [TacticalApiController::class, 'timeline'])->name('api.timeline');
    Route::get('/assessments', [TacticalApiController::class, 'assessments'])->name('api.assessments');
});

// Fallback: redirect ke dashboard
Route::fallback(fn () => redirect()->route('dashboard'));
