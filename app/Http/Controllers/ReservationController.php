<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Requests\ReservationRequest;
use Illuminate\Http\Request;

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
     * Store a newly created resource in storage.
     */
    public function store(ReservationRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 'pendiente'; // Estado inicial según documento
        $reservation = Reservation::create($data);
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

    /**
     * Update the specified resource in storage.
     */
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
        return response()->json(['message' => 'Reserva eliminada correctamente']);
    }

    /**
     * Admin: Accept a reservation.
     */
    public function accept($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'confirmada']);
        
        // Aquí iría el envío de correo según el documento
        
        return response()->json(['message' => 'Reserva confirmada', 'reservation' => $reservation]);
    }

    /**
     * Admin: Reject a reservation.
     */
    public function reject($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'rechazada']);
        
        // Aquí iría el envío de correo según el documento
        
        return response()->json(['message' => 'Reserva rechazada', 'reservation' => $reservation]);
    }

    /**
     * Shared: Cancel a reservation.
     */
    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Solo permitir cancelar si está pendiente o confirmada
        if (!in_array($reservation->status, ['pendiente', 'confirmada'])) {
            return response()->json(['message' => 'No se puede cancelar una reserva en estado ' . $reservation->status], 422);
        }

        $reservation->update(['status' => 'cancelada']);
        
        // Aquí iría el envío de correo según el documento
        
        return response()->json(['message' => 'Reserva cancelada', 'reservation' => $reservation]);
    }
}
