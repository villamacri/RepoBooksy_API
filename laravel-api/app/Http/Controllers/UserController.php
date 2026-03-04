<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profileStats(Request $request): JsonResponse
    {
        $user = User::withCount(['books', 'meetups', 'transactions'])->find($request->user()->id);

        return response()->json($user, 200);
    }

    public function index(): JsonResponse
    {
        $users = User::with(['books', 'purchases', 'sales', 'meetupAttendances'])->get();
        return response()->json($users, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,moderator,admin', // Traducido
            'reputation' => 'nullable|numeric|min:0|max:5',
            'category_preferences' => 'nullable|string',
            'access_level' => 'nullable|string|max:50',
            'responsibility_area' => 'nullable|string',
            'registration_date' => 'required|date',
            'status' => 'required|in:active,inactive,suspended', // Traducido
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        return response()->json($user, 201);
    }

    public function show(User $user): JsonResponse
    {
        $user->load(['books', 'purchases', 'sales', 'meetupAttendances']);
        return response()->json($user, 200);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'role' => 'sometimes|required|in:user,moderator,admin',
            'reputation' => 'nullable|numeric|min:0|max:5',
            'category_preferences' => 'nullable|string',
            'access_level' => 'nullable|string|max:50',
            'responsibility_area' => 'nullable|string',
            'registration_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return response()->json($user, 200);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}