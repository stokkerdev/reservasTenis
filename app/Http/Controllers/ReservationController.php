<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Space;
use App\Http\Requests\ReservationRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Reservation::with('space')->get();
    }

    /**
     * Display the admin reservations page.
     */
    public function indexWeb()
    {
        $reservations = Reservation::with('space')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Reservations/Index', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Display the current user's reservations page.
     */
    public function userReservationsWeb()
    {
        $reservations = Reservation::with('space')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Reservations/Index', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Display the create reservation page.
     */
    public function createWeb()
    {
        return Inertia::render('Reservations/Create', [
            'spaces' => Space::where('is_active', true)->get(),
        ]);
    }

  
    public function store(ReservationRequest $request)
    {
        $reservation = Reservation::create($request->validated());
        return response()->json($reservation->load('space'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reservation = Reservation::with('space')->findOrFail($id);
        return response()->json($reservation);
    }

   
    public function update(ReservationRequest $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->validated());
        return response()->json($reservation->load('space'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return response()->json(['message' => 'Reservation deleted successfully']);
    }
}
