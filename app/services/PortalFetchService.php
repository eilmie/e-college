<?php

namespace App\Services;

use App\Models\StudentPortalData;
use App\Models\User;

class PortalFetchService
{
    public function fetch(User $user): bool
    {
        $record = StudentPortalData::where('student_id', $user->student_id)->first();

        if (! $record) {
            return false;
        }

        $user->update([
            'name' => $record->full_name,
            'gender' => $record->gender,
            'program_level' => $record->program_level,
            'current_semester' => $record->current_semester,
            'merit_score' => $record->cgpa * 20, // simple CGPA-to-merit conversion, adjust the formula to match actual college policy
        ]);

        return true;
    }
}