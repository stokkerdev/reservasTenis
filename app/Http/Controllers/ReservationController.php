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

  
    public function storeWeb(ReservationRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['user_name'] = $request->user()->name;
        $data['user_email'] = $request->user()->email;
        $data['status'] = 'confirmed';
        
        if (Reservation::hasConflict($data['space_id'], $data['start_time'], $data['end_time'])) {
            return back()->withErrors(['start_time' => 'El horario seleccionado ya está reservado o bloqueado.']);
        }
        
        $reservation = Reservation::create($data);
        return redirect()->route('reservations.user.index')->with('success', 'Reserva creada correctamente');
    }

    /**
     * Store a newly created resource (Web/Inertia).
     */

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
