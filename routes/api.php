<?php

declare(strict_types=1);

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserVehicleController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class)
    ->only(['show'])
    ->names([
        'show' => 'api.users.show',
    ]);

Route::apiResource('users.vehicles', UserVehicleController::class)
    ->only(['store'])
    ->names([
        'store' => 'api.users.vehicles.store',
    ]);

Route::apiResource('vehicles', VehicleController::class)
    ->only(['index'])
    ->names([
        'index' => 'api.vehicles.index',
    ]);
