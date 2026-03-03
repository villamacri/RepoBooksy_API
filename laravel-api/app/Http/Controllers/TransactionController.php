<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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
        $transactions = Transaction::with(['book', 'buyer', 'seller'])->get();
        return response()->json($transactions, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'transaction_type' => 'required|in:sale,exchange',
            'transaction_date' => 'required|date',
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:255', // Lo pasé a nullable basado en la migración
            'status' => 'required|in:pending,completed,cancelled,in_progress',
            'book_id' => 'required|exists:books,id',
            'buyer_id' => 'required|exists:users,id',
            'seller_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction = Transaction::create($validator->validated());
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