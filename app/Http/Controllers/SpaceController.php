<?php

namespace App\Http\Controllers;
use App\Models\Space;
use Illuminate\Http\Request;
use App\Http\Requests\SpaceRequest;
use Inertia\Inertia;

class SpaceController extends Controller
{
    /**
     * Display a listing of the resource (API).
     */
    public function index()
    {
        return Space::all();
    }

    /**
     * Display a listing of spaces for admin (Web view).
     */
    public function indexWeb()
    {
        $spaces = Space::all();
        return Inertia::render('Admin/Spaces/Index', [
            'spaces' => $spaces,
        ]);
    }

    /**
     * Show create form for admin (Web view).
     */
    public function createWeb()
    {
        return Inertia::render('Admin/Spaces/Create');
    }

    /**
     * Show edit form for admin (Web view).
     */
    public function editWeb(Space $space)
    {
        return Inertia::render('Admin/Spaces/Edit', [
            'space' => $space,
        ]);
    }

    /**
     * Show space detail (Web view).
     */
    public function showWeb(Space $space)
    {
        $space->load('availabilities', 'reservations');
        return Inertia::render('Spaces/Show', [
            'space' => $space,
        ]);
    }

    /**
     * Store a newly created resource (API).
     */
    public function store(SpaceRequest $request)
    {
        $space = Space::create($request->validated());
        return response()->json($space, 201);
    }

    /**
     * Display the specified resource (API).
     */
    public function show(Space $space)
    {
        return response()->json($space);
    }

    /**
     * Update the specified resource (API).
     */
    public function update(SpaceRequest $request, Space $space)
    {
        $space->update($request->validated());
        return response()->json($space);
    }

    /**
     * Store a newly created resource (Web/Inertia).
     */
    public function storeWeb(SpaceRequest $request)
    {
        $space = Space::create($request->validated());
        return redirect()->route('spaces.index')->with('success', 'Cancha creada correctamente');
    }

    /**
     * Update the specified resource (Web/Inertia).
     */
    public function updateWeb(SpaceRequest $request, Space $space)
    {
        $space->update($request->validated());
        return redirect()->route('spaces.index')->with('success', 'Cancha actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Space $space)
    {
        $space->delete();
        return response()->json([
            'message' => 'Cancha eliminada correctamente'
        ]);
    }
}
