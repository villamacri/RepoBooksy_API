<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TransaccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $transacciones = Transaccion::with(['libro', 'comprador', 'vendedor'])->get();
        return response()->json($transacciones, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tipo_transaccion' => 'required|in:venta,intercambio',
            'fecha_transaccion' => 'required|date',
            'monto' => 'nullable|numeric|min:0',
            'metodo_pago' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,completada,cancelada,en proceso',
            'libro_id' => 'required|exists:libros,id',
            'comprador_id' => 'required|exists:users,id',
            'vendedor_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaccion = Transaccion::create($request->all());
        $transaccion->load(['libro', 'comprador', 'vendedor']);
        return response()->json($transaccion, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaccion $transaccion): JsonResponse
    {
        $transaccion->load(['libro', 'comprador', 'vendedor']);
        return response()->json($transaccion, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaccion $transaccion): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tipo_transaccion' => 'sometimes|required|in:venta,intercambio',
            'fecha_transaccion' => 'sometimes|required|date',
            'monto' => 'nullable|numeric|min:0',
            'metodo_pago' => 'sometimes|required|string|max:255',
            'estado' => 'sometimes|required|in:pendiente,completada,cancelada,en proceso',
            'libro_id' => 'sometimes|required|exists:libros,id',
            'comprador_id' => 'sometimes|required|exists:users,id',
            'vendedor_id' => 'sometimes|required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaccion->update($request->all());
        $transaccion->load(['libro', 'comprador', 'vendedor']);
        return response()->json($transaccion, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaccion $transaccion): JsonResponse
    {
        $transaccion->delete();
        return response()->json(['message' => 'Transacción eliminada correctamente'], 200);
    }
}
