<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserVehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/users/{user}', [UserController::class, 'show'])
    ->whereNumber('user')
    ->name('api.users.show');

Route::post('/users/{user}/vehicles', [UserVehicleController::class, 'store'])
    ->whereNumber('user')
    ->name('api.users.vehicles.store');
