<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\BlockedSlotController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AvailabilityController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Admin routes
    Route::middleware('admin')->group(function () {
        // Spaces management
        Route::get('/admin/spaces', [SpaceController::class, 'indexWeb'])->name('spaces.index');
        Route::get('/admin/spaces/create', [SpaceController::class, 'createWeb'])->name('spaces.create');
        Route::post('/admin/spaces', [SpaceController::class, 'storeWeb'])->name('spaces.store');
        Route::get('/admin/spaces/{space}/edit', [SpaceController::class, 'editWeb'])->name('spaces.edit');
        Route::put('/admin/spaces/{space}', [SpaceController::class, 'updateWeb'])->name('spaces.update');
        Route::delete('/admin/spaces/{space}', [SpaceController::class, 'destroyWeb'])->name('spaces.destroy');

        // BlockedSlots management
        Route::get('/admin/blocked-slots', [BlockedSlotController::class, 'indexWeb'])->name('blocked-slots.index');
        Route::get('/admin/blocked-slots/create', [BlockedSlotController::class, 'createWeb'])->name('blocked-slots.create');
        Route::post('/admin/blocked-slots', [BlockedSlotController::class, 'storeWeb'])->name('blocked-slots.store');
        Route::delete('/admin/blocked-slots/{blockedSlot}', [BlockedSlotController::class, 'destroyWeb'])->name('blocked-slots.destroy');

        // Reservations management
        Route::get('/admin/reservations', [ReservationController::class, 'indexWeb'])->name('reservations.admin.index');

        // Availabilities management
        Route::get('/admin/availabilities', [AvailabilityController::class, 'indexWeb'])->name('availabilities.index');
        Route::get('/admin/availabilities/create', [AvailabilityController::class, 'createWeb'])->name('availabilities.create');
        Route::post('/admin/availabilities', [AvailabilityController::class, 'storeWeb'])->name('availabilities.store');
        Route::get('/admin/availabilities/{availability}/edit', [AvailabilityController::class, 'editWeb'])->name('availabilities.edit');
        Route::put('/admin/availabilities/{availability}', [AvailabilityController::class, 'updateWeb'])->name('availabilities.update');
        Route::delete('/admin/availabilities/{availability}', [AvailabilityController::class, 'destroyWeb'])->name('availabilities.destroy');
    });

    // User routes
    Route::get('/reservations', [ReservationController::class, 'userReservationsWeb'])->name('reservations.user.index');
    Route::get('/reservations/create', [ReservationController::class, 'createWeb'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'storeWeb'])->name('reservations.store');
    Route::get('/spaces/{space}', [SpaceController::class, 'showWeb'])->name('spaces.show');
});
