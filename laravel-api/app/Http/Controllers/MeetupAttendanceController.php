<?php

namespace App\Http\Controllers;

use App\Models\MeetupAttendance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MeetupAttendanceController extends Controller
{
    public function index(): JsonResponse
    {
        $attendances = MeetupAttendance::with(['user', 'meetup'])->get();
        return response()->json($attendances, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'enrollment_date' => 'required|date',
            // Traducimos los estados al inglés para mantener consistencia
            'status' => 'required|in:confirmed,cancelled,waitlisted', 
            'user_id' => 'required|exists:users,id',
            // Validamos contra la nueva tabla 'meetups'
            'meetup_id' => 'required|exists:meetups,id', 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $attendance = MeetupAttendance::create($validator->validated());
        $attendance->load(['user', 'meetup']);
        return response()->json($attendance, 201);
    }

    public function show(MeetupAttendance $meetupAttendance): JsonResponse
    {
        // Nota: Laravel inyectará la variable en camelCase ($meetupAttendance)
        $meetupAttendance->load(['user', 'meetup']);
        return response()->json($meetupAttendance, 200);
    }

    public function update(Request $request, MeetupAttendance $meetupAttendance): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'enrollment_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:confirmed,cancelled,waitlisted',
            'user_id' => 'sometimes|required|exists:users,id',
            'meetup_id' => 'sometimes|required|exists:meetups,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $meetupAttendance->update($validator->validated());
        $meetupAttendance->load(['user', 'meetup']);
        return response()->json($meetupAttendance, 200);
    }

    public function destroy(MeetupAttendance $meetupAttendance): JsonResponse
    {
        $meetupAttendance->delete();
        return response()->json(['message' => 'Attendance deleted successfully'], 200);
    }
}