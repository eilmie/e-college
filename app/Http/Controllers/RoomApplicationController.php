<?php

namespace App\Http\Controllers;

use App\Models\RoomApplication;
use Illuminate\Http\Request;

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

        return view('application.create', ['semester' => $semester, 'user' => $user]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $semester = config('app.application_semester');

        if (! $user->gender || ! $user->program_level || ! $user->current_semester) {
            return redirect()->route('dashboard')
                ->withErrors(['profile' => 'Load your details from the student portal before applying.']);
        }

        $request->validate([
            'medical_proof' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        if (RoomApplication::where('user_id', $user->id)->where('semester', $semester)->exists()) {
            return back()->withErrors(['medical_proof' => 'You have already applied for this semester.']);
        }

        $path = null;
        $medicalStatus = 'none';

        if ($request->hasFile('medical_proof')) {
            $path = $request->file('medical_proof')->store('medical-proofs/'.$user->id, 'local');
            $medicalStatus = 'pending';
        }

        RoomApplication::create([
            'user_id' => $user->id,
            'semester' => $semester,
            'medical_status' => $medicalStatus,
            'medical_proof_path' => $path,
            'merit_score' => $user->merit_score,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('status', 'Application submitted.');
    }
}