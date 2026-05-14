<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\BlockedSlotController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\StatisticsController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'spaces' => \App\Models\Space::where('is_active', true)->get(),
    ]);
});

// Rutas públicas
Route::get('/terminos', function () {
    return Inertia::render('TermsOfService');
})->name('terms');

Route::get('/privacidad', function () {
    return Inertia::render('PrivacyPolicy');
})->name('privacy');

Route::get('/contacto', function () {
    return Inertia::render('Contact');
})->name('contact');

Route::post('/contacto', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard con estadísticas según rol
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return app(StatisticsController::class)->adminDashboard();
        }
        return app(StatisticsController::class)->clientDashboard();
    })->name('dashboard');

    // Admin routes
    Route::middleware('admin')->group(function () {
        // Spaces management
        Route::get('/admin/spaces', [SpaceController::class, 'indexWeb'])->name('admin.spaces.index');
        Route::get('/admin/spaces/create', [SpaceController::class, 'createWeb'])->name('admin.spaces.create');
        Route::post('/admin/spaces', [SpaceController::class, 'storeWeb'])->name('admin.spaces.store');
        Route::get('/admin/spaces/{space}/edit', [SpaceController::class, 'editWeb'])->name('admin.spaces.edit');
        Route::put('/admin/spaces/{space}', [SpaceController::class, 'updateWeb'])->name('admin.spaces.update');
        Route::delete('/admin/spaces/{space}', [SpaceController::class, 'destroyWeb'])->name('admin.spaces.destroy');

        // BlockedSlots management
        Route::get('/admin/blocked-slots', [BlockedSlotController::class, 'indexWeb'])->name('admin.blocked-slots.index');
        Route::get('/admin/blocked-slots/create', [BlockedSlotController::class, 'createWeb'])->name('admin.blocked-slots.create');
        Route::post('/admin/blocked-slots', [BlockedSlotController::class, 'storeWeb'])->name('admin.blocked-slots.store');
        Route::get('/admin/blocked-slots/{blockedSlot}/edit', [BlockedSlotController::class, 'editWeb'])->name('admin.blocked-slots.edit');
        Route::put('/admin/blocked-slots/{blockedSlot}', [BlockedSlotController::class, 'updateWeb'])->name('admin.blocked-slots.update');
        Route::delete('/admin/blocked-slots/{blockedSlot}', [BlockedSlotController::class, 'destroyWeb'])->name('admin.blocked-slots.destroy');

        // Reservations management
        Route::get('/admin/reservations', [ReservationController::class, 'indexWeb'])->name('reservations.admin.index');
        Route::get('/admin/reservations/{reservation}/edit', [ReservationController::class, 'editWeb'])->name('reservations.admin.edit');
        Route::put('/admin/reservations/{reservation}', [ReservationController::class, 'updateWeb'])->name('reservations.admin.update');
        Route::delete('/admin/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.admin.destroy');
        Route::post('/admin/reservations/{reservation}/accept', [ReservationController::class, 'accept'])->name('reservations.admin.accept');
        Route::post('/admin/reservations/{reservation}/reject', [ReservationController::class, 'reject'])->name('reservations.admin.reject');
        Route::post('/admin/reservations/{reservation}/set-pending', [ReservationController::class, 'setPending'])->name('reservations.admin.set-pending');
        Route::post('/admin/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.admin.cancel');

        // Availabilities management
        Route::get('/admin/availabilities', [AvailabilityController::class, 'indexWeb'])->name('admin.availabilities.index');
        Route::get('/admin/availabilities/create', [AvailabilityController::class, 'createWeb'])->name('admin.availabilities.create');
        Route::post('/admin/availabilities', [AvailabilityController::class, 'storeWeb'])->name('admin.availabilities.store');
        Route::get('/admin/availabilities/{availability}/edit', [AvailabilityController::class, 'editWeb'])->name('admin.availabilities.edit');
        Route::put('/admin/availabilities/{availability}', [AvailabilityController::class, 'updateWeb'])->name('admin.availabilities.update');
        Route::delete('/admin/availabilities/{availability}', [AvailabilityController::class, 'destroyWeb'])->name('admin.availabilities.destroy');
    });

    // User routes
    Route::get('reservations', [ReservationController::class, 'userReservationsWeb'])->name('reservations.user.index');
    Route::get('/reservations/create', [ReservationController::class, 'createWeb'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'storeWeb'])->name('reservations.store');
    Route::get('/spaces/{space:slug}', [SpaceController::class, 'showWeb'])->name('spaces.show');
    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'editWeb'])->name('reservations.edit');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'updateWeb'])->name('reservations.update');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    Route::get('/spaces/{space}/available-time-blocks', [ReservationController::class, 'getAvailableTimeBlocksWeb'])->name('spaces.available-time-blocks');
});
