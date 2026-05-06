<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Http\Requests\AvailabilityRequest;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Availability::with('space')->get();
    }

 
   
    public function store(AvailabilityRequest $request)
    {
        $availability = Availability::create($request->validated());
        return response()->json($availability->load('space'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $availability = Availability::with('space')->findOrFail($id);
        return response()->json($availability);
    }


    public function update(AvailabilityRequest $request, string $id)
    {
        $availability = Availability::findOrFail($id);
        $availability->update($request->validated());
        return response()->json($availability->load('space'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $availability = Availability::findOrFail($id);
        $availability->delete();
        return response()->json(['message' => 'Availability deleted successfully']);
    }
}
