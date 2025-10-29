<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VehicleDashboardController;
use App\Http\Controllers\VehicleMarketplaceController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::middleware('ensure-authenticated-user')->group(function (): void {
        Route::get('/vehicles', VehicleDashboardController::class)->name('vehicles.index');
        Route::get('/vehicles/marketplace', [VehicleMarketplaceController::class, 'index'])->name('vehicles.marketplace');
        Route::post('/vehicles/marketplace', [VehicleMarketplaceController::class, 'store'])->name('vehicles.marketplace.store');
    });
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('vehicles.index')
        : redirect()->route('login');
})->name('home');
