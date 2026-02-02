<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validamos que envíen email y password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Intentamos loguear (Auth::attempt encripta la pass y la compara)
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // 3. Si pasa, buscamos al usuario por su email
        // Usamos 'firstOrFail' por seguridad, aunque Auth::attempt ya confirmó que existe
        $user = User::where('email', $request->email)->firstOrFail();

        // 4. Creamos el token de Sanctum
        // 'auth_token' es un nombre interno para el token, puedes poner lo que quieras
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Devolvemos la respuesta exacta que espera Postman/Angular
        return response()->json([
            'token' => $token,           // Importante: token en la raíz del JSON
            'token_type' => 'Bearer',
            'user' => $user,             // Opcional: devolvemos datos del usuario
            'message' => 'Login exitoso'
        ], 200);
    }

    /**
     * LOGOUT: Elimina el token actual para cerrar la sesión.
     */
    public function logout(Request $request)
    {
        // Borra el token que se usó para hacer esta petición
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ], 200);
    }
}