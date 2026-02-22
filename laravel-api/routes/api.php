<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
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

// Autenticación y Registro
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Catálogos Públicos (Solo lectura para atraer al usuario en la app móvil)
Route::apiResource('categorias', CategoriaController::class)->only(['index', 'show']);
Route::apiResource('libros', LibroController::class)->only(['index', 'show']); // ¡Descomentado!

/*
|--------------------------------------------------------------------------
| 2. RUTAS PROTEGIDAS (Solo usuarios con Token válido)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {

    // Logout (Cerrar sesión destruyendo el token)
    Route::post('/logout', [AuthController::class, 'logout']);

    // Ruta para obtener datos del usuario logueado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- GESTIÓN COMPLETA (CRUD) ---
    
    // Usuarios y Transacciones (Módulo Financiero)
    Route::apiResource('users', UserController::class);
    Route::apiResource('transacciones', TransaccionController::class);
    
    // Libros (Laravel añadirá automáticamente create/store/update/destroy porque index y show ya son públicos)
    Route::apiResource('libros', LibroController::class)->except(['index', 'show']);
    
    // Módulos fuera del alcance del MVP móvil (pero listos en el backend)
    Route::apiResource('feedback', FeedbackController::class);
    Route::apiResource('participacion-eventos', ParticipacionEventoController::class);
    Route::apiResource('reportes', ReporteController::class);
    Route::apiResource('eventos', EventoController::class)->except(['index', 'show']);
    Route::apiResource('categorias', CategoriaController::class)->except(['index', 'show']);
});