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
        return $request->user();
    });

    // --- FULL MANAGEMENT (CRUD) ---
    
    // Users & Transactions (Financial Module)
    Route::apiResource('users', UserController::class);
    Route::apiResource('transactions', TransactionController::class);
    
    // Books (Laravel automatically adds create/store/update/destroy)
    Route::apiResource('books', BookController::class)->except(['index', 'show']);
    
    // Categories (Protected actions)
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

    // Meetups (Protected actions: create, update, destroy)
    Route::apiResource('meetups', MeetupController::class)->except(['index', 'show']);
    
    // Meetup Attendances (Joining/Leaving meetups)
    Route::apiResource('meetup-attendances', MeetupAttendanceController::class);
});