<?php

namespace App\Http\Controllers;

use App\Models\BlockedSlot;
use App\Models\Space;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlockedSlotController extends Controller
{
    /**
     * Display a listing of blocked slots for admin (Web view).
     */
    public function indexWeb()
    {
        $blockedSlots = BlockedSlot::with('space')->get();
        return Inertia::render('Admin/BlockedSlots/Index', [
            'blockedSlots' => $blockedSlots,
        ]);
    }

    /**
     * Show create form for admin (Web view).
     */
    public function createWeb()
    {
        $spaces = Space::all();
        return Inertia::render('Admin/BlockedSlots/Create', [
            'spaces' => $spaces,
        ]);
    }

    /**
     * Store a newly created blocked slot (API).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'space_id' => 'required|exists:spaces,id',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
            'reason' => 'required|string|max:255',
        ]);

        $blockedSlot = BlockedSlot::create($validated);
        return response()->json($blockedSlot->load('space'), 201);
    }

    /**
     * Remove the specified blocked slot (API).
     */
    public function destroy(BlockedSlot $blockedSlot)
    {
        $blockedSlot->delete();
        return response()->json(['message' => 'Bloqueo eliminado correctamente']);
    }
}
