<?php
require __DIR__ . '/auth.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\ParticipacionEventoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TransaccionController;
use App\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas públicas (sin autenticación)
Route::apiResource('categorias', CategoriaController::class)->only(['index', 'show']);
Route::apiResource('eventos', EventoController::class)->only(['index', 'show']);
Route::apiResource('libros', LibroController::class)->only(['index', 'show']);

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth:sanctum'])->group(function () {
});


    Route::apiResource('categorias', CategoriaController::class);
    
    // Eventos
    Route::apiResource('eventos', EventoController::class);
    
    // Libros
    Route::apiResource('libros', LibroController::class);
    
    // Feedback
    Route::apiResource('feedback', FeedbackController::class);
    
    // Participaciones en Eventos
    Route::apiResource('participacion-eventos', ParticipacionEventoController::class);
    
    // Reportes
    Route::apiResource('reportes', ReporteController::class);
    
    // Transacciones
    Route::apiResource('transacciones', TransaccionController::class);
    
    // Usuarios
    Route::apiResource('users', UserController::class);
