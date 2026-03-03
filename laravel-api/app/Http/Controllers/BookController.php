<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $books = Book::with(['category', 'user', 'transactions', 'feedbacks'])->get();
        return response()->json($books, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'description' => 'nullable|string',
            'physical_condition' => 'required|in:new,like_new,good,acceptable,poor',
            'operation_type' => 'required|in:sale,exchange,both',
            'price' => 'nullable|numeric|min:0',
            'publication_date' => 'nullable|date',
            'category_id' => 'required|exists:categories,id',
            // user_id y is_available se manejan en el backend
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Recuperamos los datos validados
        $dataToSave = $validator->validated();

        // Inyectamos de forma segura los datos automáticos
        $dataToSave['user_id'] = $request->user()->id;
        $dataToSave['is_available'] = true;

        $book = Book::create($dataToSave);
        $book->load(['category', 'user']);

        return response()->json($book, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): JsonResponse
    {
        $book->load(['category', 'user', 'transactions', 'feedbacks.user']);
        return response()->json($book, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'description' => 'nullable|string',
            'physical_condition' => 'sometimes|required|in:new,like_new,good,acceptable,poor',
            'operation_type' => 'sometimes|required|in:sale,exchange,both',
            'price' => 'nullable|numeric|min:0',
            'publication_date' => 'nullable|date',
            'is_available' => 'sometimes|required|boolean',
            'category_id' => 'sometimes|required|exists:categories,id',
            // user_id generalmente no se actualiza, el libro pertenece a quien lo subió
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $book->update($validator->validated());
        $book->load(['category', 'user']);
        return response()->json($book, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();
        return response()->json(['message' => 'Book deleted successfully'], 200);
    }
}