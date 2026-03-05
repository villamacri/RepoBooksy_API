<?php

namespace App\Http\Controllers;

use App\Models\Meetup;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MeetupController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Meetup::with('attendances');

        $query->when($request->city, fn($q) => $q->where('city', $request->city));

        $meetups = $query->latest()->get();
        $authId = auth()->id();

        foreach ($meetups as $meetup) {
            $meetup->name = !empty($meetup->name) ? $meetup->name : 'Club de Lectura Sevilla';
            $meetup->title = $meetup->name;
            $meetup->is_joined = $authId
                ? $meetup->users()->where('user_id', $authId)->exists()
                : false;
        }

        $data = $meetups->toArray();
        return response()->json($data ?? [], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meetup_date' => 'required|date', // Validamos solo la fecha
            'city' => 'nullable|string|max:100',
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
        $meetup->name = !empty($meetup->name) ? $meetup->name : 'Club de Lectura Sevilla';
        $meetup->title = $meetup->name;
        $meetup->is_joined = auth()->id()
            ? $meetup->users()->where('user_id', auth()->id())->exists()
            : false;

        return response()->json($meetup, 200);
    }

    public function join(Meetup $meetup): JsonResponse
    {
        $userId = auth()->id();
        $meetup->users()->toggle($userId);

        return response()->json([
            'is_joined' => $meetup->users()->where('user_id', auth()->id())->exists(),
        ], 200);
    }

    public function update(Request $request, Meetup $meetup): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'meetup_date' => 'sometimes|required|date',
            'city' => 'sometimes|nullable|string|max:100',
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

    public function getParticipants(Meetup $meetup): JsonResponse
    {
        return response()->json($meetup->users, 200);
    }
}