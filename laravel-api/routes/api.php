<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MeetupController;
use App\Http\Controllers\MeetupAttendanceController;
use App\Http\Controllers\HomeFeedController;
use App\Http\Controllers\StatsController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Anyone can access)
|--------------------------------------------------------------------------
*/

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Catalogs (Read-only for app visitors)
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::get('/books/latest', [BookController::class, 'latestBooks']);
Route::apiResource('books', BookController::class)->only(['index', 'show']);
Route::apiResource('meetups', MeetupController::class)->only(['index', 'show']); 

/*
|--------------------------------------------------------------------------
| 2. PROTECTED ROUTES (Requires valid Token)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {

    // Logout (Destroy token)
    Route::post('/logout', [AuthController::class, 'logout']);

    // Get logged-in user data
    Route::get('/user', function (Request $request) {
        return User::with(['meetupAttendances.meetup'])
            ->withCount(['books', 'meetups', 'transactions'])
            ->find($request->user()->id);
    });
    Route::get('/user/profile-stats', [UserController::class, 'profileStats']);

    // Home feed for mobile app
    Route::get('/home/feed', [HomeFeedController::class, 'index']);

    // Admin Dashboard & Stats
    Route::get('/admin/dashboard-stats', [StatsController::class, 'getSummary']);

    // --- FULL MANAGEMENT (CRUD) ---
    
    // Users & Transactions (Financial Module)
    Route::apiResource('users', UserController::class);
    Route::get('/transactions/my-upcoming-appointments', [TransactionController::class, 'myUpcomingAppointments']);
    Route::apiResource('transactions', TransactionController::class);
    
    // Books (Laravel automatically adds create/store/update/destroy)
    Route::apiResource('books', BookController::class)->except(['index', 'show']);
    
    // Categories (Protected actions)
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

    // Meetups (Protected actions: create, update, destroy)
    Route::post('/meetups/{meetup}/attendances', [MeetupController::class, 'join']);
    Route::post('/meetups/{meetup}/join', [MeetupController::class, 'join']);
    Route::get('/meetups/{meetup}/participants', [MeetupController::class, 'getParticipants']);
    Route::apiResource('meetups', MeetupController::class)->except(['index', 'show']);
    
    // Meetup Attendances (Joining/Leaving meetups)
    Route::apiResource('meetup-attendances', MeetupAttendanceController::class);
});