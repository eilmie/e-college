<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\RoomApplication;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        if ($user->is_admin) {
            return redirect()->route('admin.applications.index');
        }

        $semester = config('app.application_semester');

        $isFresh = $user->program_level === 'diploma' && (int) $user->current_semester === 1;
        $profileReady = $user->gender && $user->program_level && $user->current_semester;

        $application = RoomApplication::where('user_id', $user->id)
            ->where('semester', $semester)
            ->first();

        $allocation = Allocation::with('room')
            ->where('user_id', $user->id)
            ->where('semester', $semester)
            ->where('status', 'active')
            ->first();

        $roommates = $allocation
            ? Allocation::with('user')
                ->where('room_id', $allocation->room_id)
                ->where('semester', $semester)
                ->where('status', 'active')
                ->where('user_id', '!=', $user->id)
                ->get()
            : collect();

        return view('dashboard', compact('user', 'semester', 'isFresh', 'profileReady', 'application', 'allocation', 'roommates'));
    }
}