<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\OrganizationController;
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
    // Dashboard
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
});

// === API (tanpa CSRF, untuk live sync) ===
Route::middleware('auth')->prefix('api/v1')->group(function () {
    Route::get('/sync', [TacticalApiController::class, 'sync'])->name('api.sync');
    Route::get('/replay', [TacticalApiController::class, 'replay'])->name('api.replay');
    Route::get('/timeline', [TacticalApiController::class, 'timeline'])->name('api.timeline');
});

// Fallback: redirect ke dashboard
Route::fallback(fn () => redirect()->route('dashboard'));
