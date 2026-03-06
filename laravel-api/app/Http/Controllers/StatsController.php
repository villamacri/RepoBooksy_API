<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\Meetup;
use App\Models\Transaction;
use App\Models\Category; // Añadimos el modelo Category
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    /**
     * Get dashboard summary statistics
     * * @return JsonResponse
     */
    public function getSummary(): JsonResponse
    {
        $stats = [
            // 1. Las 4 tarjetas superiores (Mejoradas con filtros reales)
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'active_meetups' => Meetup::where('meetup_date', '>=', now())->count(), // Solo meetups futuros
            'successful_exchanges' => Transaction::where('status', 'completed')->count(), // Solo transacciones completadas

            // 2. Últimos Movimientos (Los 5 usuarios más recientes)
            'recent_users' => User::orderBy('id', 'desc')
                ->take(5)
                ->get(['name', 'role', 'registration_date', 'created_at']),

            // 3. Libros por Categoría (Para las barras de progreso)
            'books_by_category' => Category::withCount('books')
                ->having('books_count', '>', 0) // Solo enviamos las que tienen al menos 1 libro
                ->orderBy('books_count', 'desc')
                ->get()
        ];

        return response()->json($stats, 200);
    }
}