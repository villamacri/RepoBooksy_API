<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * REGISTRO DE USUARIO (API)
     */
    public function register(Request $request)
    {
        // 1. Validación estricta usando los campos reales de nuestra migración
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // confirmación asume que el frontend enviará 'password' y 'password_confirmation'
            'password' => 'required|string|min:8|confirmed', 
            'telefono' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Creación del usuario con datos limpios
        $user = User::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            
            // SEGURIDAD: Asignamos la fecha exacta desde el servidor. 
            // Nunca confíes en la fecha que envíe el cliente móvil.
            'fecha_registro' => now(), 
            
            // Nota: 'role' y 'estado' no se incluyen aquí para evitar "Privilege Escalation".
            // La base de datos ya les asignará 'usuario' y 'activo' por defecto gracias a la migración.
        ]);

        // 3. Emitimos el token inmediatamente para autologuear al usuario tras registrarse
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Devolvemos el mismo formato de JSON que en el Login
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'message' => 'Registro exitoso'
        ], 201); // 201 = Created
    }

    /**
     * LOGIN DE USUARIO (API)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'message' => 'Login exitoso'
        ], 200);
    }

    /**
     * LOGOUT DE USUARIO (API)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ], 200);
    }
}