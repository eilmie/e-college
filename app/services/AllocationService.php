<?php

namespace App\Services;

use App\Models\Allocation;
use App\Models\Room;
use App\Models\RoomApplication;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AllocationService
{
    public function run(string $semester): array
    {
        $result = ['allocated' => 0, 'rejected' => 0];

        DB::transaction(function () use ($semester, &$result) {
            // 1. Fresh diploma semester 1 students: automatically accepted, no application needed
            $fresh = User::where('program_level', 'diploma')
                ->where('current_semester', 1)
                ->whereDoesntHave('allocations', fn ($q) => $q->where('semester', $semester))
                ->get();

            foreach ($fresh as $student) {
                $this->assign($student, null, $semester) ? $result['allocated']++ : $result['rejected']++;
            }

            // 2. Everyone else, in priority order
            $applications = RoomApplication::with('user')
                ->where('semester', $semester)
                ->where('status', 'pending')
                ->orderByRaw("medical_status = 'verified' DESC")
                ->orderByDesc('merit_score')
                ->orderBy('has_disciplinary_record')
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();

            foreach ($applications as $application) {
                $ok = $this->assign($application->user, $application, $semester);
                $application->update(['status' => $ok ? 'approved' : 'rejected']);
                $ok ? $result['allocated']++ : $result['rejected']++;
            }
        });

        return $result;
    }

    private function assign(User $student, ?RoomApplication $application, string $semester): bool
    {
        $room = Room::where('gender', $student->gender)
            ->where('status', '!=', 'maintenance')
            ->withCount(['allocations as occupants' => fn ($q) => $q
                ->where('semester', $semester)
                ->where('status', 'active')])
            ->havingRaw('occupants < capacity')
            ->orderBy('id')
            ->first();

        if (! $room) {
            return false;
        }

        Allocation::create([
            'user_id' => $student->id,
            'room_id' => $room->id,
            'room_application_id' => $application?->id,
            'semester' => $semester,
        ]);

        return true;
    }
}