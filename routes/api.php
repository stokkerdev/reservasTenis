<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;

// Public auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Admin-only routes for management
    Route::middleware('admin')->group(function () {
        Route::post('spaces', [SpaceController::class, 'store']);
        Route::put('spaces/{space}', [SpaceController::class, 'update']);
        Route::delete('spaces/{space}', [SpaceController::class, 'destroy']);
        
        Route::apiResource('users', UserController::class);
        Route::apiResource('availabilities', AvailabilityController::class);
        
        // Admin reservation actions (approve/reject/cancel)
        Route::post('reservations/{id}/accept', [ReservationController::class, 'accept']);
        Route::post('reservations/{id}/reject', [ReservationController::class, 'reject']);
    });

    // Shared routes (Admin and User)
    Route::get('spaces', [SpaceController::class, 'index']);
    Route::get('spaces/{space}', [SpaceController::class, 'show']);
    
    Route::get('reservations', [ReservationController::class, 'index']);
    Route::post('reservations', [ReservationController::class, 'store']);
    Route::get('reservations/{reservation}', [ReservationController::class, 'show']);
    Route::post('reservations/{id}/cancel', [ReservationController::class, 'cancel']);

    // New route to get available time blocks for a space and date
    Route::get('spaces/{space}/available-time-blocks', [ReservationController::class, 'getAvailableTimeBlocks']);
});