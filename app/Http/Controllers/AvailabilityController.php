<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Space;
use App\Http\Requests\AvailabilityRequest;
use Inertia\Inertia;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource (API).
     */
    public function index()
    {
        return Availability::with('space')->get();
    }

    /**
     * Display a listing of the resource for admin (Web view).
     */
    public function indexWeb()
    {
        $availabilities = Availability::with('space')->orderBy('day_of_week')->get();
        return Inertia::render('Admin/Availabilities/Index', [
            'availabilities' => $availabilities,
        ]);
    }

    /**
     * Show the form for creating a new resource (Web view).
     */
    public function createWeb()
    {
        $spaces = Space::all();
        return Inertia::render('Admin/Availabilities/Create', [
            'spaces' => $spaces,
        ]);
    }

    /**
     * Show the form for editing the specified resource (Web view).
     */
    public function editWeb(Availability $availability)
    {
        $spaces = Space::all();
        return Inertia::render('Admin/Availabilities/Edit', [
            'availability' => $availability->load('space'),
            'spaces' => $spaces,
        ]);
    }

    /**
     * Store a newly created resource in storage (API).
     */
    public function store(AvailabilityRequest $request)
    {
        $availability = Availability::create($request->validated());
        return response()->json($availability->load('space'), 201);
    }

    /**
     * Store a newly created resource in storage (Web).
     */
    public function storeWeb(AvailabilityRequest $request)
    {
        Availability::create($request->validated());
        return redirect()->route('admin.availabilities.index')->with('success', 'Disponibilidad creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $availability = Availability::with('space')->findOrFail($id);
        return response()->json($availability);
    }

    /**
     * Update the specified resource in storage (API).
     */
    public function update(AvailabilityRequest $request, string $id)
    {
        $availability = Availability::findOrFail($id);
        $availability->update($request->validated());
        return response()->json($availability->load('space'));
    }

    /**
     * Update the specified resource in storage (Web).
     */
    public function updateWeb(AvailabilityRequest $request, Availability $availability)
    {
        $availability->update($request->validated());
        return redirect()->route('admin.availabilities.index')->with('success', 'Disponibilidad actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage (API).
     */
    public function destroy(string $id)
    {
        $availability = Availability::findOrFail($id);
        $availability->delete();
        return response()->json(['message' => 'Availability deleted successfully']);
    }

    /**
     * Remove the specified resource from storage (Web).
     */
    public function destroyWeb(Availability $availability)
    {
        $availability->delete();
        return redirect()->route('admin.availabilities.index')->with('success', 'Disponibilidad eliminada correctamente.');
    }
}
