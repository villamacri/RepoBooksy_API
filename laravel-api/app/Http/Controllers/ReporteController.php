<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $reportes = Reporte::with('user')->get();
        return response()->json($reportes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tipo_reporte' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_reporte' => 'required|date',
            'estado' => 'required|in:pendiente,en revision,resuelto,cerrado',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $reporte = Reporte::create($request->all());
        $reporte->load('user');
        return response()->json($reporte, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reporte $reporte): JsonResponse
    {
        $reporte->load('user');
        return response()->json($reporte, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reporte $reporte): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tipo_reporte' => 'sometimes|required|string|max:255',
            'descripcion' => 'sometimes|required|string',
            'fecha_reporte' => 'sometimes|required|date',
            'estado' => 'sometimes|required|in:pendiente,en revision,resuelto,cerrado',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $reporte->update($request->all());
        $reporte->load('user');
        return response()->json($reporte, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reporte $reporte): JsonResponse
    {
        $reporte->delete();
        return response()->json(['message' => 'Reporte eliminado correctamente'], 200);
    }
}
