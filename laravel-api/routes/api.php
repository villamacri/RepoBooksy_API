<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Importamos TU controlador de autenticación nuevo
use App\Http\Controllers\AuthController; 
// Importamos el resto de controladores
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\ParticipacionEventoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TransaccionController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| 1. RUTAS PÚBLICAS (Cualquiera puede entrar)
|--------------------------------------------------------------------------
*/

// Login (Usa tu AuthController nuevo)
Route::post('/login', [AuthController::class, 'login']);

// Opcional: Lectura pública de catálogos (solo ver lista y detalle)
Route::apiResource('categorias', CategoriaController::class)->only(['index', 'show']);
Route::apiResource('eventos', EventoController::class)->only(['index', 'show']);
// Nota: Si quieres que ver libros sea público, descomenta la siguiente línea:
// Route::apiResource('libros', LibroController::class)->only(['index', 'show']);


/*
|--------------------------------------------------------------------------
| 2. RUTAS PROTEGIDAS (Solo usuarios con Token válido)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {

    // Logout (Cerrar sesión)
    Route::post('/logout', [AuthController::class, 'logout']);

    // Ruta para obtener datos del usuario logueado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- GESTIÓN COMPLETA (CRUD) ---
    // Al estar aquí dentro, nadie puede crear/editar/borrar sin estar logueado.
    
    // Usuarios (Crear, editar, borrar usuarios)
    Route::apiResource('users', UserController::class);

    // Libros, Transacciones, etc.
    Route::apiResource('libros', LibroController::class);
    Route::apiResource('transacciones', TransaccionController::class);
    Route::apiResource('feedback', FeedbackController::class);
    Route::apiResource('participacion-eventos', ParticipacionEventoController::class);
    Route::apiResource('reportes', ReporteController::class);
    
    // Categorias y Eventos (Gestión completa para admins/usuarios)
    // Laravel es listo: si usas 'apiResource' aquí dentro, añadirá las rutas 
    // de create/update/delete que faltaban en la parte pública.
    Route::apiResource('categorias', CategoriaController::class)->except(['index', 'show']);
    Route::apiResource('eventos', EventoController::class)->except(['index', 'show']);
});