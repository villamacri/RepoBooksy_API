<?php

namespace App\Http\Controllers;

use App\Models\ParticipacionEvento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ParticipacionEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $participaciones = ParticipacionEvento::with(['user', 'evento'])->get();
        return response()->json($participaciones, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fecha_inscripcion' => 'required|date',
            'estado' => 'required|in:confirmado,pendiente,cancelado',
            'user_id' => 'required|exists:users,id',
            'evento_id' => 'required|exists:eventos,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $participacion = ParticipacionEvento::create($request->all());
        $participacion->load(['user', 'evento']);
        return response()->json($participacion, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ParticipacionEvento $participacionEvento): JsonResponse
    {
        $participacionEvento->load(['user', 'evento']);
        return response()->json($participacionEvento, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParticipacionEvento $participacionEvento): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fecha_inscripcion' => 'sometimes|required|date',
            'estado' => 'sometimes|required|in:confirmado,pendiente,cancelado',
            'user_id' => 'sometimes|required|exists:users,id',
            'evento_id' => 'sometimes|required|exists:eventos,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $participacionEvento->update($request->all());
        $participacionEvento->load(['user', 'evento']);
        return response()->json($participacionEvento, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParticipacionEvento $participacionEvento): JsonResponse
    {
        $participacionEvento->delete();
        return response()->json(['message' => 'Participación eliminada correctamente'], 200);
    }
}
