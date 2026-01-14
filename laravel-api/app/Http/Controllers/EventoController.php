<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $eventos = Evento::with('participacionEventos')->get();
        return response()->json($eventos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_evento' => 'required|date',
            'ubicacion' => 'required|string|max:255',
            'capacidad_maxima' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $evento = Evento::create($request->all());
        return response()->json($evento, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Evento $evento): JsonResponse
    {
        $evento->load('participacionEventos.user');
        return response()->json($evento, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evento $evento): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_evento' => 'sometimes|required|date',
            'ubicacion' => 'sometimes|required|string|max:255',
            'capacidad_maxima' => 'sometimes|required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $evento->update($request->all());
        return response()->json($evento, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evento $evento): JsonResponse
    {
        $evento->delete();
        return response()->json(['message' => 'Evento eliminado correctamente'], 200);
    }
}
