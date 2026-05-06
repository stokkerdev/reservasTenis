<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ReservationController;

Route::apiResource('spaces', SpaceController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('availabilities', AvailabilityController::class);
Route::apiResource('reservations', ReservationController::class);