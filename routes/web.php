<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\BlockedSlotController;
use App\Http\Controllers\ReservationController;

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

        // BlockedSlots management
        Route::get('/admin/blocked-slots', [BlockedSlotController::class, 'indexWeb'])->name('blocked-slots.index');
        Route::get('/admin/blocked-slots/create', [BlockedSlotController::class, 'createWeb'])->name('blocked-slots.create');

        // Reservations management
        Route::get('/admin/reservations', [ReservationController::class, 'indexWeb'])->name('reservations.admin.index');
    });

    // User routes
    Route::get('/reservations', [ReservationController::class, 'userReservationsWeb'])->name('reservations.user.index');
    Route::get('/reservations/create', [ReservationController::class, 'createWeb'])->name('reservations.create');
    Route::get('/spaces/{space}', [SpaceController::class, 'showWeb'])->name('spaces.show');
});
