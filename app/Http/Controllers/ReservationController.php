<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Space;
use App\Http\Requests\ReservationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource (API).
     */
    public function index()
    {
        return Reservation::with('space')->get();
    }

    /**
     * Display admin reservations management view.
     */
    public function indexWeb()
    {
        $reservations = Reservation::with('space', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Reservations/Index', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Display user reservations view.
     */
    public function userReservationsWeb()
    {
        $reservations = Reservation::where('user_id', auth()->id())
            ->with('space')
            ->orderBy('start_time', 'desc')
            ->get();

        return Inertia::render('Reservations/Index', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Show create reservation form.
     */
    public function createWeb()
    {
        $spaces = Space::where('is_active', true)->get();

        return Inertia::render('Reservations/Create', [
            'spaces' => $spaces,
        ]);
    }

    /**
     * Store a newly created resource (Web/Inertia).
     */
    public function storeWeb(ReservationRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['user_name'] = auth()->user()->name;
        $data['user_email'] = auth()->user()->email;

        if (Reservation::hasConflict($data['space_id'], $data['start_time'], $data['end_time'])) {
            return response()->json(['errors' => ['general' => 'Conflicto de horario. La cancha ya está reservada o bloqueada en ese período.']], 409);
        }

        $data['status'] = 'confirmed';
        $reservation = Reservation::create($data);

        return response()->json(['message' => 'Reserva creada correctamente.', 'reservation' => $reservation->load('space')], 201);
    }

    /**
     * Store a newly created resource (API).
     */
    public function store(ReservationRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['user_name'] = auth()->user()->name;
        $data['user_email'] = auth()->user()->email;

        // Check for conflicts
        if (Reservation::hasConflict($data['space_id'], $data['start_time'], $data['end_time'])) {
            return response()->json(['message' => 'Conflicto de horario. La cancha ya está reservada o bloqueada en ese período.'], 409);
        }

        $data['status'] = 'confirmada'; // Auto-confirm if no conflicts

        $reservation = Reservation::create($data);
        return response()->json($reservation->load('space'), 201);
    }

    /**
     * Display the specified resource (API).
     */
    public function show(string $id)
    {
        $reservation = Reservation::with('space')->findOrFail($id);
        return response()->json($reservation);
    }

    /**
     * Update the specified resource (API).
     */
    public function update(ReservationRequest $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);

        $data = $request->validated();

        // Check for conflicts, excluding the current reservation
        if (Reservation::hasConflict($data['space_id'], $data['start_time'], $data['end_time'], $reservation->id)) {
            return response()->json(['message' => 'Conflicto de horario. La cancha ya está reservada o bloqueada en ese período.'], 409);
        }

        $reservation->update($data);
        return response()->json($reservation->load('space'));
    }



    public function editWeb(string $id)
    {
        $reservation = Reservation::with('space')->findOrFail($id);
        $spaces = Space::where('is_active', true)->get();

        if ($reservation->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->route('reservations')->with('error', 'No tienes permiso para editar esta reserva.');
        }

        return Inertia::render('Reservations/Edit', [
            'reservation' => $reservation,
            'spaces' => $spaces,
        ]);
    }

   
    
    public function destroy(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Authorization check: user can only delete their own reservations, unless admin
        if ($reservation->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta reserva.'], 403);
        }
        
        $reservation->delete();
        return response()->json(['message' => 'Reserva eliminada correctamente']);
    }

    public function updateWeb(ReservationRequest $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);

        // Aqui reviso si puede editar, si el usuario es duiaño de su porpia reserva, si la puede editar o si es admin
        if ($reservation->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->route('reservations')->with('error', 'No tienes permiso para editar esta reserva.');
        }

        $data = $request->validated();

        // Check for conflicts, excluding the current reservation
        if (Reservation::hasConflict($data['space_id'], $data['start_time'], $data['end_time'], $reservation->id)) {
            return redirect()->back()->withInput()->withErrors([
                'general' => 'Conflicto de horario. La cancha ya está reservada o bloqueada en ese período.'
            ]);
        }

        $reservation->update($data);

        return redirect()->route('reservations')->with('success', 'Reserva actualizada correctamente.');
    }

    /**
     * Admin: Accept a reservation.
     */
    public function accept($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'confirmada']);

        // TODO: Enviar correo de confirmación

        return response()->json(['message' => 'Reserva confirmada', 'reservation' => $reservation]);
    }

    /**
     * Admin: Reject a reservation.
     */
    public function reject($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'rechazada']);

        // TODO: Enviar correo de rechazo

        return response()->json(['message' => 'Reserva rechazada', 'reservation' => $reservation]);
    }

    /**
     * Shared: Cancel a reservation.
     */
    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);

        if (!in_array($reservation->status, ['pendiente', 'confirmada'])) {
            return response()->json(['message' => 'No se puede cancelar una reserva en estado ' . $reservation->status], 422);
        }

        $reservation->update(['status' => 'cancelada']);

        // TODO: Enviar correo de cancelación

        return response()->json(['message' => 'Reserva cancelada', 'reservation' => $reservation]);
    }

    /**
     * Get available time blocks for a specific space and date (Web/Inertia).
     */
    public function getAvailableTimeBlocksWeb(Request $request, Space $space)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $date = Carbon::parse($request->input('date'));
        $intervalMinutes = 60;

        $allBlocks = $space->generateTimeBlocks($date, $intervalMinutes);

        $availableBlocks = [];
        foreach ($allBlocks as $block) {
            if (!Reservation::hasConflict($space->id, $block['start_time'], $block['end_time'])) {
                $availableBlocks[] = $block;
            }
        }

        return response()->json($availableBlocks);
    }

    /**
     * Get available time blocks for a specific space and date.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Space $space
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableTimeBlocks(Request $request, Space $space)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $date = Carbon::parse($request->input('date'));
        $intervalMinutes = 60; // Assuming 1-hour blocks

        $allBlocks = $space->generateTimeBlocks($date, $intervalMinutes);

        $availableBlocks = [];
        foreach ($allBlocks as $block) {
            if (!Reservation::hasConflict($space->id, $block['start_time'], $block['end_time'])) {
                $availableBlocks[] = $block;
            }
        }

        return response()->json($availableBlocks);
    }
}
