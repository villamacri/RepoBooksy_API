<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Meetup;
use App\Models\MeetupAttendance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $transactions = Transaction::with(['book', 'buyer', 'seller'])->latest()->get();
        return response()->json($transactions, 200);
    }

    /**
     * Return upcoming user appointments from pending transactions or confirmed meetups.
     */
    public function myUpcomingAppointments(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $pendingTransactions = Transaction::with(['book:id,title'])
            ->where(function ($query) use ($userId) {
                $query->where('buyer_id', $userId)
                    ->orWhere('seller_id', $userId);
            })
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('transaction_date', 'asc')
            ->get()
            ->map(function (Transaction $transaction) {
                return [
                    'type' => 'transaction',
                    'id' => $transaction->id,
                    'user_id' => $transaction->buyer_id,
                    'local_id' => null,
                    'book_id' => $transaction->book_id,
                    'title' => $transaction->book?->title,
                    'location' => null,
                    'date_time' => optional($transaction->transaction_date)->format('Y-m-d'),
                    'status' => $transaction->status,
                ];
            });

        $confirmedMeetups = MeetupAttendance::with(['meetup:id,name,location,meetup_date'])
            ->where('user_id', $userId)
            ->where('status', 'confirmed')
            ->whereHas('meetup', function ($query) {
                $query->whereDate('meetup_date', '>=', now()->toDateString());
            })
            ->get()
            ->map(function (MeetupAttendance $attendance) {
                return [
                    'type' => 'meetup',
                    'id' => $attendance->id,
                    'user_id' => $attendance->user_id,
                    'local_id' => $attendance->meetup_id,
                    'meetup_id' => $attendance->meetup_id,
                    'title' => $attendance->meetup?->name,
                    'location' => $attendance->meetup?->location,
                    'date_time' => optional($attendance->meetup?->meetup_date)->format('Y-m-d'),
                    'status' => $attendance->status,
                ];
            });

        $joinedMeetups = Meetup::query()
            ->whereDate('meetup_date', '>=', now()->toDateString())
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->get()
            ->map(function (Meetup $meetup) use ($userId) {
                return [
                    'type' => 'meetup',
                    'id' => $meetup->id,
                    'user_id' => $userId,
                    'local_id' => $meetup->id,
                    'meetup_id' => $meetup->id,
                    'title' => $meetup->name,
                    'location' => $meetup->location,
                    'date_time' => optional($meetup->meetup_date)->format('Y-m-d'),
                    'status' => 'joined',
                ];
            });

        $appointments = $pendingTransactions
            ->concat($confirmedMeetups)
            ->concat($joinedMeetups)
            ->unique(function ($item) {
                return $item['type'] === 'meetup'
                    ? 'meetup-' . ($item['meetup_id'] ?? $item['id'])
                    : 'transaction-' . $item['id'];
            })
            ->sortBy('date_time')
            ->values();

        return response()->json([
            'appointments' => $appointments,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Validamos solo lo que nos envía Flutter
        $validator = Validator::make($request->all(), [
            'transaction_type' => 'required|in:sale,exchange',
            'amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,completed,cancelled,in_progress',
            'book_id' => 'required|exists:books,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // 2. Buscamos el libro para saber quién es el dueño (el vendedor)
        $book = \App\Models\Book::findOrFail($data['book_id']);

        // 3. Autocompletamos los datos de forma 100% segura en el servidor
        $data['buyer_id'] = $request->user()->id; // El usuario logueado en la app
        $data['seller_id'] = $book->user_id;      // El dueño original del libro
        $data['transaction_date'] = now();        // Fecha y hora actual

        // Extra de seguridad: Evitar que el usuario se compre/intercambie su propio libro
        if ($data['buyer_id'] === $data['seller_id']) {
            return response()->json([
                'errors' => ['book_id' => ['No puedes solicitar tu propio libro.']]
            ], 422);
        }

        // 4. Creamos la transacción
        $transaction = Transaction::create($data);
        $transaction->load(['book', 'buyer', 'seller']);
        
        return response()->json($transaction, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction): JsonResponse
    {
        $transaction->load(['book', 'buyer', 'seller']);
        return response()->json($transaction, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'transaction_type' => 'sometimes|required|in:sale,exchange',
            'transaction_date' => 'sometimes|required|date',
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'sometimes|nullable|string|max:255',
            'status' => 'sometimes|required|in:pending,completed,cancelled,in_progress',
            'book_id' => 'sometimes|required|exists:books,id',
            'buyer_id' => 'sometimes|required|exists:users,id',
            'seller_id' => 'sometimes|required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction->update($validator->validated());
        $transaction->load(['book', 'buyer', 'seller']);
        return response()->json($transaction, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): JsonResponse
    {
        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully'], 200);
    }
}