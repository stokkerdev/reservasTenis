<?php

namespace App\Http\Controllers;
use App\Models\Space;
use Illuminate\Http\Request;
use App\Http\Requests\SpaceRequest;

class SpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Space::all();
    }



    public function store(SpaceRequest $request)
    {
        $space = Space::create($request->validated());

        return response()->json($space, 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(Space $space)
    {
        return response()->json($space);
    }

    public function update(SpaceRequest $request, Space $space)
    {
        $space->update($request->validated());

        return response()->json($space);
    }
    public function destroy(Space $space)
    {
        $space->delete();

        return response()->json([
            'message' => 'Cancha eliminada correctamente'
        ]);
    }
}
