<?php

namespace App\Http\Controllers;

use App\Models\Meetup;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MeetupController extends Controller
{
    public function index(): JsonResponse
    {
        $meetups = Meetup::with('attendances')->get();
        return response()->json($meetups, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meetup_date' => 'required|date', // Validamos solo la fecha
            'location' => 'nullable|string|max:255', // Ahora es opcional
            'max_capacity' => 'nullable|integer|min:1', // Ahora es opcional
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $meetup = Meetup::create($validator->validated());
        return response()->json($meetup, 201);
    }

    public function show(Meetup $meetup): JsonResponse
    {
        $meetup->load('attendances.user');
        return response()->json($meetup, 200);
    }

    public function update(Request $request, Meetup $meetup): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'meetup_date' => 'sometimes|required|date',
            'location' => 'sometimes|nullable|string|max:255',
            'max_capacity' => 'sometimes|nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $meetup->update($validator->validated());
        return response()->json($meetup, 200);
    }

    public function destroy(Meetup $meetup): JsonResponse
    {
        $meetup->delete();
        return response()->json(['message' => 'Meetup deleted successfully'], 200);
    }
}