<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SimulationController;
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

// Aplikasi (wajib login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/peta', [DashboardController::class, 'maps'])->name('maps');

    Route::get('/simulasi/riwayat', [SimulationController::class, 'history'])->name('simulations.history');
    Route::resource('simulasi', SimulationController::class)
        ->parameters(['simulasi' => 'simulation'])
        ->names('simulations');
});

// Fallback: redirect ke dashboard
Route::fallback(fn () => redirect()->route('dashboard'));