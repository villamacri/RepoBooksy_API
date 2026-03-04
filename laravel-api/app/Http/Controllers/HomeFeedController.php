<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Meetup;
use App\Models\MeetupAttendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeFeedController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'city' => 'nullable|string|max:100',
            'books_limit' => 'nullable|integer|min:1|max:30',
            'events_limit' => 'nullable|integer|min:1|max:30',
            'appointments_limit' => 'nullable|integer|min:1|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $city = $request->query('city');
        $booksLimit = (int) $request->query('books_limit', 10);
        $eventsLimit = (int) $request->query('events_limit', 10);
        $appointmentsLimit = (int) $request->query('appointments_limit', 10);

        $latestBooks = Book::with(['category', 'user'])
            ->where('is_available', true)
            ->latest('created_at')
            ->limit($booksLimit)
            ->get()
            ->toArray();

        $upcomingMeetupsQuery = Meetup::query()
            ->whereDate('meetup_date', '>=', now()->toDateString())
            ->orderBy('meetup_date', 'asc');

        $upcomingMeetupsQuery->when($city, fn($query) => $query->where('city', $city));

        $upcomingMeetupsInCity = $upcomingMeetupsQuery
            ->limit($eventsLimit)
            ->get()
            ->toArray();

        $attendanceAppointments = MeetupAttendance::with(['meetup'])
            ->where('user_id', $request->user()->id)
            ->where('status', 'confirmed')
            ->whereHas('meetup', function ($query) {
                $query->whereDate('meetup_date', '>=', now()->toDateString());
            })
            ->orderBy('enrollment_date', 'desc')
            ->limit($appointmentsLimit)
            ->get()
            ->map(function (MeetupAttendance $attendance) {
                return [
                    'type' => 'meetup',
                    'id' => $attendance->id,
                    'meetup_id' => $attendance->meetup_id,
                    'title' => $attendance->meetup?->name,
                    'location' => $attendance->meetup?->location,
                    'date_time' => optional($attendance->meetup?->meetup_date)->format('Y-m-d'),
                    'status' => $attendance->status,
                ];
            });

        $joinedMeetups = Meetup::query()
            ->whereDate('meetup_date', '>=', now()->toDateString())
            ->whereHas('users', function ($query) use ($request) {
                $query->where('users.id', $request->user()->id);
            })
            ->get()
            ->map(function (Meetup $meetup) {
                return [
                    'type' => 'meetup',
                    'id' => $meetup->id,
                    'meetup_id' => $meetup->id,
                    'title' => $meetup->name,
                    'location' => $meetup->location,
                    'date_time' => optional($meetup->meetup_date)->format('Y-m-d'),
                    'status' => 'joined',
                ];
            });

        $myUpcomingAppointments = $attendanceAppointments
            ->concat($joinedMeetups)
            ->unique('meetup_id')
            ->sortBy('date_time')
            ->values()
            ->take($appointmentsLimit)
            ->toArray();

        return response()->json([
            'latest_books' => $latestBooks ?? [],
            'upcoming_meetups_in_city' => $upcomingMeetupsInCity ?? [],
            'my_confirmed_appointments' => $myUpcomingAppointments ?? [],
            'meta' => [
                'city_filter' => $city,
            ],
        ], 200);
    }
}
