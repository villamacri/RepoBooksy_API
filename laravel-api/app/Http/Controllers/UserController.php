<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function profileStats(Request $request): JsonResponse
    {
        $user = User::withCount(['books', 'meetups', 'transactions'])->find($request->user()->id);

        return response()->json($user, 200);
    }

    public function index(): JsonResponse
    {
        $users = User::with(['books', 'purchases', 'sales', 'meetupAttendances.meetup'])->get();
        return response()->json($users, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'reputation' => 'nullable|numeric|min:0|max:5',
            'category_preferences' => 'nullable|string',
            'access_level' => 'nullable|string|max:50',
            'responsibility_area' => 'nullable|string',
            'registration_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive,suspended', // Traducido
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $user = new User();
        $user->name = $validated['name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->role = 'admin';
        $user->reputation = $validated['reputation'] ?? null;
        $user->category_preferences = $validated['category_preferences'] ?? null;
        $user->access_level = $validated['access_level'] ?? null;
        $user->responsibility_area = $validated['responsibility_area'] ?? null;
        $user->registration_date = $validated['registration_date'] ?? now()->toDateString();
        $user->status = $validated['status'] ?? 'active';
        $user->password = $request->filled('password') ? (string) $request->input('password') : 'password123';
        $user->save();

        return response()->json($user, 201);
    }

    public function show(User $user): JsonResponse
    {
        $user->load(['books', 'purchases', 'sales', 'meetupAttendances.meetup']);
        return response()->json($user, 200);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|nullable|string|min:8',
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

        if ($request->filled('password')) {
            $data['password'] = (string) $request->input('password');
        } else {
            unset($data['password']);
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