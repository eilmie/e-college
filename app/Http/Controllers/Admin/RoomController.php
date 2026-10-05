<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\Allocation;
use App\Models\RoomApplication;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::withCount(['allocations as occupants' => fn ($q) => $q
                ->where('semester', config('app.application_semester'))
                ->where('status', 'active')]);

        if ($request->filled('block')) {
            $query->where('block', $request->block);
        }

        if ($request->filled('search')) {
            $query->where('room_number', 'like', '%'.$request->search.'%');
        }

        $rooms = $query->orderBy('block')->orderBy('room_number')->get();

        return view('admin.rooms.index', [
            'rooms' => $rooms,
            'selectedBlock' => $request->block,
            'search' => $request->search,
        ]);
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'block' => ['required', 'string', 'max:50'],
            'room_number' => ['required', 'string', 'max:20'],
            'floor' => ['required', 'integer', 'min:0', 'max:50'],
            'capacity' => ['required', 'integer', 'min:1', 'max:10'],
            'gender' => ['required', 'in:male,female'],
        ]);

        Room::create($validated);

        return redirect()->route('admin.rooms.index')->with('status', 'Room added.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'block' => ['required', 'string', 'max:50'],
            'room_number' => ['required', 'string', 'max:20'],
            'floor' => ['required', 'integer', 'min:0', 'max:50'],
            'capacity' => ['required', 'integer', 'min:1', 'max:10'],
            'gender' => ['required', 'in:male,female'],
            'status' => ['required', 'in:available,full,maintenance'],
        ]);

        $room->update($validated);

        return redirect()->route('admin.rooms.index')->with('status', 'Room updated.');
    }

    public function destroy(Room $room)
    {
        if ($room->allocations()->where('status', 'active')->exists()) {
            return back()->withErrors(['room' => 'Cannot delete a room with active allocations.']);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('status', 'Room deleted.');
    }

    public function show(Room $room)
    {
        $semester = config('app.application_semester');

        $occupants = Allocation::with('user')
            ->where('room_id', $room->id)
            ->where('semester', $semester)
            ->where('status', 'active')
            ->get();

        $availableApplications = RoomApplication::with('user')
            ->where('semester', $semester)
            ->where('status', 'pending')
            ->whereHas('user', fn ($q) => $q->where('gender', $room->gender))
            ->get();

        return view('admin.rooms.show', compact('room', 'occupants', 'availableApplications', 'semester'));
    }

    public function assignStudent(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_application_id' => ['required', 'exists:room_applications,id'],
        ]);

        $semester = config('app.application_semester');
        $application = RoomApplication::with('user')->findOrFail($validated['room_application_id']);

        if ($application->user->gender !== $room->gender) {
            return back()->withErrors(['room_application_id' => 'Student gender does not match this room.']);
        }

        $occupied = Allocation::where('room_id', $room->id)
            ->where('semester', $semester)
            ->where('status', 'active')
            ->count();

        if ($occupied >= $room->capacity) {
            return back()->withErrors(['room_application_id' => 'This room is already full.']);
        }

        Allocation::create([
            'user_id' => $application->user_id,
            'room_id' => $room->id,
            'room_application_id' => $application->id,
            'semester' => $semester,
        ]);

        $application->update(['status' => 'approved']);

        return back()->with('status', 'Student assigned to room.');
    }

    public function removeStudent(Allocation $allocation)
    {
        $roomId = $allocation->room_id;

        $allocation->update(['status' => 'ended']);

        if ($allocation->roomApplication) {
            $allocation->roomApplication->update(['status' => 'pending']);
        }

        return redirect()->route('admin.rooms.show', $roomId)->with('status', 'Student removed from room.');
    }
}