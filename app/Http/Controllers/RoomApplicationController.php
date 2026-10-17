<?php

namespace App\Http\Controllers;

use App\Models\RoomApplication;
use App\Http\Requests\StoreRoomApplicationRequest;
use App\Models\StudentPortalData;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RoomApplicationController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $semester = config('app.application_semester');

        if (! $user->gender || ! $user->program_level || ! $user->current_semester) {
            return redirect()->route('dashboard')
                ->withErrors(['profile' => 'Load your details from the student portal before applying.']);
        }

        if ($user->program_level === 'diploma' && (int) $user->current_semester === 1) {
            return redirect()->route('dashboard')
                ->with('status', 'Semester 1 diploma students are placed automatically and do not need to apply.');
        }

        $existing = RoomApplication::where('user_id', $user->id)
            ->where('semester', $semester)
            ->first();

        if ($existing) {
            return view('application.status', ['application' => $existing]);
        }

        $portal = StudentPortalData::where('student_id', $user->student_id)->first();

        return view('application.create', compact('user', 'semester', 'portal'));
    }

    public function store(StoreRoomApplicationRequest $request)
{
    $user = auth()->user();
    $semester = config('app.application_semester');

    if (! $user->gender || ! $user->program_level || ! $user->current_semester) {
        return redirect()->route('dashboard')
            ->withErrors(['profile' => 'Load your details from the student portal before applying.']);
    }

    if (RoomApplication::where('user_id', $user->id)->where('semester', $semester)->exists()) {
        return redirect()->route('application.create');
    }

    $details = Arr::except($request->validated(), ['medical_proof', 'declaration']);

    foreach (['practical_next_semester', 'is_married', 'has_vehicle'] as $flag) {
        $details[$flag] = $request->boolean($flag);
    }

    // Clear answers that belong to a "No" choice
    if (! $details['practical_next_semester']) {
        $details['practical_from'] = $details['practical_to'] = null;
    }
    if (! $details['is_married']) {
        foreach (['spouse_name', 'spouse_occupation', 'spouse_income', 'spouse_phone'] as $field) {
            $details[$field] = null;
        }
    }
    if (! $details['has_vehicle']) {
        foreach (['vehicle_type', 'vehicle_model', 'vehicle_plate'] as $field) {
            $details[$field] = null;
        }
    }
    foreach (['dependants_private_uni', 'dependants_public_uni', 'dependants_secondary', 'dependants_primary', 'dependants_preschool', 'dependants_not_in_school'] as $field) {
        $details[$field] = (int) ($details[$field] ?? 0);
    }
    $details['declared_at'] = now();

    $path = null;
    $medicalStatus = 'none';

    if ($request->hasFile('medical_proof')) {
        $path = $request->file('medical_proof')->store('medical-proofs/'.$user->id, 'local');
        $medicalStatus = 'pending';
    }

    DB::transaction(function () use ($user, $semester, $path, $medicalStatus, $details) {
        $application = RoomApplication::create([
            'user_id' => $user->id,
            'semester' => $semester,
            'medical_status' => $medicalStatus,
            'medical_proof_path' => $path,
            'merit_score' => $user->merit_score,
            'status' => 'pending',
        ]);

        $application->detail()->create($details);
    });

    return redirect()->route('dashboard')->with('status', 'Application submitted.');
}
}