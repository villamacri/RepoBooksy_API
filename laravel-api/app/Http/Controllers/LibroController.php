<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $libros = Libro::with(['categoria', 'user', 'transaccions', 'feedback'])->get();
        return response()->json($libros, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'editorial' => 'nullable|string|max:255',
            'anio_editorial' => 'nullable|integer|min:1000|max:' . date('Y'),
            'descripcion' => 'nullable|string',
            'estado_fisico' => 'required|in:nuevo,como nuevo,bueno,aceptable,pobre',
            'tipo_operacion' => 'required|in:venta,intercambio,ambos',
            'precio' => 'nullable|numeric|min:0',
            'fecha_publicacion' => 'nullable|date',
            'disponibilidad' => 'required|boolean',
            'categoria_id' => 'required|exists:categorias,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $libro = Libro::create($request->all());
        $libro->load(['categoria', 'user']);
        return response()->json($libro, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Libro $libro): JsonResponse
    {
        $libro->load(['categoria', 'user', 'transaccions', 'feedback.user']);
        return response()->json($libro, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Libro $libro): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'sometimes|required|string|max:255',
            'autor' => 'sometimes|required|string|max:255',
            'editorial' => 'nullable|string|max:255',
            'anio_editorial' => 'nullable|integer|min:1000|max:' . date('Y'),
            'descripcion' => 'nullable|string',
            'estado_fisico' => 'sometimes|required|in:nuevo,como nuevo,bueno,aceptable,pobre',
            'tipo_operacion' => 'sometimes|required|in:venta,intercambio,ambos',
            'precio' => 'nullable|numeric|min:0',
            'fecha_publicacion' => 'nullable|date',
            'disponibilidad' => 'sometimes|required|boolean',
            'categoria_id' => 'sometimes|required|exists:categorias,id',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $libro->update($request->all());
        $libro->load(['categoria', 'user']);
        return response()->json($libro, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Libro $libro): JsonResponse
    {
        $libro->delete();
        return response()->json(['message' => 'Libro eliminado correctamente'], 200);
    }
}
