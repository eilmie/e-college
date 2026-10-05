<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomApplication;
use Illuminate\Http\Request;
use App\Services\AllocationService;
class RoomApplicationController extends Controller
{
    public function runAllocation(AllocationService $service)
    {
        $semester = config('app.application_semester');
        $result = $service->run($semester);

        return back()->with('status', "Allocation complete for {$semester}: {$result['allocated']} allocated, {$result['rejected']} rejected.");
    }
    public function index()
    {
        $applications = RoomApplication::with('user')
            ->where('semester', config('app.application_semester'))
            ->where('status', 'pending')
            ->orderByRaw("medical_status = 'verified' DESC")
            ->orderByDesc('merit_score')
            ->orderBy('has_disciplinary_record')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get()
            ->values();

        return view('admin.applications.index', compact('applications'));
    }

        public function viewProof(RoomApplication $application)
    {
        abort_unless($application->medical_proof_path, 404);

        $path = storage_path('app/'.$application->medical_proof_path);

        abort_unless(file_exists($path), 404);

        return response()->file($path);
    }

    public function verifyMedical(Request $request, RoomApplication $application)
    {
        $request->validate(['decision' => ['required', 'in:verified,rejected']]);
        $application->update(['medical_status' => $request->decision]);
        return back()->with('status', 'Medical status updated for '.$application->user->name);
    }

    public function setDisciplinary(Request $request, RoomApplication $application)
    {
        $request->validate(['has_record' => ['required', 'boolean']]);
        $application->update(['has_disciplinary_record' => $request->has_record]);
        return back()->with('status', 'Disciplinary record updated for '.$application->user->name);
    }
}