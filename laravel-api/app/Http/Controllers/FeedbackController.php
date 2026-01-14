<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $feedbacks = Feedback::with(['user', 'libro'])->get();
        return response()->json($feedbacks, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string',
            'fecha_feedback' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'libro_id' => 'required|exists:libros,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $feedback = Feedback::create($request->all());
        $feedback->load(['user', 'libro']);
        return response()->json($feedback, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback): JsonResponse
    {
        $feedback->load(['user', 'libro']);
        return response()->json($feedback, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'puntuacion' => 'sometimes|required|integer|min:1|max:5',
            'comentario' => 'nullable|string',
            'fecha_feedback' => 'sometimes|required|date',
            'user_id' => 'sometimes|required|exists:users,id',
            'libro_id' => 'sometimes|required|exists:libros,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $feedback->update($request->all());
        $feedback->load(['user', 'libro']);
        return response()->json($feedback, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback): JsonResponse
    {
        $feedback->delete();
        return response()->json(['message' => 'Feedback eliminado correctamente'], 200);
    }
}
