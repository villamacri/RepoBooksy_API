<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\Meetup;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    /**
     * Get dashboard summary statistics
     * 
     * @return JsonResponse
     */
    public function getSummary(): JsonResponse
    {
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'active_meetups' => Meetup::count(),
            'successful_exchanges' => Transaction::count(),
        ];

        return response()->json($stats, 200);
    }
}
