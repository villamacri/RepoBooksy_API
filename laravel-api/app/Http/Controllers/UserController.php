<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::with(['libros', 'transaccions', 'feedback', 'reportes', 'participacionEventos'])->get();
        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'telefono' => 'nullable|string|max:20',
            'role' => 'required|in:usuario,admin,moderador',
            'reputacion' => 'nullable|numeric|min:0|max:5',
            'preferencias_categoria' => 'nullable|string',
            'nivel_acceso' => 'nullable|string|max:50',
            'area_responsabilidad' => 'nullable|string',
            'fecha_registro' => 'required|date',
            'estado' => 'required|in:activo,inactivo,suspendido',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        $user->load(['libros', 'transaccions', 'feedback', 'reportes', 'participacionEventos']);
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'apellidos' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
            'telefono' => 'nullable|string|max:20',
            'role' => 'sometimes|required|in:usuario,admin,moderador',
            'reputacion' => 'nullable|numeric|min:0|max:5',
            'preferencias_categoria' => 'nullable|string',
            'nivel_acceso' => 'nullable|string|max:50',
            'area_responsabilidad' => 'nullable|string',
            'fecha_registro' => 'sometimes|required|date',
            'estado' => 'sometimes|required|in:activo,inactivo,suspendido',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }
}
